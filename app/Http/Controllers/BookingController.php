<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Browse available service categories.
     */
    public function browse()
    {
        $categories = ServiceCategory::all();
        return view('bookings.browse', compact('categories'));
    }

    /**
     * Show booking form for a specific service category.
     */
    public function create($category_id)
    {
        $category = ServiceCategory::findOrFail($category_id);
        $user = Auth::user();
        return view('bookings.create', compact('category', 'user'));
    }

    /**
     * Submit and store home service booking request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'service_date' => 'required|date|after_or_equal:today',
            'service_time' => 'required',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $category = ServiceCategory::findOrFail($validated['category_id']);
        $uniqueBookingId = 'BK-' . strtoupper(Str::random(6));

        $booking = Booking::create([
            'booking_id' => $uniqueBookingId,
            'customer_id' => Auth::id(),
            'provider_id' => null, // Left nullable for prototype assignment
            'category_id' => $category->id,
            'booking_date' => now()->toDateString(),
            'service_date' => $validated['service_date'],
            'service_time' => $validated['service_time'],
            'address' => $validated['address'],
            'status' => 'pending',
            'total_amount' => $category->estimated_price,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Log Activity
        ActivityLog::record(Auth::id(), 'Booking Created', "Submitted service booking #{$booking->booking_id} for {$category->name}");

        return redirect()->route('bookings.index')
            ->with('success', "Booking #{$booking->booking_id} submitted successfully!");
    }

    /**
     * List bookings for logged-in user.
     */
    public function index()
    {
        $user = Auth::user();
        $pendingApproval = false;

        if ($user->isCustomer()) {
            $bookings = Booking::with(['category', 'provider', 'payment', 'review'])
                ->where('customer_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->isProvider()) {
            if (!$user->is_approved) {
                // Unapproved providers see nothing and get an approval notice.
                $pendingApproval = true;
                $bookings = collect();
            } else {
                $bookings = Booking::with(['category', 'customer'])
                    ->where('provider_id', $user->id)
                    ->orWhereNull('provider_id')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } else {
            $bookings = Booking::with(['category', 'customer', 'provider'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('bookings.index', compact('bookings', 'pendingApproval'));
    }

    /**
     * Toggle booking status manually (Pending <-> Completed). Only Provider or Admin.
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::findOrFail($id);

        if ($user->isCustomer()) {
            abort(403, 'Customers cannot update service booking status.');
        }

        if ($user->isProvider() && !$user->is_approved) {
            abort(403, 'Your provider account is pending admin approval.');
        }

        if ($user->isProvider() && $booking->provider_id !== $user->id) {
            abort(403, 'You can only update the status of bookings assigned to you.');
        }

        $booking->status = $booking->status === 'pending' ? 'completed' : 'pending';
        $booking->save();

        ActivityLog::record(Auth::id(), 'Booking Status Updated', "Updated status of booking #{$booking->booking_id} to {$booking->status}");

        // Generate the invoice once the job is completed (idempotent — never duplicates).
        if ($booking->status === 'completed') {
            $payment = Payment::firstOrCreate(
                ['booking_id' => $booking->id],
                [
                    'invoice_number' => 'INV-' . strtoupper(Str::random(6)),
                    'amount' => $booking->total_amount ?? 0,
                    'status' => 'unpaid',
                ]
            );

            if ($payment->wasRecentlyCreated) {
                ActivityLog::record(Auth::id(), 'Invoice Generated', "Generated invoice {$payment->invoice_number} for booking #{$booking->booking_id}");
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Booking status updated to {$booking->status}!",
                'status' => $booking->status,
            ]);
        }

        return back()->with('success', "Booking status updated to {$booking->status}!");
    }

    /**
     * Accept an unassigned booking (Provider only).
     */
    public function acceptBooking(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->isProvider()) {
            abort(403, 'Only Service Providers can accept bookings.');
        }

        if (!$user->is_approved) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Your provider account is pending admin approval.'], 403);
            }
            return back()->with('error', 'Your provider account is pending admin approval. You cannot accept jobs yet.');
        }

        $booking = Booking::findOrFail($id);

        if ($booking->provider_id !== null) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Booking has already been accepted by another provider.'], 400);
            }
            return back()->with('error', 'Booking has already been accepted by another provider.');
        }

        $booking->provider_id = $user->id;
        $booking->save();

        ActivityLog::record($user->id, 'Booking Accepted', "Accepted service booking #{$booking->booking_id}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'You have successfully accepted the booking!',
                'booking_id' => $booking->id
            ]);
        }

        return back()->with('success', 'You have successfully accepted the booking!');
    }

    /**
     * Reject or release an assigned booking back to open pool (Provider only).
     */
    public function rejectBooking(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->isProvider()) {
            abort(403, 'Only Service Providers can reject/release bookings.');
        }

        $booking = Booking::findOrFail($id);

        if ($booking->provider_id !== $user->id) {
            abort(403, 'You can only release bookings assigned to you.');
        }

        if ($booking->isCompleted()) {
            return back()->with('error', 'Cannot reject a service booking that is already completed.');
        }

        $booking->provider_id = null;
        $booking->save();

        ActivityLog::record($user->id, 'Booking Rejected/Released', "Provider {$user->name} released booking #{$booking->booking_id} back to available pool.");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Service request released back to open pool.',
            ]);
        }

        return back()->with('success', 'Service request released back to open market.');
    }
}

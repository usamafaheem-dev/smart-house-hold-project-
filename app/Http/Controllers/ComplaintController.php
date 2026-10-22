<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{
    /**
     * Customer view: their complaints + bookings for the "related booking" dropdown.
     */
    public function index()
    {
        $complaints = Complaint::where('customer_id', Auth::id())
            ->with('booking.category')
            ->orderBy('created_at', 'desc')
            ->get();

        $bookings = Booking::with('category')
            ->where('customer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('complaints.index', compact('complaints', 'bookings'));
    }

    /**
     * Store a new complaint (AJAX).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'booking_id' => 'nullable|exists:bookings,id',
        ]);

        // If a booking is referenced, it must belong to this customer.
        if (!empty($validated['booking_id'])) {
            $booking = Booking::findOrFail($validated['booking_id']);
            if ($booking->customer_id !== Auth::id()) {
                abort(403, 'You can only reference your own bookings.');
            }
        }

        $complaint = Complaint::create([
            'complaint_id' => 'CMP-' . strtoupper(Str::random(6)),
            'customer_id' => Auth::id(),
            'booking_id' => $validated['booking_id'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'open',
        ]);

        ActivityLog::record(
            Auth::id(),
            'Complaint Filed',
            "Filed complaint {$complaint->complaint_id}: {$complaint->subject}"
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Complaint {$complaint->complaint_id} submitted successfully!",
                'complaint' => $complaint,
            ]);
        }

        return redirect()->route('complaints.index')
            ->with('success', "Complaint {$complaint->complaint_id} submitted successfully!");
    }

    /**
     * Admin view: all complaints across the system.
     */
    public function adminIndex()
    {
        $complaints = Complaint::with(['customer', 'booking.category'])
            ->orderByRaw("FIELD(status, 'open', 'resolved')")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.complaints', compact('complaints'));
    }

    /**
     * Admin reply + resolve a complaint.
     */
    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string|max:2000',
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->admin_reply = $validated['admin_reply'];
        $complaint->status = 'resolved';
        $complaint->resolved_at = now();
        $complaint->save();

        ActivityLog::record(
            Auth::id(),
            'Complaint Resolved',
            "Resolved complaint {$complaint->complaint_id}"
        );

        return back()->with('success', "Complaint {$complaint->complaint_id} has been resolved.");
    }
}

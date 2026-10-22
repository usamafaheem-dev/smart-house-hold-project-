<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    /**
     * Show the provider's availability & service-area form.
     */
    public function availability()
    {
        $user = Auth::user();
        $categories = ServiceCategory::orderBy('name')->get();
        return view('provider.availability', compact('user', 'categories'));
    }

    /**
     * Update the provider's availability, offered categories & service areas.
     */
    public function updateAvailability(Request $request)
    {
        $validated = $request->validate([
            'available_days' => 'nullable|array',
            'available_days.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'available_from' => 'nullable|date_format:H:i',
            'available_to' => 'nullable|date_format:H:i',
            'service_areas' => 'nullable|string|max:1000',
            'offered_categories' => 'nullable|array',
            'offered_categories.*' => 'exists:service_categories,id',
        ]);

        $user = Auth::user();
        $user->available_days = !empty($validated['available_days'])
            ? implode(',', $validated['available_days'])
            : null;
        $user->available_from = $validated['available_from'] ?? null;
        $user->available_to = $validated['available_to'] ?? null;
        $user->service_areas = $validated['service_areas'] ?? null;
        $user->offered_categories = !empty($validated['offered_categories'])
            ? implode(',', $validated['offered_categories'])
            : null;
        $user->save();

        ActivityLog::record($user->id, 'Availability Updated', 'Updated availability, offered service categories & service areas');

        return redirect()->route('provider.availability')
            ->with('success', 'Your profile, offered categories, and availability have been updated.');
    }

    /**
     * Show the provider's job history & earnings summary.
     */
    public function earnings()
    {
        $user = Auth::user();

        $completed = Booking::with(['category', 'customer', 'payment'])
            ->where('provider_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();

        $totalJobs = $completed->count();
        $grossEarnings = (float) $completed->sum('total_amount');
        $paidEarnings = (float) $completed
            ->filter(fn ($b) => $b->payment && $b->payment->isPaid())
            ->sum('total_amount');
        $pendingEarnings = $grossEarnings - $paidEarnings;

        $avgRating = $user->averageRating();
        $totalReviews = $user->totalReviews();
        $reviews = $user->reviewsReceived()
            ->with(['customer', 'booking.category'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('provider.earnings', compact(
            'completed',
            'totalJobs',
            'grossEarnings',
            'paidEarnings',
            'pendingEarnings',
            'avgRating',
            'totalReviews',
            'reviews'
        ));
    }
}

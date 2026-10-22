<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a customer's rating & review for a completed booking's provider.
     */
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::findOrFail($id);

        // Ownership: only the booking's customer may review it.
        if ($booking->customer_id !== Auth::id()) {
            return $this->fail($request, 'You can only review your own bookings.', 403);
        }

        // Must be a completed job with an assigned provider.
        if (!$booking->isCompleted()) {
            return $this->fail($request, 'You can only review completed services.', 400);
        }

        if (is_null($booking->provider_id)) {
            return $this->fail($request, 'This booking has no assigned provider to review.', 400);
        }

        // One review per booking.
        if ($booking->hasReview()) {
            return $this->fail($request, 'You have already reviewed this booking.', 400);
        }

        $review = Review::create([
            'booking_id' => $booking->id,
            'customer_id' => Auth::id(),
            'provider_id' => $booking->provider_id, // snapshot at review time
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        ActivityLog::record(
            Auth::id(),
            'Review Submitted',
            "Rated booking #{$booking->booking_id} {$review->rating}/5 stars"
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your review has been submitted.',
                'rating' => $review->rating,
            ]);
        }

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }

    /**
     * Uniform failure response for JSON or standard requests.
     */
    private function fail(Request $request, string $message, int $code)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $message], $code);
        }

        abort($code, $message);
    }
}

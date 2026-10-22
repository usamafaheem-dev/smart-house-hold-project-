<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display the logged-in customer's invoices & payment history.
     */
    public function index()
    {
        $payments = Payment::whereHas('booking', function ($q) {
                $q->where('customer_id', Auth::id());
            })
            ->with(['booking.category', 'booking.provider'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payments.index', compact('payments'));
    }

    /**
     * Show a single printable invoice (owner or admin only).
     */
    public function invoice($id)
    {
        $payment = Payment::with(['booking.category', 'booking.provider', 'booking.customer'])
            ->findOrFail($id);

        $user = Auth::user();
        if (!$user->isAdmin() && $payment->booking->customer_id !== $user->id) {
            abort(403, 'You are not authorized to view this invoice.');
        }

        return view('payments.invoice', compact('payment'));
    }

    /**
     * Process a mock payment (Cash / Card) against an unpaid invoice.
     */
    public function pay(Request $request, $id)
    {
        $validated = $request->validate([
            'method' => 'required|in:cash,card',
        ]);

        $payment = Payment::with('booking')->findOrFail($id);

        // Only the owning customer may pay their own invoice.
        if ($payment->booking->customer_id !== Auth::id()) {
            abort(403, 'You are not authorized to pay this invoice.');
        }

        if ($payment->isPaid()) {
            return back()->with('error', 'This invoice has already been paid.');
        }

        $payment->method = $validated['method'];
        $payment->status = 'paid';
        $payment->transaction_ref = 'TXN-' . strtoupper(Str::random(10));
        $payment->paid_at = now();
        $payment->save();

        ActivityLog::record(
            Auth::id(),
            'Payment Completed',
            "Paid invoice {$payment->invoice_number} (Rs. {$payment->amount}) via {$payment->method} for booking #{$payment->booking->booking_id}"
        );

        return redirect()->route('payments.invoice', $payment->id)
            ->with('success', "Payment successful! Invoice {$payment->invoice_number} is now marked as paid.");
    }
}

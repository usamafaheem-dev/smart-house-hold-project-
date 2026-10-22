@extends('layouts.app')

@section('title', 'My Payments & Invoices')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>My Payments & Invoices</h2>
        <p class="text-muted mb-0">Invoices are generated automatically once a service is completed.</p>
    </div>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Invoice #</th>
                    <th>Service</th>
                    <th>Provider</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td><span class="badge bg-dark text-uppercase font-monospace">{{ $payment->invoice_number }}</span></td>
                        <td>
                            <i class="fas {{ $payment->booking->category->icon ?? 'fa-tools' }} text-primary me-2"></i>
                            <strong class="text-dark">{{ $payment->booking->category->name ?? 'N/A' }}</strong>
                            <div class="small text-muted font-monospace">{{ $payment->booking->booking_id ?? '' }}</div>
                        </td>
                        <td>
                            @if($payment->booking->provider)
                                <span class="text-info fw-bold"><i class="fas fa-hard-hat me-1"></i>{{ $payment->booking->provider->name }}</span>
                            @else
                                <span class="text-muted fst-italic">—</span>
                            @endif
                        </td>
                        <td><span class="text-success fw-bold">Rs. {{ number_format($payment->amount, 2) }}</span></td>
                        <td>
                            @if($payment->isPaid())
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Paid</span>
                                <div class="small text-muted text-uppercase mt-1">{{ $payment->method }}</div>
                            @else
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Unpaid</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            @if($payment->isPaid() && $payment->paid_at)
                                {{ $payment->paid_at->format('M d, Y') }}
                            @else
                                {{ $payment->created_at->format('M d, Y') }}
                            @endif
                        </td>
                        <td class="text-end">
                            @if($payment->isPaid())
                                <a href="{{ route('payments.invoice', $payment->id) }}" class="btn btn-sm btn-outline-primary fw-bold">
                                    <i class="fas fa-receipt me-1"></i> View Invoice
                                </a>
                            @else
                                <a href="{{ route('payments.invoice', $payment->id) }}" class="btn btn-sm btn-primary fw-bold shadow-sm">
                                    <i class="fas fa-credit-card me-1"></i> Pay Now
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-file-invoice fa-3x mb-3 d-block text-secondary"></i>
                            No invoices yet. They appear here once your booked services are completed.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'My Earnings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-wallet me-2 text-primary"></i>Job History & Earnings</h2>
        <p class="text-muted mb-0">Your completed services, income, and customer ratings.</p>
    </div>
    <a href="{{ route('provider.availability') }}" class="btn btn-outline-primary fw-bold"><i class="fas fa-business-time me-1"></i> Availability</a>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 h-100 border-start border-primary border-4">
            <div class="text-muted small text-uppercase fw-bold">Completed Jobs</div>
            <div class="fs-4 fw-bold text-primary">{{ $totalJobs }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 h-100 border-start border-success border-4">
            <div class="text-muted small text-uppercase fw-bold">Earnings Received</div>
            <div class="fs-4 fw-bold text-success">Rs. {{ number_format($paidEarnings, 0) }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 h-100 border-start border-warning border-4">
            <div class="text-muted small text-uppercase fw-bold">Pending Payment</div>
            <div class="fs-4 fw-bold text-warning">Rs. {{ number_format($pendingEarnings, 0) }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 h-100 border-start border-info border-4">
            <div class="text-muted small text-uppercase fw-bold">Avg. Rating</div>
            <div class="fs-4 fw-bold text-info">
                {{ $avgRating > 0 ? $avgRating : '—' }}
                @if($avgRating > 0)<i class="fas fa-star text-warning fs-6"></i>@endif
            </div>
            <div class="small text-muted">{{ $totalReviews }} review(s)</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Completed Jobs -->
    <div class="col-lg-7">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-clipboard-check text-success me-2"></i>Completed Jobs</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Booking</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($completed as $booking)
                            <tr>
                                <td>
                                    <span class="font-monospace text-primary">{{ $booking->booking_id }}</span>
                                    <div class="small text-muted"><i class="fas {{ $booking->category->icon ?? 'fa-tools' }} me-1"></i>{{ $booking->category->name ?? '' }}</div>
                                </td>
                                <td class="small">{{ $booking->customer->name ?? 'N/A' }}</td>
                                <td class="text-success fw-bold">Rs. {{ number_format($booking->total_amount, 0) }}</td>
                                <td>
                                    @if($booking->payment && $booking->payment->isPaid())
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Unpaid</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-clipboard-list fa-2x mb-2 d-block text-secondary"></i>
                                    No completed jobs yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Reviews -->
    <div class="col-lg-5">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-star text-warning me-2"></i>Customer Reviews</h5>
            @forelse($reviews as $review)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-dark">{{ $review->customer->name ?? 'Customer' }}</strong>
                        <span>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                            @endfor
                        </span>
                    </div>
                    <div class="small text-muted">{{ $review->comment ?: 'No comment provided.' }}</div>
                    <div class="small text-muted mt-1">
                        <i class="fas {{ $review->booking->category->icon ?? 'fa-tools' }} me-1"></i>{{ $review->booking->category->name ?? '' }}
                        · {{ $review->created_at->format('M d, Y') }}
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-comment-slash fa-2x mb-2 d-block text-secondary"></i>
                    No reviews yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

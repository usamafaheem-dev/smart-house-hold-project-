@extends('layouts.app')

@section('title', 'Admin — Payments & Analytics')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-chart-line me-2 text-danger"></i>Payments & Analytics</h2>
        <p class="text-muted mb-0">Revenue, bookings, and provider insights at a glance.</p>
    </div>
    <a href="{{ route('admin.payments') }}" class="btn btn-outline-primary fw-bold"><i class="fas fa-list me-1"></i> Full Ledger</a>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 h-100 border-start border-success border-4">
            <div class="text-muted small text-uppercase fw-bold">Revenue (Paid)</div>
            <div class="fs-4 fw-bold text-success">Rs. {{ number_format($totalRevenue, 0) }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 h-100 border-start border-warning border-4">
            <div class="text-muted small text-uppercase fw-bold">Outstanding</div>
            <div class="fs-4 fw-bold text-warning">Rs. {{ number_format($pendingRevenue, 0) }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 h-100 border-start border-primary border-4">
            <div class="text-muted small text-uppercase fw-bold">Total Bookings</div>
            <div class="fs-4 fw-bold text-primary">{{ $totalBookings }}</div>
            <div class="small text-muted">{{ $completedBookings }} completed · {{ $pendingBookings }} pending</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom p-3 h-100 border-start border-info border-4">
            <div class="text-muted small text-uppercase fw-bold">Providers</div>
            <div class="fs-4 fw-bold text-info">{{ $totalProviders }}</div>
            <div class="small text-muted">{{ $pendingProviders }} pending approval</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Revenue by Category (doughnut) -->
    <div class="col-lg-5">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-chart-pie text-primary me-2"></i>Revenue by Category</h5>
            @if($revenueByCategory->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fas fa-chart-pie fa-3x mb-3 d-block text-secondary"></i>
                    No paid revenue yet.
                </div>
            @else
                <canvas id="categoryChart" height="240"></canvas>
            @endif
        </div>
    </div>

    <!-- Payments over time (line) -->
    <div class="col-lg-7">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-chart-line text-success me-2"></i>Paid Revenue — Last 7 Days</h5>
            <canvas id="dailyChart" height="240"></canvas>
        </div>
    </div>
</div>

<!-- Recent Payments -->
<div class="card card-custom p-4">
    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-receipt text-primary me-2"></i>Recent Payments</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Paid At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPayments as $payment)
                    <tr>
                        <td><span class="badge bg-dark font-monospace">{{ $payment->invoice_number }}</span></td>
                        <td class="fw-semibold text-dark">{{ $payment->booking->customer->name ?? 'N/A' }}</td>
                        <td>{{ $payment->booking->category->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-light text-dark border text-uppercase">{{ $payment->method }}</span></td>
                        <td class="text-success fw-bold">Rs. {{ number_format($payment->amount, 2) }}</td>
                        <td class="small text-muted">{{ $payment->paid_at ? $payment->paid_at->format('M d, Y h:i A') : '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No payments recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
$(document).ready(function() {
    const palette = ['#4f46e5', '#7c3aed', '#3b82f6', '#2dd4bf', '#10b981', '#f59e0b', '#ef4444', '#ec4899'];

    @if(!$revenueByCategory->isEmpty())
    const catLabels = @json($revenueByCategory->keys());
    const catData = @json($revenueByCategory->values()).map(Number);
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{ data: catData, backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            cutout: '60%'
        }
    });
    @endif

    const dayLabels = @json($paymentsByDayLabels);
    const dayData = @json($paymentsByDayData).map(Number);
    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels: dayLabels,
            datasets: [{
                label: 'Paid Revenue (Rs.)',
                data: dayData,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.12)',
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#10b981',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rs. ' + v } } }
        }
    });
});
</script>
@endpush

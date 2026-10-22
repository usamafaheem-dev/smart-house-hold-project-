@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom bg-danger text-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold mb-1"><i class="fas fa-user-shield me-2"></i>Welcome Admin, {{ $user->name }}!</h2>
                    <p class="mb-0 text-white-50">System Administration & Monitoring Dashboard.</p>
                </div>
                <span class="badge bg-light text-danger px-3 py-2 fs-6 fw-semibold text-uppercase">Role: Admin</span>
            </div>
        </div>
    </div>
</div>

<!-- Alert Banner if action required -->
@if(!empty($pendingProvidersCount) && $pendingProvidersCount > 0)
    <div class="alert alert-warning border-0 shadow-sm d-flex justify-content-between align-items-center mb-4">
        <div>
            <i class="fas fa-user-clock me-2"></i>
            <strong>{{ $pendingProvidersCount }} Service Provider(s)</strong> are waiting for account approval!
        </div>
        <a href="{{ route('admin.users', ['role' => 'provider']) }}" class="btn btn-sm btn-warning fw-bold text-dark">
            <i class="fas fa-check-circle me-1"></i> Review Providers
        </a>
    </div>
@endif

@if(!empty($openComplaintsCount) && $openComplaintsCount > 0)
    <div class="alert alert-info border-0 shadow-sm d-flex justify-content-between align-items-center mb-4">
        <div>
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>{{ $openComplaintsCount }} Customer Complaint(s)</strong> are currently open and waiting for resolution.
        </div>
        <a href="{{ route('admin.complaints') }}" class="btn btn-sm btn-info fw-bold text-dark">
            <i class="fas fa-reply me-1"></i> Resolve Complaints
        </a>
    </div>
@endif

<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-custom p-4 border-start border-primary border-4 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-users me-2 text-primary"></i>User Accounts</h5>
                @if(!empty($pendingProvidersCount) && $pendingProvidersCount > 0)
                    <span class="badge bg-warning text-dark">{{ $pendingProvidersCount }} pending</span>
                @endif
            </div>
            <p class="text-muted small">Monitor customers, providers, and approval status.</p>
            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary btn-sm fw-bold mt-auto"><i class="fas fa-arrow-right me-1"></i> View Users</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom p-4 border-start border-warning border-4 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-clipboard-list me-2 text-warning"></i>All Bookings</h5>
                @if(!empty($pendingBookingsCount) && $pendingBookingsCount > 0)
                    <span class="badge bg-warning text-dark">{{ $pendingBookingsCount }} pending</span>
                @endif
            </div>
            <p class="text-muted small">Review, oversee, and assign service bookings to providers.</p>
            <a href="{{ route('admin.bookings') }}" class="btn btn-outline-warning text-dark btn-sm fw-bold mt-auto"><i class="fas fa-arrow-right me-1"></i> View Bookings</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom p-4 border-start border-info border-4 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-tasks me-2 text-info"></i>Household Tasks</h5>
                @if(!empty($totalTasksCount))
                    <span class="badge bg-info text-dark">{{ $totalTasksCount }} tasks</span>
                @endif
            </div>
            <p class="text-muted small">Monitor customer personal chores and routine task records.</p>
            <a href="{{ route('admin.tasks') }}" class="btn btn-outline-info text-dark btn-sm fw-bold mt-auto"><i class="fas fa-arrow-right me-1"></i> Monitor Tasks</a>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-md-3 col-sm-6">
        <div class="card card-custom p-4 border-start border-success border-4 shadow-sm h-100">
            <h5 class="fw-bold text-dark"><i class="fas fa-chart-line me-2 text-success"></i>Analytics</h5>
            <p class="text-muted small">Revenue insights & booking trends.</p>
            <a href="{{ route('admin.analytics') }}" class="btn btn-outline-success btn-sm fw-bold"><i class="fas fa-arrow-right me-1"></i> View Analytics</a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card card-custom p-4 border-start border-secondary border-4 shadow-sm h-100">
            <h5 class="fw-bold text-dark"><i class="fas fa-tags me-2 text-secondary"></i>Categories</h5>
            <p class="text-muted small">Dynamic CRUD for home services.</p>
            <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary btn-sm fw-bold"><i class="fas fa-arrow-right me-1"></i> Manage</a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card card-custom p-4 border-start border-danger border-4 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-headset me-2 text-danger"></i>Complaints</h5>
                @if(!empty($openComplaintsCount) && $openComplaintsCount > 0)
                    <span class="badge bg-danger">{{ $openComplaintsCount }} open</span>
                @endif
            </div>
            <p class="text-muted small">Customer complaints and resolution tickets.</p>
            <a href="{{ route('admin.complaints') }}" class="btn btn-outline-danger btn-sm fw-bold"><i class="fas fa-arrow-right me-1"></i> View Complaints</a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card card-custom p-4 border-start border-dark border-4 shadow-sm h-100">
            <h5 class="fw-bold text-dark"><i class="fas fa-history me-2 text-dark"></i>Audit Logs</h5>
            <p class="text-muted small">Real-time system activity logs.</p>
            <a href="{{ route('admin.logs') }}" class="btn btn-outline-dark btn-sm fw-bold"><i class="fas fa-arrow-right me-1"></i> Audit Logs</a>
        </div>
    </div>
</div>
@endsection

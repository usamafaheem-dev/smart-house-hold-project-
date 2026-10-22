@extends('layouts.app')

@section('title', 'Provider Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom bg-info text-dark p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold mb-1"><i class="fas fa-tools me-2"></i>Welcome, {{ $user->name }}!</h2>
                    <p class="mb-0 text-dark">Service Provider Dashboard — View assigned home service requests.</p>
                </div>
                <span class="badge bg-dark text-white px-3 py-2 fs-6 fw-semibold text-uppercase">Role: Service Provider</span>
            </div>
        </div>
    </div>
</div>

@if(!empty($pendingApproval) && $pendingApproval)
    <div class="card card-custom p-5 text-center border-warning border-2 mb-4">
        <div class="mb-3"><i class="fas fa-user-clock fa-4x text-warning"></i></div>
        <h3 class="fw-bold text-dark">Account Pending Approval</h3>
        <p class="text-muted mb-4" style="max-width: 560px; margin: 0 auto;">
            Thank you for registering as a Service Provider. An administrator must review and approve your
            account before you can view or accept service requests. Meanwhile, you can set up your availability
            and service areas so you're ready to go the moment you're approved.
        </p>
        <div>
            <a href="{{ route('provider.availability') }}" class="btn btn-primary fw-bold shadow-sm">
                <i class="fas fa-business-time me-1"></i> Set Up Availability
            </a>
        </div>
    </div>
@else
    <!-- Quick Links -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="{{ route('bookings.index') }}" class="text-decoration-none">
                <div class="card card-custom p-3 h-100 border-start border-primary border-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clipboard-list fa-2x text-primary me-3"></i>
                        <div>
                            <div class="fw-bold text-dark">Service Requests</div>
                            <small class="text-muted">Accept & complete jobs</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('provider.availability') }}" class="text-decoration-none">
                <div class="card card-custom p-3 h-100 border-start border-info border-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-business-time fa-2x text-info me-3"></i>
                        <div>
                            <div class="fw-bold text-dark">Availability</div>
                            <small class="text-muted">Days, hours & areas</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('provider.earnings') }}" class="text-decoration-none">
                <div class="card card-custom p-3 h-100 border-start border-success border-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-wallet fa-2x text-success me-3"></i>
                        <div>
                            <div class="fw-bold text-dark">Earnings</div>
                            <small class="text-muted">Income & reviews</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4 align-items-stretch">
        <!-- Left Image Column -->
        <div class="col-md-3">
            <div class="card card-custom h-100 border-0 shadow-sm overflow-hidden p-0">
                <img src="{{ asset('images/carousel_1.jpg') }}" alt="Provider Image" class="img-fluid w-100 h-100" style="object-fit: cover; min-height: 350px;">
            </div>
        </div>

        <!-- Right Tasks Column -->
        <div class="col-md-9">
            <div class="card card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-clipboard-list text-primary me-2"></i>Assigned Service Requests</h4>
                    <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-primary fw-bold">Manage Bookings</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th># ID</th>
                                <th>Service</th>
                                <th>Customer</th>
                                <th>Date & Time</th>
                                <th>Action / Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td class="fw-semibold text-primary">{{ $booking->booking_id }}</td>
                                    <td class="fw-bold text-dark">{{ $booking->category->name }}</td>
                                    <td class="text-muted">{{ $booking->customer->name }}</td>
                                    <td>
                                        <div><i class="far fa-calendar-alt text-primary me-1"></i>{{ \Carbon\Carbon::parse($booking->service_date)->format('M d, Y') }}</div>
                                        <small class="text-muted"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($booking->service_time)->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if(is_null($booking->provider_id))
                                            <form action="{{ route('bookings.accept', $booking->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm">
                                                    <i class="fas fa-handshake me-1"></i> Accept Task
                                                </button>
                                            </form>
                                        @else
                                            @if($booking->status === 'pending')
                                                <form action="{{ route('bookings.toggle-status', $booking->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success fw-bold shadow-sm">
                                                        <i class="fas fa-check-circle me-1"></i> Mark Complete
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-success px-3 py-2 fs-6">
                                                    <i class="fas fa-check-double me-1"></i> Completed
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-clipboard-list fa-3x mb-3 d-block text-secondary"></i>
                                        No service tasks assigned or available yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

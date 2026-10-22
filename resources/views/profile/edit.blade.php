@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-id-card me-2 text-primary"></i>My Profile & Settings</h2>
        <p class="text-muted mb-0">Manage your personal information, contact details, and account security.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary fw-bold">
        <i class="fas fa-arrow-left me-1"></i> Dashboard
    </a>
</div>

<div class="row g-4">
    <!-- Left Column: User Summary Card -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 text-center mb-4">
            <div class="mb-3">
                <div class="avatar-placeholder d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow" 
                     style="width: 100px; height: 100px; font-size: 2.5rem; background: var(--primary-gradient);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
            <p class="text-muted mb-2 small">{{ $user->email }}</p>
            <div>
                <span class="badge px-3 py-2 rounded-pill fw-bold 
                    {{ $user->isAdmin() ? 'bg-danger' : ($user->isProvider() ? 'bg-info text-dark' : 'bg-primary') }}">
                    <i class="fas {{ $user->isAdmin() ? 'fa-user-shield' : ($user->isProvider() ? 'fa-hard-hat' : 'fa-user') }} me-1"></i>
                    ROLE: {{ strtoupper($user->role) }}
                </span>
            </div>

            <hr class="my-4">

            <div class="text-start">
                <div class="mb-3">
                    <small class="text-muted d-block"><i class="fas fa-phone me-1 text-primary"></i> Contact Number</small>
                    <span class="fw-semibold text-dark">{{ $user->phone ?: 'Not provided' }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block"><i class="fas fa-map-marker-alt me-1 text-primary"></i> Address</small>
                    <span class="fw-semibold text-dark">{{ $user->address ?: 'Not provided' }}</span>
                </div>
                <div>
                    <small class="text-muted d-block"><i class="far fa-clock me-1 text-primary"></i> Member Since</small>
                    <span class="fw-semibold text-dark">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            @if($user->isProvider())
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Account Vetting:</small>
                        @if($user->is_approved)
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Approved</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted">Rating:</small>
                        <span class="text-warning fw-bold"><i class="fas fa-star"></i> {{ $user->averageRating() }} ({{ $user->totalReviews() }})</span>
                    </div>
                    <a href="{{ route('provider.availability') }}" class="btn btn-sm btn-outline-primary w-100 fw-bold">
                        <i class="fas fa-business-time me-1"></i> Manage Availability
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Edit Details & Password -->
    <div class="col-lg-8">
        <!-- Edit Profile Form -->
        <div class="card card-custom p-4 p-md-5 mb-4">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">
                <i class="fas fa-user-edit me-2 text-primary"></i>Edit Personal Information
            </h5>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control bg-light" id="email" value="{{ $user->email }}" readonly disabled>
                        <small class="text-muted">Email address cannot be changed.</small>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" 
                               value="{{ old('phone', $user->phone) }}" placeholder="e.g. 0300-1234567">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="address" class="form-label fw-semibold">Current Address / City</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" 
                               value="{{ old('address', $user->address) }}" placeholder="e.g. Model Town, Lahore">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="card card-custom p-4 p-md-5">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">
                <i class="fas fa-lock me-2 text-primary"></i>Change Password
            </h5>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="current_password" class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                           id="current_password" name="current_password" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold">New Password (min 8 chars) <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-outline-primary fw-bold px-4">
                        <i class="fas fa-key me-1"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

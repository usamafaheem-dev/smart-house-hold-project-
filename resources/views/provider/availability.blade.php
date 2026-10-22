    @extends('layouts.app')

@section('title', 'My Availability & Services')

@php
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $selectedDays = $user->available_days ? explode(',', $user->available_days) : [];
    $selectedCategories = $user->offeredCategoryIds();
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-business-time me-2 text-primary"></i>Service Profile, Skills & Availability</h2>
        <p class="text-muted mb-0">Select the categories you offer, working days, hours, and geographical coverage areas.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary fw-bold"><i class="fas fa-arrow-left me-1"></i> Dashboard</a>
</div>

<div class="card card-custom p-4 p-md-5" style="max-width: 850px;">
    <form method="POST" action="{{ route('provider.availability.update') }}">
        @csrf

        <!-- Offered Categories Selection -->
        <div class="mb-4 pb-3 border-bottom">
            <label class="form-label fw-bold text-dark fs-5 mb-1">
                <i class="fas fa-tags me-1 text-primary"></i> Offered Service Categories (Your Specialties)
            </label>
            <p class="text-muted small mb-3">Check all categories you are qualified to perform. You can update this anytime.</p>
            
            <div class="row g-2">
                @foreach($categories as $cat)
                    <div class="col-md-6 col-lg-4">
                        <div class="form-check p-3 border rounded-3 bg-light h-100 d-flex align-items-center">
                            <input class="form-check-input me-2" type="checkbox" name="offered_categories[]" 
                                   value="{{ $cat->id }}" id="cat-{{ $cat->id }}" 
                                   {{ in_array($cat->id, $selectedCategories) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark cursor-pointer d-flex align-items-center" for="cat-{{ $cat->id }}">
                                <i class="fas {{ $cat->icon ?? 'fa-tools' }} text-primary me-2"></i>
                                <span>{{ $cat->name }}</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            <small class="text-muted mt-2 d-block">If none are selected, all categories will be shown to you.</small>
        </div>

        <!-- Available Days -->
        <div class="mb-4">
            <label class="form-label fw-bold text-dark"><i class="far fa-calendar-alt me-1 text-primary"></i> Available Working Days</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach($days as $day)
                    <input type="checkbox" class="btn-check" name="available_days[]" value="{{ $day }}" id="day-{{ $day }}" {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="day-{{ $day }}">{{ $day }}</label>
                @endforeach
            </div>
        </div>

        <!-- Working Hours -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="available_from" class="form-label fw-bold text-dark"><i class="far fa-clock me-1 text-primary"></i> Available From</label>
                <input type="time" class="form-control" id="available_from" name="available_from"
                       value="{{ $user->available_from ? \Carbon\Carbon::parse($user->available_from)->format('H:i') : '' }}">
            </div>
            <div class="col-md-6">
                <label for="available_to" class="form-label fw-bold text-dark"><i class="far fa-clock me-1 text-primary"></i> Available To</label>
                <input type="time" class="form-control" id="available_to" name="available_to"
                       value="{{ $user->available_to ? \Carbon\Carbon::parse($user->available_to)->format('H:i') : '' }}">
            </div>
        </div>

        <!-- Service Locations / Areas -->
        <div class="mb-4">
            <label for="service_areas" class="form-label fw-bold text-dark"><i class="fas fa-map-marker-alt me-1 text-primary"></i> Service Locations / Areas</label>
            <textarea class="form-control" id="service_areas" name="service_areas" rows="3" placeholder="e.g. Model Town, Johar Town, DHA, Gulberg — Lahore">{{ $user->service_areas }}</textarea>
            <small class="text-muted">List the neighbourhoods, sectors, or cities you cover, separated by commas.</small>
        </div>

        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
            <i class="fas fa-save me-1"></i> Save Profile & Availability
        </button>
    </form>
</div>
@endsection

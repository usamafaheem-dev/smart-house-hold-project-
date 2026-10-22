@extends('layouts.app')

@section('title', 'Book ' . $category->name . ' Service')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card card-custom p-4">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="fas {{ $category->icon }} fa-lg"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0">Book {{ $category->name }}</h3>
                    <p class="text-muted small mb-0">Estimated Price: <strong class="text-success">Rs. {{ number_format($category->estimated_price, 2) }}</strong></p>
                </div>
            </div>

            <form method="POST" action="{{ route('bookings.store') }}">
                @csrf
                <input type="hidden" name="category_id" value="{{ $category->id }}">

                <!-- Service Date & Time -->
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label for="service_date" class="form-label fw-semibold">Preferred Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('service_date') is-invalid @enderror" id="service_date" name="service_date" min="{{ date('Y-m-d') }}" value="{{ old('service_date', date('Y-m-d')) }}" required>
                        @error('service_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6">
                        <label for="service_time" class="form-label fw-semibold">Preferred Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('service_time') is-invalid @enderror" id="service_time" name="service_time" value="{{ old('service_time', '11:00') }}" required>
                        @error('service_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Service Address -->
                <div class="mb-3">
                    <label for="address" class="form-label fw-semibold">Service Address <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Enter full address for service delivery..." required>{{ old('address', $user->address) }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Additional Instructions -->
                <div class="mb-4">
                    <label for="notes" class="form-label fw-semibold">Special Instructions (Optional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="e.g. Please bring extra ladder or specific tools...">{{ old('notes') }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('bookings.browse') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Services
                    </a>
                    <button type="submit" class="btn btn-success fw-bold px-4 py-2">
                        <i class="fas fa-check-circle me-1"></i> Confirm Booking Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

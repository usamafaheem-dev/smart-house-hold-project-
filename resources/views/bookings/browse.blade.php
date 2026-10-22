@extends('layouts.app')

@section('title', 'Browse Home Services')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-concierge-bell me-2 text-primary"></i>Browse Home Services</h2>
        <p class="text-muted mb-0">Select a category below to book professional home services.</p>
    </div>
    <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary fw-bold">
        <i class="fas fa-list me-1"></i> My Bookings
    </a>
</div>

<div class="row g-4">
    @foreach($categories as $category)
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom h-100 p-4 text-center border-top border-primary border-4 shadow-sm">
                <div class="bg-light text-primary rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="fas {{ $category->icon }} fa-2x"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">{{ $category->name }}</h4>
                <p class="text-muted small mb-3">{{ $category->description }}</p>
                <div class="bg-light p-2 rounded mb-3">
                    <span class="text-muted small d-block">Starting From</span>
                    <strong class="text-success fs-5">Rs. {{ number_format($category->estimated_price, 2) }}</strong>
                </div>
                <a href="{{ route('bookings.create', $category->id) }}" class="btn btn-primary w-100 fw-bold py-2">
                    <i class="fas fa-calendar-plus me-1"></i> Book Now
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection

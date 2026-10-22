@extends('layouts.app')

@section('title', 'User Registration')

@section('full-width-content')
<div class="row g-0" style="height: 100vh; overflow: hidden;">
    
    <!-- Left Side: Registration Form -->
    <div class="col-md-6 bg-white position-relative d-flex align-items-center justify-content-center animate-left" style="height: 100vh; overflow-y: auto;">
        
        <!-- Back to Home Button -->
        <a href="{{ url('/') }}" class="btn btn-primary btn-sm position-fixed top-0 start-0 m-3 shadow-sm fw-bold rounded-pill" style="z-index: 100; width: max-content;">
            <i class="fas fa-arrow-left me-1"></i> Back to Home
        </a>

        <div class="w-100 p-4 mx-auto animate-up" style="max-width: 480px; animation-delay: 0.2s;">
            <div class="mb-4 d-flex align-items-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3 shadow-sm" style="width: 45px; height: 45px; background: var(--primary-gradient); flex-shrink: 0;">
                    <i class="fas fa-user-plus fs-5 text-white"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0 fs-4">Create Your Account</h3>
                    <p class="text-muted small mb-0">Join the smartest household platform.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf

                <!-- Role Selection -->
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small text-uppercase tracking-wide mb-1">Select Role <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="role" id="role_customer" value="customer" {{ old('role', 'customer') == 'customer' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary w-100 py-1 rounded-3 shadow-sm transition-all" for="role_customer">
                                <i class="fas fa-user"></i> <span class="fw-bold small d-block">Customer</span>
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="role" id="role_provider" value="provider" {{ old('role') == 'provider' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary w-100 py-1 rounded-3 shadow-sm transition-all" for="role_provider">
                                <i class="fas fa-tools"></i> <span class="fw-bold small d-block">Provider</span>
                            </label>
                        </div>
                    </div>
                    <div class="invalid-feedback d-block" id="role_error"></div>
                </div>

                <div class="row g-2">
                    <!-- Full Name -->
                    <div class="col-md-6 mb-2">
                        <label for="name" class="form-label fw-semibold text-muted small mb-1">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light border-0 shadow-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
                        <div class="invalid-feedback" id="name_error">@error('name') {{ $message }} @enderror</div>
                    </div>

                    <!-- Phone Number -->
                    <div class="col-md-6 mb-2">
                        <label for="phone" class="form-label fw-semibold text-muted small mb-1">Phone</label>
                        <input type="text" class="form-control bg-light border-0 shadow-sm @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="0300-1234567">
                    </div>
                </div>

                <!-- Email Address -->
                <div class="mb-2">
                    <label for="email" class="form-label fw-semibold text-muted small mb-1">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control bg-light border-0 shadow-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="user@example.com" required>
                    <div class="invalid-feedback" id="email_error">@error('email') {{ $message }} @enderror</div>
                </div>

                <!-- Address -->
                <div class="mb-2">
                    <label for="address" class="form-label fw-semibold text-muted small mb-1">Address</label>
                    <textarea class="form-control bg-light border-0 shadow-sm @error('address') is-invalid @enderror" id="address" name="address" rows="1" placeholder="House #, Street, City">{{ old('address') }}</textarea>
                </div>

                <!-- Password & Confirm Password Row -->
                <div class="row">
                    <!-- Password -->
                    <div class="col-sm-6 mb-3">
                        <label for="password" class="form-label fw-semibold text-muted small mb-1">Password</label>
                        <div class="input-group shadow-sm rounded">
                            <input type="password" class="form-control bg-light border-0 @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                            <button class="btn btn-light bg-light border-0 text-muted toggle-password" type="button" data-target="password">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1" id="password_error">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Confirm Password -->
                    <div class="col-sm-6 mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold text-muted small mb-1">Confirm Password</label>
                        <div class="input-group shadow-sm rounded">
                            <input type="password" class="form-control bg-light border-0" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                            <button class="btn btn-light bg-light border-0 text-muted toggle-password" type="button" data-target="password_confirmation">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mb-3 fw-bold shadow-sm rounded-pill transition-all hover-scale">
                    Create Account
                </button>

                <div class="text-center small">
                    <span class="text-muted">Already have an account?</span>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #4f46e5;">Login here</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side: Image (Hidden on mobile) -->
    <div class="col-md-6 d-none d-md-block position-relative order-1 order-md-2 animate-right" style="height: 100vh; overflow: hidden;">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary opacity-25" style="z-index: 1;"></div>
        <img src="{{ asset('images/signup.jpg') }}" alt="Join our Professionals" class="w-100 h-100 d-block" style="object-fit: cover;">
        <div class="position-absolute bottom-0 start-0 p-5 w-100 text-white" style="z-index: 2; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
            <h2 class="fw-bold mb-2">Join the <span style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Network</span></h2>
            <p class="fs-5 opacity-75 mb-0">Connect with reliable service providers or find new customers.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (input && input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else if (input) {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Add loading state to form submission
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', function () {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
                }
            });
        }
    });
</script>
@endsection

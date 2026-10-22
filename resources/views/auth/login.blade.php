@extends('layouts.app')

@section('title', 'User Login')

@section('full-width-content')
<div class="row g-0 position-relative" style="height: 100vh; overflow: hidden;">
    
    <!-- Back to Home Button (Fixed at Top Left) -->
    <a href="{{ url('/') }}" class="btn btn-primary btn-sm position-fixed top-0 start-0 m-3 shadow-sm fw-bold rounded-pill" style="z-index: 100; width: max-content;">
        <i class="fas fa-arrow-left me-1"></i> Back to Home
    </a>

    <!-- Left Side: Image (Hidden on mobile) -->
    <div class="col-md-6 d-none d-md-block position-relative animate-left" style="height: 100vh; overflow: hidden;">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary opacity-25" style="z-index: 1;"></div>
        <img src="{{ asset('images/login.jpg') }}" alt="Smart Home Services" class="w-100 h-100 d-block" style="object-fit: cover;">
        <div class="position-absolute bottom-0 start-0 p-5 w-100 text-white" style="z-index: 2; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
            <h2 class="fw-bold mb-2">Welcome <span style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Back!</span></h2>
            <p class="fs-5 opacity-75 mb-0">Sign in to manage your household services effortlessly.</p>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="col-md-6 bg-white position-relative d-flex align-items-center justify-content-center animate-right" style="height: 100vh; overflow-y: auto;">
        
        <div class="w-100 p-4 mx-auto animate-up" style="max-width: 450px; animation-delay: 0.2s;">
            <div class="mb-4 d-flex align-items-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3 shadow-sm" style="width: 45px; height: 45px; background: var(--primary-gradient); flex-shrink: 0;">
                    <i class="fas fa-sign-in-alt fs-5 text-white"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0 fs-4">Welcome Back</h3>
                    <p class="text-muted small mb-0">Enter your details to access your account.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold text-muted small mb-1">Email Address</label>
                    <input type="email" class="form-control bg-light border-0 shadow-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="user@example.com" required autofocus>
                    <div class="invalid-feedback" id="email_error">@error('email') {{ $message }} @enderror</div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold text-muted small mb-1">Password</label>
                    <div class="input-group shadow-sm rounded">
                        <input type="password" class="form-control bg-light border-0 @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
                        <button class="btn btn-light bg-light border-0 text-muted" type="button" id="togglePassword">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1" id="password_error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted small" for="remember">
                            Remember me
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none small fw-semibold" style="color: #4f46e5;">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3 rounded-pill transition-all hover-scale">
                    Sign In
                </button>

                <div class="text-center small">
                    <span class="text-muted">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #4f46e5;">Register now</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePassword');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                const password = document.getElementById('password');
                const icon = this.querySelector('i');
                if (password.type === 'password') {
                    password.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    password.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }

        // Add loading state to form submission
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function () {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Signing In...';
                }
            });
        }
    });
</script>
@endsection

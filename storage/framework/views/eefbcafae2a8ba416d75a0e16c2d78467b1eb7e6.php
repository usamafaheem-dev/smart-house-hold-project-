<?php $__env->startSection('title', 'Welcome'); ?>

<?php $__env->startSection('full-width-content'); ?>
<div class="hero-section position-relative overflow-hidden" style="flex: 1; display: flex; align-items: center;">
    <!-- Background Carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade position-absolute top-0 start-0 w-100 h-100" data-bs-ride="carousel" data-bs-pause="false" style="z-index: -2;">
        <div class="carousel-inner w-100 h-100">
            <div class="carousel-item active w-100 h-100" data-bs-interval="5000">
                <img src="<?php echo e(asset('images/hero.jpg')); ?>" alt="Team" class="w-100 h-100" style="object-fit: cover;">
            </div>
            <div class="carousel-item w-100 h-100" data-bs-interval="5000">
                <img src="<?php echo e(asset('images/carousel_1.jpg')); ?>" alt="Electrician" class="w-100 h-100" style="object-fit: cover;">
            </div>
            <div class="carousel-item w-100 h-100" data-bs-interval="5000">
                <img src="<?php echo e(asset('images/carousel_2.jpg')); ?>" alt="Cleaner" class="w-100 h-100" style="object-fit: cover;">
            </div>
            <div class="carousel-item w-100 h-100" data-bs-interval="5000">
                <img src="<?php echo e(asset('images/carousel_3.jpg')); ?>" alt="Plumber" class="w-100 h-100" style="object-fit: cover;">
            </div>
        </div>
    </div>
    
    <!-- Gradient Overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to right, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.7) 40%, rgba(15, 23, 42, 0.2) 100%); z-index: -1;"></div>
    
    <div class="container position-relative z-index-1">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9 col-md-10 text-white">
                <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 fs-6" style="background: var(--primary-gradient) !important; border:none; color:white;">Premium Home Services</span>
                <h1 class="display-3 fw-bold mb-4 lh-sm text-white">
                    Smart <span style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Household</span><br>
                    <span style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Management</span> System
                </h1>
                <p class="lead mb-4 fs-4" style="color: #cbd5e1; font-weight: 300;">Experience the future of home maintenance. Book trusted plumbers, electricians, and cleaners with just a few taps. Professional service, guaranteed.</p>
                
                <div class="d-flex justify-content-center gap-2">
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary px-4 py-2 fs-5 shadow-lg">Get Started</a>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-light px-4 py-2 fs-5" style="border-width: 2px;">Login</a>
                </div>
                
                <div class="mt-4 d-flex justify-content-center align-items-center gap-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success fs-4 me-2"></i>
                        <span class="fs-5" style="color: #cbd5e1;">Verified Experts</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success fs-4 me-2"></i>
                        <span class="fs-5" style="color: #cbd5e1;">Instant Booking</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Optional extra content below hero can go here -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/welcome.blade.php ENDPATH**/ ?>
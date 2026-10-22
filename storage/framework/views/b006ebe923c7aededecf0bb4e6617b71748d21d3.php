<?php $__env->startSection('title', 'Browse Home Services'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-concierge-bell me-2 text-primary"></i>Browse Home Services</h2>
        <p class="text-muted mb-0">Select a category below to book professional home services.</p>
    </div>
    <a href="<?php echo e(route('bookings.index')); ?>" class="btn btn-outline-primary fw-bold">
        <i class="fas fa-list me-1"></i> My Bookings
    </a>
</div>

<div class="row g-4">
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom h-100 p-4 text-center border-top border-primary border-4 shadow-sm">
                <div class="bg-light text-primary rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="fas <?php echo e($category->icon); ?> fa-2x"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2"><?php echo e($category->name); ?></h4>
                <p class="text-muted small mb-3"><?php echo e($category->description); ?></p>
                <div class="bg-light p-2 rounded mb-3">
                    <span class="text-muted small d-block">Starting From</span>
                    <strong class="text-success fs-5">Rs. <?php echo e(number_format($category->estimated_price, 2)); ?></strong>
                </div>
                <a href="<?php echo e(route('bookings.create', $category->id)); ?>" class="btn btn-primary w-100 fw-bold py-2">
                    <i class="fas fa-calendar-plus me-1"></i> Book Now
                </a>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/bookings/browse.blade.php ENDPATH**/ ?>
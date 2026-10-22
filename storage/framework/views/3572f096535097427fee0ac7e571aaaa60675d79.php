<?php $__env->startSection('title', 'Book ' . $category->name . ' Service'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card card-custom p-4">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="fas <?php echo e($category->icon); ?> fa-lg"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0">Book <?php echo e($category->name); ?></h3>
                    <p class="text-muted small mb-0">Estimated Price: <strong class="text-success">Rs. <?php echo e(number_format($category->estimated_price, 2)); ?></strong></p>
                </div>
            </div>

            <form method="POST" action="<?php echo e(route('bookings.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="category_id" value="<?php echo e($category->id); ?>">

                <!-- Service Date & Time -->
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label for="service_date" class="form-label fw-semibold">Preferred Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control <?php $__errorArgs = ['service_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="service_date" name="service_date" min="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e(old('service_date', date('Y-m-d'))); ?>" required>
                        <?php $__errorArgs = ['service_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-6">
                        <label for="service_time" class="form-label fw-semibold">Preferred Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control <?php $__errorArgs = ['service_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="service_time" name="service_time" value="<?php echo e(old('service_time', '11:00')); ?>" required>
                        <?php $__errorArgs = ['service_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Service Address -->
                <div class="mb-3">
                    <label for="address" class="form-label fw-semibold">Service Address <span class="text-danger">*</span></label>
                    <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="address" name="address" rows="3" placeholder="Enter full address for service delivery..." required><?php echo e(old('address', $user->address)); ?></textarea>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Additional Instructions -->
                <div class="mb-4">
                    <label for="notes" class="form-label fw-semibold">Special Instructions (Optional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="e.g. Please bring extra ladder or specific tools..."><?php echo e(old('notes')); ?></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?php echo e(route('bookings.browse')); ?>" class="btn btn-outline-secondary">
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/bookings/create.blade.php ENDPATH**/ ?>
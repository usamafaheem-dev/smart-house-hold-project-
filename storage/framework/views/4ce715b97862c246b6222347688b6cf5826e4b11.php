<?php $__env->startSection('title', 'Customer Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom glass-banner p-4 p-md-5 rounded-4 border-0">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                <div class="text-center text-md-start mb-3 mb-md-0">
                    <h2 class="fw-bold mb-2 text-white display-6" style="letter-spacing: -0.03em;"><i class="fas fa-user-circle me-3 opacity-75"></i>Welcome, <?php echo e($user->name); ?>!</h2>
                    <p class="mb-0 text-white-50 fs-5">Your smart control center for household tasks and home service bookings.</p>
                </div>
                <div>
                    <span class="badge bg-white text-primary px-4 py-2 fs-6 fw-bold rounded-pill shadow-sm" style="letter-spacing: 0.5px;">ROLE: CUSTOMER</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Status Counter Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 bg-warning bg-opacity-10 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Reminders Due Today</span>
                    <h3 class="fw-bold text-warning mb-0 mt-1"><?php echo e($dueReminders->count()); ?></h3>
                </div>
                <div class="bg-warning text-dark rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-bell fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 bg-primary bg-opacity-10 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Active Bookings</span>
                    <h3 class="fw-bold text-primary mb-0 mt-1"><?php echo e($activeBookingsCount); ?></h3>
                </div>
                <div class="bg-primary text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-calendar-check fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 bg-info bg-opacity-10 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Upcoming Chores</span>
                    <h3 class="fw-bold text-info mb-0 mt-1"><?php echo e($upcomingTasksCount); ?></h3>
                </div>
                <div class="bg-info text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-clipboard-list fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 <?php echo e($unpaidInvoicesCount > 0 ? 'bg-danger bg-opacity-10' : 'bg-success bg-opacity-10'); ?> h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Unpaid Invoices</span>
                    <h3 class="fw-bold <?php echo e($unpaidInvoicesCount > 0 ? 'text-danger' : 'text-success'); ?> mb-0 mt-1"><?php echo e($unpaidInvoicesCount); ?></h3>
                </div>
                <div class="<?php echo e($unpaidInvoicesCount > 0 ? 'bg-danger' : 'bg-success'); ?> text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-file-invoice-dollar fs-5"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Reminder Notification Box -->
<?php if($dueReminders->count() > 0): ?>
    <div class="card card-custom border-warning border-2 p-4 mb-4 shadow-sm" style="background: linear-gradient(135deg, rgba(254, 243, 199, 0.5) 0%, rgba(255, 251, 235, 0.8) 100%);">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <span class="bg-warning text-dark p-2 rounded-circle me-3">
                    <i class="fas fa-bell fa-lg"></i>
                </span>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Scheduled Routine Chores Due Today!</h5>
                    <small class="text-muted">You have <?php echo e($dueReminders->count()); ?> routine household chore(s) requiring attention today.</small>
                </div>
            </div>
            <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-sm btn-warning fw-bold text-dark shadow-sm">
                <i class="fas fa-tasks me-1"></i> Open Task Manager
            </a>
        </div>

        <div class="list-group list-group-flush rounded-3 border">
            <?php $__currentLoopData = $dueReminders->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="list-group-item d-flex justify-content-between align-items-center bg-white p-3">
                    <div>
                        <div class="fw-bold text-dark"><?php echo e($task->task_title); ?></div>
                        <small class="text-muted">
                            <i class="far fa-clock me-1 text-primary"></i>Scheduled: <?php echo e(\Carbon\Carbon::parse($task->reminder_time)->format('h:i A')); ?>

                            <?php if($task->description): ?>
                                &bull; <?php echo e(Str::limit($task->description, 50)); ?>

                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <?php if($task->priority === 'high'): ?>
                            <span class="badge bg-danger">High Priority</span>
                        <?php elseif($task->priority === 'medium'): ?>
                            <span class="badge bg-warning text-dark">Medium Priority</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Low Priority</span>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('tasks.toggle-status', $task->id)); ?>" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-outline-success fw-bold" title="Mark Done">
                                <i class="fas fa-check me-1"></i> Done
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <!-- Household Tasks Quick Card -->
    <div class="col-md-6">
        <div class="card card-custom h-100 p-4 p-xl-5 glass-card-primary rounded-4 border-0">
            <div class="card-body p-0 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3 text-primary">
                            <i class="fas fa-list-check fa-2x"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-0" style="letter-spacing: -0.02em;">Household Tasks</h4>
                    </div>
                </div>
                <p class="text-muted fs-6 mb-4 flex-grow-1" style="line-height: 1.6;">Create, organize, set reminders, and track your daily household chores with seamless AJAX-powered interactions.</p>
                <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-primary btn-lg fw-bold rounded-pill px-4 shadow-sm align-self-start">
                    <i class="fas fa-arrow-right me-2"></i> Open Task Manager
                </a>
            </div>
        </div>
    </div>

    <!-- Home Service Bookings Quick Card -->
    <div class="col-md-6">
        <div class="card card-custom h-100 p-4 p-xl-5 glass-card-success rounded-4 border-0">
            <div class="card-body p-0 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3 text-success">
                            <i class="fas fa-concierge-bell fa-2x"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-0" style="letter-spacing: -0.02em;">Service Booking</h4>
                    </div>
                </div>
                <p class="text-muted fs-6 mb-4 flex-grow-1" style="line-height: 1.6;">Browse cleaning, plumbing, electrical, gardening, and appliance repair services provided by verified professionals.</p>
                <a href="<?php echo e(route('bookings.browse')); ?>" class="btn btn-success btn-lg fw-bold rounded-pill px-4 shadow-sm align-self-start text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                    <i class="fas fa-calendar-plus me-2"></i> Browse & Book
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/dashboard/customer.blade.php ENDPATH**/ ?>
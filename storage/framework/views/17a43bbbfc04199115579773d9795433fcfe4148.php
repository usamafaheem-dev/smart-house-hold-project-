<?php $__env->startSection('title', 'Household Tasks Monitoring'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-tasks me-2 text-primary"></i>Customer Household Tasks Monitoring</h2>
        <p class="text-muted mb-0">System-wide overview of all household tasks, reminders, and chores created by customers.</p>
    </div>
    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-outline-secondary fw-bold">
        <i class="fas fa-arrow-left me-1"></i> Dashboard
    </a>
</div>

<!-- Metrics Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 bg-primary bg-opacity-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Total Tasks</span>
                    <h3 class="fw-bold text-primary mb-0 mt-1"><?php echo e(number_format($totalTasks)); ?></h3>
                </div>
                <div class="bg-primary text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-clipboard-list fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 bg-warning bg-opacity-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Pending Tasks</span>
                    <h3 class="fw-bold text-warning mb-0 mt-1"><?php echo e(number_format($pendingTasks)); ?></h3>
                </div>
                <div class="bg-warning text-dark rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-clock fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 bg-success bg-opacity-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Completed Tasks</span>
                    <h3 class="fw-bold text-success mb-0 mt-1"><?php echo e(number_format($completedTasks)); ?></h3>
                </div>
                <div class="bg-success text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-check-circle fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 bg-danger bg-opacity-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">High Priority (Pending)</span>
                    <h3 class="fw-bold text-danger mb-0 mt-1"><?php echo e(number_format($highPriorityTasks)); ?></h3>
                </div>
                <div class="bg-danger text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-exclamation-triangle fs-5"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="card card-custom p-3 mb-4">
    <form method="GET" action="<?php echo e(route('admin.tasks')); ?>" class="row g-2 align-items-center">
        <div class="col-auto">
            <label class="fw-semibold text-muted small"><i class="fas fa-filter me-1"></i>Filter by Status:</label>
        </div>
        <div class="col-auto">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" <?php echo e($statusFilter === 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="completed" <?php echo e($statusFilter === 'completed' ? 'selected' : ''); ?>>Completed</option>
            </select>
        </div>

        <div class="col-auto ms-md-3">
            <label class="fw-semibold text-muted small"><i class="fas fa-flag me-1"></i>Priority:</label>
        </div>
        <div class="col-auto">
            <select name="priority" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Priorities</option>
                <option value="high" <?php echo e($priorityFilter === 'high' ? 'selected' : ''); ?>>High</option>
                <option value="medium" <?php echo e($priorityFilter === 'medium' ? 'selected' : ''); ?>>Medium</option>
                <option value="low" <?php echo e($priorityFilter === 'low' ? 'selected' : ''); ?>>Low</option>
            </select>
        </div>

        <?php if($statusFilter || $priorityFilter): ?>
            <div class="col-auto">
                <a href="<?php echo e(route('admin.tasks')); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </form>
</div>

<!-- Tasks Table Card -->
<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Task Title & Details</th>
                    <th>Customer</th>
                    <th>Scheduled Date & Time</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td>
                            <strong class="text-dark d-block"><?php echo e($task->task_title); ?></strong>
                            <small class="text-muted"><?php echo e(Str::limit($task->description, 60, '...') ?: 'No description provided'); ?></small>
                        </td>
                        <td>
                            <?php if($task->customer): ?>
                                <div><strong class="text-dark"><?php echo e($task->customer->name); ?></strong></div>
                                <small class="text-muted"><?php echo e($task->customer->email); ?></small>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Unknown user</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div><i class="far fa-calendar-alt text-primary me-1"></i><?php echo e(\Carbon\Carbon::parse($task->reminder_date)->format('M d, Y')); ?></div>
                            <small class="text-muted"><i class="far fa-clock me-1"></i><?php echo e(\Carbon\Carbon::parse($task->reminder_time)->format('h:i A')); ?></small>
                        </td>
                        <td>
                            <?php if($task->priority === 'high'): ?>
                                <span class="badge bg-danger">High</span>
                            <?php elseif($task->priority === 'medium'): ?>
                                <span class="badge bg-warning text-dark">Medium</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Low</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($task->status === 'completed'): ?>
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Completed</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-muted"><?php echo e($task->created_at->format('M d, Y')); ?></small>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 d-block text-secondary"></i>
                            No household task records found matching criteria.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/admin/tasks.blade.php ENDPATH**/ ?>
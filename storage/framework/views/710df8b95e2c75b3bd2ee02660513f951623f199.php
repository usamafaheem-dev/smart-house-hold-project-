<?php $__env->startSection('title', 'Admin — System Activity Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-history me-2 text-danger"></i>System Activity Audit Logs</h2>
        <p class="text-muted mb-0">Track real-time actions logged across registration, tasks, and bookings (Module 5 Integration).</p>
    </div>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Logged Time</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td>
                            <?php if($log->user): ?>
                                <strong class="text-dark"><i class="fas fa-user me-1 text-primary"></i><?php echo e($log->user->name); ?></strong>
                                <small class="text-muted d-block">(<?php echo e(ucfirst($log->user->role)); ?>)</small>
                            <?php else: ?>
                                <span class="text-muted">System / Guest</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-primary"><?php echo e($log->action); ?></span></td>
                        <td><?php echo e($log->description); ?></td>
                        <td class="small font-monospace text-muted"><?php echo e($log->ip_address ?: '127.0.0.1'); ?></td>
                        <td class="small text-muted"><?php echo e($log->created_at->format('M d, Y - h:i:s A')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No activity logs recorded yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/admin/logs.blade.php ENDPATH**/ ?>
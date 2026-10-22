<?php $__env->startSection('title', 'Admin — Payments Ledger'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-money-check-alt me-2 text-danger"></i>Payments Ledger</h2>
        <p class="text-muted mb-0">All invoices generated across the system.</p>
    </div>
    <a href="<?php echo e(route('admin.analytics')); ?>" class="btn btn-outline-primary fw-bold"><i class="fas fa-chart-line me-1"></i> Analytics</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card card-custom p-3 border-start border-success border-4">
            <div class="text-muted small text-uppercase fw-bold">Total Collected (Paid)</div>
            <div class="fs-4 fw-bold text-success">Rs. <?php echo e(number_format($totalPaid, 2)); ?></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-custom p-3 border-start border-warning border-4">
            <div class="text-muted small text-uppercase fw-bold">Total Outstanding (Unpaid)</div>
            <div class="fs-4 fw-bold text-warning">Rs. <?php echo e(number_format($totalUnpaid, 2)); ?></div>
        </div>
    </div>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Provider</th>
                    <th>Service</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge bg-dark font-monospace"><?php echo e($payment->invoice_number); ?></span></td>
                        <td class="fw-semibold text-dark"><?php echo e($payment->booking->customer->name ?? 'N/A'); ?></td>
                        <td class="small"><?php echo e($payment->booking->provider->name ?? '—'); ?></td>
                        <td><?php echo e($payment->booking->category->name ?? 'N/A'); ?></td>
                        <td class="text-success fw-bold">Rs. <?php echo e(number_format($payment->amount, 2)); ?></td>
                        <td>
                            <?php if($payment->method): ?>
                                <span class="badge bg-light text-dark border text-uppercase"><?php echo e($payment->method); ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($payment->isPaid()): ?>
                                <span class="badge bg-success">Paid</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Unpaid</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted">
                            <?php echo e($payment->isPaid() && $payment->paid_at ? $payment->paid_at->format('M d, Y') : $payment->created_at->format('M d, Y')); ?>

                        </td>
                        <td class="text-end">
                            <a href="<?php echo e(route('payments.invoice', $payment->id)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-receipt me-1"></i> View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-file-invoice fa-3x mb-3 d-block text-secondary"></i>
                            No payments recorded yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/admin/payments.blade.php ENDPATH**/ ?>
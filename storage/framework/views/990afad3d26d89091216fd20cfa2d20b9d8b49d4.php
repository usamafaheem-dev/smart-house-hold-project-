<?php $__env->startSection('title', 'My Payments & Invoices'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>My Payments & Invoices</h2>
        <p class="text-muted mb-0">Invoices are generated automatically once a service is completed.</p>
    </div>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Invoice #</th>
                    <th>Service</th>
                    <th>Provider</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge bg-dark text-uppercase font-monospace"><?php echo e($payment->invoice_number); ?></span></td>
                        <td>
                            <i class="fas <?php echo e($payment->booking->category->icon ?? 'fa-tools'); ?> text-primary me-2"></i>
                            <strong class="text-dark"><?php echo e($payment->booking->category->name ?? 'N/A'); ?></strong>
                            <div class="small text-muted font-monospace"><?php echo e($payment->booking->booking_id ?? ''); ?></div>
                        </td>
                        <td>
                            <?php if($payment->booking->provider): ?>
                                <span class="text-info fw-bold"><i class="fas fa-hard-hat me-1"></i><?php echo e($payment->booking->provider->name); ?></span>
                            <?php else: ?>
                                <span class="text-muted fst-italic">—</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="text-success fw-bold">Rs. <?php echo e(number_format($payment->amount, 2)); ?></span></td>
                        <td>
                            <?php if($payment->isPaid()): ?>
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Paid</span>
                                <div class="small text-muted text-uppercase mt-1"><?php echo e($payment->method); ?></div>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Unpaid</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted">
                            <?php if($payment->isPaid() && $payment->paid_at): ?>
                                <?php echo e($payment->paid_at->format('M d, Y')); ?>

                            <?php else: ?>
                                <?php echo e($payment->created_at->format('M d, Y')); ?>

                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <?php if($payment->isPaid()): ?>
                                <a href="<?php echo e(route('payments.invoice', $payment->id)); ?>" class="btn btn-sm btn-outline-primary fw-bold">
                                    <i class="fas fa-receipt me-1"></i> View Invoice
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('payments.invoice', $payment->id)); ?>" class="btn btn-sm btn-primary fw-bold shadow-sm">
                                    <i class="fas fa-credit-card me-1"></i> Pay Now
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-file-invoice fa-3x mb-3 d-block text-secondary"></i>
                            No invoices yet. They appear here once your booked services are completed.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/payments/index.blade.php ENDPATH**/ ?>
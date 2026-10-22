<?php $__env->startSection('title', 'Invoice ' . $payment->invoice_number); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @media  print {
        .navbar, footer, .no-print, .alert { display: none !important; }
        .invoice-card { box-shadow: none !important; border: none !important; }
        body { background: #fff !important; }
    }
    .invoice-brand {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }
    .paid-stamp {
        color: #10b981;
        border: 4px solid #10b981;
        padding: 0.25rem 1.25rem;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 3px;
        transform: rotate(-8deg);
        display: inline-block;
        opacity: 0.85;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php $booking = $payment->booking; ?>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.payments') : route('payments.index')); ?>" class="btn btn-outline-secondary fw-bold">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <button onclick="window.print()" class="btn btn-outline-primary fw-bold">
        <i class="fas fa-print me-1"></i> Print Invoice
    </button>
</div>

<div class="card card-custom p-4 p-md-5 invoice-card" style="max-width: 850px; margin: 0 auto;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start flex-wrap border-bottom pb-4 mb-4">
        <div>
            <h3 class="invoice-brand mb-1"><i class="fas fa-home me-2"></i>Smart Household</h3>
            <p class="text-muted small mb-0">Home Services Management System</p>
        </div>
        <div class="text-md-end">
            <h4 class="fw-bold text-dark mb-1">INVOICE</h4>
            <span class="badge bg-dark font-monospace"><?php echo e($payment->invoice_number); ?></span>
        </div>
    </div>

    <!-- Parties -->
    <div class="row mb-4">
        <div class="col-sm-6 mb-3">
            <h6 class="text-uppercase text-muted small fw-bold mb-2">Billed To</h6>
            <div class="fw-bold text-dark"><?php echo e($booking->customer->name ?? 'Customer'); ?></div>
            <div class="small text-muted"><?php echo e($booking->customer->email ?? ''); ?></div>
            <div class="small text-muted"><?php echo e($booking->customer->phone ?? ''); ?></div>
            <div class="small text-muted"><?php echo e($booking->address); ?></div>
        </div>
        <div class="col-sm-6 mb-3 text-sm-end">
            <h6 class="text-uppercase text-muted small fw-bold mb-2">Invoice Details</h6>
            <div class="small"><span class="text-muted">Issued:</span> <strong><?php echo e($payment->created_at->format('M d, Y')); ?></strong></div>
            <div class="small"><span class="text-muted">Booking:</span> <strong class="font-monospace"><?php echo e($booking->booking_id); ?></strong></div>
            <div class="small"><span class="text-muted">Provider:</span> <strong><?php echo e($booking->provider->name ?? 'Unassigned'); ?></strong></div>
            <div class="small">
                <span class="text-muted">Status:</span>
                <?php if($payment->isPaid()): ?>
                    <span class="badge bg-success">Paid</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Unpaid</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Line items -->
    <div class="table-responsive mb-4">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Description</th>
                    <th>Service Date</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <i class="fas <?php echo e($booking->category->icon ?? 'fa-tools'); ?> text-primary me-2"></i>
                        <strong><?php echo e($booking->category->name ?? 'Service'); ?></strong>
                        <div class="small text-muted"><?php echo e($booking->category->description ?? ''); ?></div>
                    </td>
                    <td class="small text-muted"><?php echo e(\Carbon\Carbon::parse($booking->service_date)->format('M d, Y')); ?></td>
                    <td class="text-end fw-bold">Rs. <?php echo e(number_format($payment->amount, 2)); ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-end border-0">Total</th>
                    <th class="text-end text-success border-0 fs-5">Rs. <?php echo e(number_format($payment->amount, 2)); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Payment section -->
    <?php if($payment->isPaid()): ?>
        <div class="text-center py-4 border-top">
            <div class="paid-stamp mb-3">Paid</div>
            <div class="small text-muted">
                Paid via <strong class="text-uppercase"><?php echo e($payment->method); ?></strong>
                on <?php echo e($payment->paid_at ? $payment->paid_at->format('M d, Y \a\t h:i A') : ''); ?>

            </div>
            <div class="small text-muted">Transaction Ref: <span class="font-monospace"><?php echo e($payment->transaction_ref); ?></span></div>
        </div>
    <?php elseif(auth()->id() === $booking->customer_id): ?>
        <div class="border-top pt-4 no-print">
            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-credit-card me-2 text-primary"></i>Complete Payment</h6>
            <form method="POST" action="<?php echo e(route('payments.pay', $payment->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                        <select name="method" class="form-select" required>
                            <option value="" disabled selected>Choose a method...</option>
                            <option value="cash">Cash on Delivery</option>
                            <option value="card">Credit / Debit Card</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold w-100 shadow-sm">
                            <i class="fas fa-check-circle me-1"></i> Pay Rs. <?php echo e(number_format($payment->amount, 2)); ?>

                        </button>
                    </div>
                </div>
                <p class="small text-muted mt-2 mb-0"><i class="fas fa-info-circle me-1"></i>This is a simulated payment for demonstration purposes.</p>
            </form>
        </div>
    <?php else: ?>
        <div class="border-top pt-4 text-center text-muted">
            <i class="fas fa-clock me-1"></i> This invoice is awaiting payment from the customer.
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/payments/invoice.blade.php ENDPATH**/ ?>
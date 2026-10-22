<?php $__env->startSection('title', 'Service Bookings List'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-calendar-check me-2 text-primary"></i>Service Bookings List</h2>
        <p class="text-muted mb-0">Track and manage home service request statuses.</p>
    </div>
    <?php if(auth()->user()->isCustomer()): ?>
        <a href="<?php echo e(route('bookings.browse')); ?>" class="btn btn-primary fw-bold shadow-sm">
            <i class="fas fa-plus me-1"></i> Book New Service
        </a>
    <?php endif; ?>
</div>

<div id="ajaxAlertContainer"></div>

<?php if(!empty($pendingApproval) && $pendingApproval): ?>
    <div class="alert alert-warning shadow-sm d-flex align-items-center" role="alert">
        <i class="fas fa-hourglass-half fa-2x me-3"></i>
        <div>
            <strong>Your provider account is pending approval.</strong>
            <div class="small">An administrator must approve your account before you can view or accept service requests.</div>
        </div>
    </div>
<?php endif; ?>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-nowrap">Booking ID</th>
                    <th class="text-nowrap">Service Category</th>
                    <?php if(!auth()->user()->isCustomer()): ?>
                        <th>Customer Name</th>
                    <?php else: ?>
                        <th>Assigned To</th>
                    <?php endif; ?>
                    <th>Service Date & Time</th>
                    <th>Address</th>
                    <th>Estimated Price</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge bg-dark text-uppercase font-monospace"><?php echo e($booking->booking_id); ?></span></td>
                        <td class="text-nowrap">
                            <div class="d-inline-flex align-items-center gap-2">
                                <span>
                                    <i class="fas <?php echo e($booking->category->icon ?? 'fa-tools'); ?> text-primary me-1"></i>
                                    <strong class="text-dark"><?php echo e($booking->category->name); ?></strong>
                                </span>
                                <?php if(auth()->user()->isProvider() && auth()->user()->offersCategory($booking->category_id)): ?>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1 small" style="font-size: 0.72rem;" title="Matches your registered specialty">
                                        <i class="fas fa-star text-warning me-1"></i>Your Skill
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php if(!auth()->user()->isCustomer()): ?>
                            <td>
                                <div><strong class="text-dark"><?php echo e($booking->customer->name ?? 'Guest'); ?></strong></div>
                                <small class="text-muted"><?php echo e($booking->customer->phone ?? 'N/A'); ?></small>
                            </td>
                        <?php else: ?>
                            <td>
                                <?php if($booking->provider_id && $booking->provider): ?>
                                    <span class="text-info fw-bold"><i class="fas fa-hard-hat me-1"></i><?php echo e($booking->provider->name); ?></span>
                                    <?php $ar = $booking->provider->averageRating(); ?>
                                    <?php if($ar > 0): ?>
                                        <div class="small text-warning"><i class="fas fa-star"></i> <?php echo e($ar); ?> <span class="text-muted">(<?php echo e($booking->provider->totalReviews()); ?>)</span></div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Waiting for Provider...</span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <td>
                            <div><i class="far fa-calendar-alt text-primary me-1"></i><?php echo e(\Carbon\Carbon::parse($booking->service_date)->format('M d, Y')); ?></div>
                            <small class="text-muted"><i class="far fa-clock me-1"></i><?php echo e(\Carbon\Carbon::parse($booking->service_time)->format('h:i A')); ?></small>
                        </td>
                        <td class="small text-muted" style="max-width: 200px;"><?php echo e(Str::limit($booking->address, 60, '...')); ?></td>
                        <td><span class="text-success fw-bold">Rs. <?php echo e(number_format($booking->total_amount, 2)); ?></span></td>
                        <td>
                            <?php if($booking->status === 'completed'): ?>
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Completed</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end text-nowrap">
                            <?php if(auth()->user()->isCustomer()): ?>
                                <?php if($booking->status === 'completed'): ?>
                                    <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                        
                                        <?php if($booking->payment): ?>
                                            <?php if($booking->payment->isPaid()): ?>
                                                <a href="<?php echo e(route('payments.invoice', $booking->payment->id)); ?>" class="btn btn-sm btn-outline-success rounded-pill fw-bold px-3 py-1 text-nowrap shadow-none" title="View Paid Invoice">
                                                    <i class="fas fa-file-invoice me-1"></i> Invoice
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('payments.invoice', $booking->payment->id)); ?>" class="btn btn-sm btn-primary rounded-pill fw-bold px-3 py-1 text-nowrap shadow-sm" title="Pay Service Bill">
                                                    <i class="fas fa-credit-card me-1"></i> Pay Now
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <?php if($booking->provider_id): ?>
                                            <?php if($booking->review): ?>
                                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1 small" title="Your Rating: <?php echo e($booking->review->rating); ?> / 5 Stars">
                                                    <span class="text-warning me-1">
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star <?php echo e($i <= $booking->review->rating ? '' : 'text-secondary opacity-25'); ?>"></i>
                                                        <?php endfor; ?>
                                                    </span>
                                                    <span class="fw-bold small text-muted">(<?php echo e($booking->review->rating); ?>.0)</span>
                                                </span>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-warning text-dark rounded-pill fw-bold px-3 py-1 text-nowrap rate-btn shadow-none"
                                                        data-id="<?php echo e($booking->id); ?>"
                                                        data-provider="<?php echo e($booking->provider->name ?? 'the provider'); ?>">
                                                    <i class="fas fa-star text-warning me-1"></i> Rate
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border rounded-pill py-1 px-3 fw-semibold">
                                        <i class="fas fa-hourglass-half text-warning me-1"></i> In Progress
                                    </span>
                                <?php endif; ?>
                            <?php elseif(auth()->user()->isProvider()): ?>
                                <?php if(is_null($booking->provider_id)): ?>
                                    <form method="POST" action="<?php echo e(route('bookings.accept', $booking->id)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill fw-bold shadow-sm px-3 py-1">
                                            <i class="fas fa-handshake me-1"></i> Accept Task
                                        </button>
                                    </form>
                                <?php elseif($booking->provider_id === auth()->id() && $booking->status === 'pending'): ?>
                                    <div class="d-inline-flex gap-1 align-items-center">
                                        <form method="POST" action="<?php echo e(route('bookings.toggle-status', $booking->id)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill fw-bold shadow-sm px-3 py-1">
                                                <i class="fas fa-check-circle me-1"></i> Complete
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('bookings.reject', $booking->id)); ?>" class="d-inline" onsubmit="return confirm('Release this request back to the open market?');">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill fw-bold px-2 py-1" title="Release request">
                                                <i class="fas fa-undo me-1"></i> Release
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="badge bg-light text-success border border-success rounded-pill py-1 px-3 fw-semibold">
                                        <i class="fas fa-check-double me-1"></i> Done
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <form method="POST" action="<?php echo e(route('bookings.toggle-status', $booking->id)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm rounded-pill px-3 py-1 <?php echo e($booking->status === 'pending' ? 'btn-outline-success' : 'btn-outline-warning'); ?>">
                                        <i class="fas fa-sync-alt me-1"></i> Mark <?php echo e($booking->status === 'pending' ? 'Completed' : 'Pending'); ?>

                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3 d-block text-secondary"></i>
                            No service bookings found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if(auth()->user()->isCustomer()): ?>
<!-- Rate Provider Modal -->
<div class="modal fade" id="rateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold"><i class="fas fa-star me-2"></i>Rate Your Provider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rateForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p class="text-muted mb-2">How was the service from <strong id="rateProviderName">the provider</strong>?</p>
                    <input type="hidden" name="rating" id="ratingValue" value="0">
                    <div class="mb-3 fs-2 text-center" id="starPicker" style="cursor: pointer;">
                        <i class="far fa-star rate-star text-warning" data-v="1"></i>
                        <i class="far fa-star rate-star text-warning" data-v="2"></i>
                        <i class="far fa-star rate-star text-warning" data-v="3"></i>
                        <i class="far fa-star rate-star text-warning" data-v="4"></i>
                        <i class="far fa-star rate-star text-warning" data-v="5"></i>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Comment <span class="text-muted small">(optional)</span></label>
                        <textarea class="form-control" name="comment" id="rateComment" rows="3" placeholder="Share your experience..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark" id="submitRateBtn"><i class="fas fa-paper-plane me-1"></i> Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php if(auth()->user()->isCustomer()): ?>
<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    let currentBookingId = null;

    // Open rating modal
    $(document).on('click', '.rate-btn', function() {
        currentBookingId = $(this).data('id');
        $('#rateProviderName').text($(this).data('provider'));
        $('#ratingValue').val(0);
        $('#rateComment').val('');
        paintStars(0);
        new bootstrap.Modal(document.getElementById('rateModal')).show();
    });

    // Star hover & click
    $(document).on('mouseenter', '.rate-star', function() { paintStars($(this).data('v')); });
    $('#starPicker').on('mouseleave', function() { paintStars($('#ratingValue').val()); });
    $(document).on('click', '.rate-star', function() {
        $('#ratingValue').val($(this).data('v'));
        paintStars($(this).data('v'));
    });

    function paintStars(v) {
        $('.rate-star').each(function() {
            if ($(this).data('v') <= v) {
                $(this).removeClass('far').addClass('fas');
            } else {
                $(this).removeClass('fas').addClass('far');
            }
        });
    }

    // Submit review
    $('#rateForm').on('submit', function(e) {
        e.preventDefault();
        if (parseInt($('#ratingValue').val()) < 1) {
            showAlert('warning', 'Please select a star rating first.');
            return;
        }
        const btn = $('#submitRateBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Submitting...');

        $.ajax({
            url: "/bookings/" + currentBookingId + "/review",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Submit Review');
                if(response.success) {
                    bootstrap.Modal.getInstance(document.getElementById('rateModal')).hide();
                    showAlert('success', response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Submit Review');
                let msg = 'Failed to submit review.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                showAlert('danger', msg);
            }
        });
    });

    function showAlert(type, message) {
        const html = `<div class="alert alert-${type} alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-info-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        $('#ajaxAlertContainer').html(html);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/bookings/index.blade.php ENDPATH**/ ?>
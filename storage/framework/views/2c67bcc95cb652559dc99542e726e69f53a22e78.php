<?php $__env->startSection('title', 'My Complaints'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-comment-dots me-2 text-primary"></i>Complaints & Support</h2>
        <p class="text-muted mb-0">Raise an issue about a service and track the admin's response.</p>
    </div>
    <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addComplaintModal">
        <i class="fas fa-plus me-1"></i> New Complaint
    </button>
</div>

<div id="ajaxAlertContainer"></div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Complaint ID</th>
                    <th>Subject</th>
                    <th>Related Booking</th>
                    <th>Status</th>
                    <th>Admin Response</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complaint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge bg-dark text-uppercase font-monospace"><?php echo e($complaint->complaint_id); ?></span></td>
                        <td>
                            <div class="fw-semibold text-dark"><?php echo e($complaint->subject); ?></div>
                            <div class="small text-muted"><?php echo e(Str::limit($complaint->message, 60, '...')); ?></div>
                        </td>
                        <td class="small">
                            <?php if($complaint->booking): ?>
                                <span class="font-monospace text-primary"><?php echo e($complaint->booking->booking_id); ?></span>
                                <div class="text-muted"><?php echo e($complaint->booking->category->name ?? ''); ?></div>
                            <?php else: ?>
                                <span class="text-muted fst-italic">General</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($complaint->isResolved()): ?>
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Resolved</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Open</span>
                            <?php endif; ?>
                        </td>
                        <td class="small" style="max-width: 260px;">
                            <?php if($complaint->admin_reply): ?>
                                <div class="text-dark"><?php echo e($complaint->admin_reply); ?></div>
                                <div class="text-muted mt-1"><i class="fas fa-user-shield me-1"></i><?php echo e($complaint->resolved_at ? $complaint->resolved_at->format('M d, Y') : ''); ?></div>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Awaiting response...</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted"><?php echo e($complaint->created_at->format('M d, Y')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-comment-slash fa-3x mb-3 d-block text-secondary"></i>
                            No complaints filed. Click <strong>"New Complaint"</strong> if you need help.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- New Complaint Modal -->
<div class="modal fade" id="addComplaintModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-comment-dots me-2"></i>File a Complaint</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createComplaintForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="subject" class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="e.g. Provider arrived late" required>
                    </div>
                    <div class="mb-3">
                        <label for="booking_id" class="form-label fw-semibold">Related Booking <span class="text-muted small">(optional)</span></label>
                        <select class="form-select" id="booking_id" name="booking_id">
                            <option value="">General complaint (no booking)</option>
                            <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($b->id); ?>"><?php echo e($b->booking_id); ?> — <?php echo e($b->category->name ?? 'Service'); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label fw-semibold">Details <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Describe your issue in detail..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="saveComplaintBtn"><i class="fas fa-paper-plane me-1"></i> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#createComplaintForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#saveComplaintBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Submitting...');

        $.ajax({
            url: "<?php echo e(route('complaints.store')); ?>",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Submit');
                if(response.success) {
                    $('#addComplaintModal').modal('hide');
                    $('#createComplaintForm')[0].reset();
                    showAlert('success', response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Submit');
                showAlert('danger', 'Failed to submit complaint. Please check the required fields.');
            }
        });
    });

    function showAlert(type, message) {
        const html = `<div class="alert alert-${type} alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-info-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        $('#ajaxAlertContainer').html(html);
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/complaints/index.blade.php ENDPATH**/ ?>
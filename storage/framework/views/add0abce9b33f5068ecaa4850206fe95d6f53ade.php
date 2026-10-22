<?php $__env->startSection('title', 'Admin — All Service Bookings'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-clipboard-list me-2 text-danger"></i>System-wide Service Bookings</h2>
        <p class="text-muted mb-0">Monitor and update booking statuses across all customer requests.</p>
    </div>
</div>

<style>
    .custom-dropdown-item:hover, .custom-dropdown-item:focus {
        background-color: rgba(79, 70, 229, 0.08) !important;
        color: #4f46e5 !important;
        transform: translateX(4px);
    }
</style>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Booking Code</th>
                    <th>Category</th>
                    <th>Customer Details</th>
                    <th>Service Date & Time</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Assigned Provider</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge bg-dark text-uppercase font-monospace"><?php echo e($booking->booking_id); ?></span></td>
                        <td>
                            <i class="fas <?php echo e($booking->category->icon ?? 'fa-tools'); ?> text-primary me-2"></i>
                            <strong><?php echo e($booking->category->name); ?></strong>
                        </td>
                        <td>
                            <div><strong class="text-dark"><?php echo e($booking->customer->name ?? 'Guest'); ?></strong></div>
                            <small class="text-muted"><?php echo e($booking->customer->email ?? ''); ?> | <?php echo e($booking->customer->phone ?? 'N/A'); ?></small>
                        </td>
                        <td>
                            <div><i class="far fa-calendar-alt text-primary me-1"></i><?php echo e(\Carbon\Carbon::parse($booking->service_date)->format('M d, Y')); ?></div>
                            <small class="text-muted"><i class="far fa-clock me-1"></i><?php echo e(\Carbon\Carbon::parse($booking->service_time)->format('h:i A')); ?></small>
                        </td>
                        <td><span class="text-success fw-bold">Rs. <?php echo e(number_format($booking->total_amount, 2)); ?></span></td>
                        <td>
                            <?php if($booking->status === 'completed'): ?>
                                <span class="badge bg-success">Completed</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($booking->provider_id): ?>
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <span class="text-info fw-bold"><i class="fas fa-hard-hat me-1"></i><?php echo e($booking->provider->name); ?></span>
                                    <?php if($booking->status !== 'completed'): ?>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill py-0 px-2" data-bs-toggle="modal" data-bs-target="#reassignModal<?php echo e($booking->id); ?>" title="Reassign to another provider">
                                            <i class="fas fa-exchange-alt me-1"></i>Reassign
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <form method="POST" action="<?php echo e(route('admin.bookings.assign', $booking->id)); ?>" onsubmit="if(document.getElementById('provider_id_<?php echo e($booking->id); ?>').value === '') { alert('Please select a provider first.'); return false; } return true;">
                                    <?php echo csrf_field(); ?>
                                    <div class="input-group input-group-sm shadow-sm" style="border-radius: 8px; flex-wrap: nowrap;">
                                        <input type="hidden" name="provider_id" id="provider_id_<?php echo e($booking->id); ?>">
                                        
                                        <button class="btn bg-light border-0 dropdown-toggle text-start d-flex justify-content-between align-items-center" 
                                                type="button" 
                                                data-bs-toggle="dropdown" 
                                                aria-expanded="false"
                                                style="flex: 1 1 auto; color: #4f46e5; font-weight: 500; min-width: 150px;">
                                            <span id="dropdown_text_<?php echo e($booking->id); ?>">Select Provider...</span>
                                        </button>
                                        
                                        <ul class="dropdown-menu shadow-lg border-0" style="border-radius: 12px; padding: 0.5rem; min-width: 220px;">
                                            <li><h6 class="dropdown-header text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Available Providers</h6></li>
                                            <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li>
                                                    <a class="dropdown-item fw-medium py-2 px-3 custom-dropdown-item" 
                                                       href="javascript:void(0)"
                                                       onclick="document.getElementById('provider_id_<?php echo e($booking->id); ?>').value = '<?php echo e($provider->id); ?>'; document.getElementById('dropdown_text_<?php echo e($booking->id); ?>').innerText = '<?php echo e(addslashes($provider->name)); ?>';"
                                                       style="border-radius: 8px; transition: all 0.2s;">
                                                        <i class="fas fa-user-tie me-2 text-primary opacity-75"></i><?php echo e($provider->name); ?>

                                                    </a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                        
                                        <button type="submit" class="btn btn-primary fw-bold border-0 px-3">Assign</button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No service bookings found in system.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($booking->provider_id && $booking->status !== 'completed'): ?>
        <div class="modal fade" id="reassignModal<?php echo e($booking->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-dark"><i class="fas fa-exchange-alt me-2 text-primary"></i>Reassign Provider</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="<?php echo e(route('admin.bookings.assign', $booking->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <p class="text-muted small mb-2">Reassign service booking <strong>#<?php echo e($booking->booking_id); ?></strong> (<?php echo e($booking->category->name); ?>).</p>
                            <p class="small text-secondary mb-3">Current Provider: <strong class="text-dark"><?php echo e($booking->provider->name); ?></strong></p>
                            
                            <label class="form-label fw-semibold text-dark">Select New Provider</label>
                            <select name="provider_id" class="form-select" required>
                                <option value="">-- Choose Approved Provider --</option>
                                <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($provider->id); ?>" <?php echo e($booking->provider_id == $provider->id ? 'disabled' : ''); ?>>
                                        <?php echo e($provider->name); ?> <?php echo e($booking->provider_id == $provider->id ? '(Current)' : ''); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Confirm Reassign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/admin/bookings.blade.php ENDPATH**/ ?>
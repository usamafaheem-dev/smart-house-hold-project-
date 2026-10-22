<?php $__env->startSection('title', 'Household Task Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-list-check me-2 text-primary"></i>Household Task Management</h2>
        <p class="text-muted mb-0">Organize your home chores, set reminder dates & times, and track progress.</p>
    </div>
    <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal">
        <i class="fas fa-plus me-1"></i> Add New Task
    </button>
</div>

<?php
    $todayDate = \Carbon\Carbon::today()->toDateString();
    $dueTodayTasks = $tasks->where('status', 'pending')->where('reminder_date', '<=', $todayDate);
?>

<?php if($dueTodayTasks->count() > 0): ?>
    <div class="alert alert-warning border-2 border-warning shadow-sm d-flex align-items-center justify-content-between mb-4 p-3 rounded-3" role="alert">
        <div class="d-flex align-items-center">
            <span class="bg-warning text-dark p-2 rounded-circle me-3">
                <i class="fas fa-bell fa-lg"></i>
            </span>
            <div>
                <h6 class="fw-bold text-dark mb-0">Attention: You have <?php echo e($dueTodayTasks->count()); ?> household task(s) due today!</h6>
                <small class="text-muted">Review your routine chores scheduled for today below and mark them complete once finished.</small>
            </div>
        </div>
        <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill shadow-sm"><?php echo e($dueTodayTasks->count()); ?> Due Today</span>
    </div>
<?php endif; ?>

<!-- Dynamic Alert for AJAX Actions -->
<div id="ajaxAlertContainer"></div>

<!-- Task List Card -->
<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tasksTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Task Title</th>
                    <th>Description</th>
                    <th>Due Date & Time</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="taskListBody">
                <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr id="task-row-<?php echo e($task->id); ?>">
                        <td><?php echo e($index + 1); ?></td>
                        <td class="fw-semibold text-dark"><?php echo e($task->task_title); ?></td>
                        <td class="text-muted small"><?php echo e(Str::limit($task->description, 50, '...') ?: 'N/A'); ?></td>
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
                        <td class="status-cell" id="status-cell-<?php echo e($task->id); ?>">
                            <?php if($task->status === 'completed'): ?>
                                <span class="badge bg-success px-3 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.85rem;">
                                    <i class="fas fa-check-circle me-1"></i> Completed
                                </span>
                            <?php else: ?>
                                <select class="form-select form-select-sm status-dropdown fw-bold shadow-sm text-warning border-warning" data-id="<?php echo e($task->id); ?>" style="width: 140px; cursor: pointer;">
                                    <option value="pending" class="text-warning fw-bold" selected>Pending</option>
                                    <option value="completed" class="text-success fw-bold">Mark Completed</option>
                                </select>
                            <?php endif; ?>
                        </td>
                        <td class="text-end text-nowrap">
                            <button class="btn btn-sm btn-outline-primary edit-task-btn me-1 <?php echo e($task->status === 'completed' ? 'd-none' : ''); ?>" 
                                    data-id="<?php echo e($task->id); ?>"
                                    data-title="<?php echo e($task->task_title); ?>"
                                    data-description="<?php echo e($task->description); ?>"
                                    data-date="<?php echo e($task->reminder_date); ?>"
                                    data-time="<?php echo e(\Carbon\Carbon::parse($task->reminder_time)->format('H:i')); ?>"
                                    data-priority="<?php echo e($task->priority); ?>"
                                    data-status="<?php echo e($task->status); ?>">
                                <i class="fas fa-edit me-1"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-task-btn" data-id="<?php echo e($task->id); ?>">
                                <i class="fas fa-trash-alt me-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr id="noTasksRow">
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 d-block text-secondary"></i>
                            No household tasks found. Click <strong>"Add New Task"</strong> to create one!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Task Bootstrap Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>Create Household Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createTaskForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="task_title" class="form-label fw-semibold">Task Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="task_title" name="task_title" placeholder="e.g. Clean living room AC filter" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Additional instructions or notes..."></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="reminder_date" class="form-label fw-semibold">Reminder Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="reminder_date" name="reminder_date" value="<?php echo e(date('Y-m-d')); ?>" required>
                        </div>
                        <div class="col-6">
                            <label for="reminder_time" class="form-label fw-semibold">Reminder Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="reminder_time" name="reminder_time" value="10:00" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label fw-semibold">Priority Level <span class="text-danger">*</span></label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low">Low Priority</option>
                            <option value="medium" selected>Medium Priority</option>
                            <option value="high">High Priority</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="saveTaskBtn"><i class="fas fa-save me-1"></i> Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Task Bootstrap Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Edit Household Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTaskForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" id="edit_task_id" name="task_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_task_title" class="form-label fw-semibold">Task Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_task_title" name="task_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" placeholder="Additional instructions or notes..."></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="edit_reminder_date" class="form-label fw-semibold">Reminder Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_reminder_date" name="reminder_date" required>
                        </div>
                        <div class="col-6">
                            <label for="edit_reminder_time" class="form-label fw-semibold">Reminder Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="edit_reminder_time" name="reminder_time" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="edit_priority" class="form-label fw-semibold">Priority Level <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_priority" name="priority" required>
                                <option value="low">Low Priority</option>
                                <option value="medium">Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="edit_status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="updateTaskBtn"><i class="fas fa-check me-1"></i> Update Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // AJAX Setup Headers
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // 1. Submit Add Task Form via AJAX
    $('#createTaskForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#saveTaskBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

        $.ajax({
            url: "<?php echo e(route('tasks.store')); ?>",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Task');
                if(response.success) {
                    $('#addTaskModal').modal('hide');
                    $('#createTaskForm')[0].reset();
                    showAlert('success', response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Task');
                showAlert('danger', 'Failed to create task. Please check required fields.');
            }
        });
    });

    // 2. Open Edit Task Modal with populated data
    $(document).on('click', '.edit-task-btn', function() {
        const id = $(this).data('id');
        const title = $(this).data('title');
        const description = $(this).data('description');
        const date = $(this).data('date');
        const time = $(this).data('time');
        const priority = $(this).data('priority');
        const status = $(this).data('status');

        $('#edit_task_id').val(id);
        $('#edit_task_title').val(title);
        $('#edit_description').val(description || '');
        $('#edit_reminder_date').val(date);
        $('#edit_reminder_time').val(time);
        $('#edit_priority').val(priority);
        $('#edit_status').val(status);

        $('#editTaskModal').modal('show');
    });

    // 3. Submit Edit Task Form via AJAX
    $('#editTaskForm').on('submit', function(e) {
        e.preventDefault();
        const taskId = $('#edit_task_id').val();
        const btn = $('#updateTaskBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');

        $.ajax({
            url: "/tasks/" + taskId,
            type: "POST", // Laravel <?php echo method_field('PUT'); ?> handles method spoofing
            data: $(this).serialize(),
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Update Task');
                if(response.success) {
                    $('#editTaskModal').modal('hide');
                    showAlert('success', response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Update Task');
                showAlert('danger', 'Failed to update task. Please check required fields.');
            }
        });
    });

    // 4. Update Task Status via AJAX Dropdown
    $(document).on('change', '.status-dropdown', function() {
        const taskId = $(this).data('id');
        const newStatus = $(this).val();
        const selectElement = $(this);
        const row = $('#task-row-' + taskId);
        const cell = $('#status-cell-' + taskId);
        
        selectElement.prop('disabled', true);
        
        $.ajax({
            url: "/tasks/" + taskId + "/toggle-status",
            type: "POST",
            data: { status: newStatus },
            success: function(response) {
                if(response.success) {
                    showAlert('success', response.message);
                    if(newStatus === 'completed') {
                        // Replace dropdown with Completed badge
                        cell.html('<span class="badge bg-success px-3 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.85rem;"><i class="fas fa-check-circle me-1"></i> Completed</span>');
                        // Hide edit button
                        row.find('.edit-task-btn').addClass('d-none');
                    }
                }
            },
            error: function() {
                selectElement.prop('disabled', false);
                showAlert('danger', 'Failed to update status.');
            }
        });
    });

    // 5. Delete Task via AJAX
    $(document).on('click', '.delete-task-btn', function() {
        if(!confirm('Are you sure you want to delete this task?')) return;
        
        const taskId = $(this).data('id');
        $.ajax({
            url: "/tasks/" + taskId,
            type: "DELETE",
            success: function(response) {
                if(response.success) {
                    $('#task-row-' + taskId).fadeOut(300, function() { $(this).remove(); });
                    showAlert('success', response.message);
                }
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/tasks/index.blade.php ENDPATH**/ ?>
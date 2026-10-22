@extends('layouts.app')

@section('title', 'Admin — Service Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-tags me-2 text-danger"></i>Service Category Management</h2>
        <p class="text-muted mb-0">Add, edit, or remove the home service categories customers can book.</p>
    </div>
    <button type="button" class="btn btn-primary fw-bold shadow-sm" id="addCategoryBtn" data-bs-toggle="modal" data-bs-target="#categoryModal">
        <i class="fas fa-plus me-1"></i> Add Category
    </button>
</div>

<div id="ajaxAlertContainer"></div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Est. Price</th>
                    <th>Bookings</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="categoryListBody">
                @forelse($categories as $category)
                    <tr id="category-row-{{ $category->id }}">
                        <td><span class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded" style="width:42px;height:42px;"><i class="fas {{ $category->icon ?? 'fa-tools' }} fa-lg"></i></span></td>
                        <td class="fw-semibold text-dark">{{ $category->name }}</td>
                        <td class="small text-muted" style="max-width: 320px;">{{ Str::limit($category->description, 70, '...') ?: 'N/A' }}</td>
                        <td class="text-success fw-bold">Rs. {{ number_format($category->estimated_price ?? 0, 2) }}</td>
                        <td><span class="badge bg-secondary">{{ $category->bookings_count }}</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary edit-category-btn"
                                    data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}"
                                    data-icon="{{ $category->icon }}"
                                    data-description="{{ $category->description }}"
                                    data-price="{{ $category->estimated_price }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-category-btn" data-id="{{ $category->id }}" data-count="{{ $category->bookings_count }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr id="noCategoriesRow">
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-tags fa-3x mb-3 d-block text-secondary"></i>
                            No service categories yet. Click <strong>"Add Category"</strong> to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Category Modal (Add / Edit) -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="categoryModalTitle"><i class="fas fa-plus-circle me-2"></i>Add Service Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm">
                @csrf
                <input type="hidden" id="category_id" name="category_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Carpentry" required>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label fw-semibold">FontAwesome Icon Class</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tools" id="iconPreview"></i></span>
                            <input type="text" class="form-control" id="icon" name="icon" placeholder="fa-hammer">
                        </div>
                        <small class="text-muted">e.g. <code>fa-hammer</code>, <code>fa-broom</code>, <code>fa-bolt</code>. Leave blank for default.</small>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="What this service covers..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="estimated_price" class="form-label fw-semibold">Estimated Price (Rs.)</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="estimated_price" name="estimated_price" placeholder="2500.00">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="saveCategoryBtn"><i class="fas fa-save me-1"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Live icon preview
    $('#icon').on('input', function() {
        const cls = $(this).val().trim() || 'fa-tools';
        $('#iconPreview').attr('class', 'fas ' + cls);
    });

    // Reset modal for "Add"
    $('#addCategoryBtn').on('click', function() {
        $('#categoryForm')[0].reset();
        $('#category_id').val('');
        $('#iconPreview').attr('class', 'fas fa-tools');
        $('#categoryModalTitle').html('<i class="fas fa-plus-circle me-2"></i>Add Service Category');
    });

    // Populate modal for "Edit"
    $(document).on('click', '.edit-category-btn', function() {
        $('#category_id').val($(this).data('id'));
        $('#name').val($(this).data('name'));
        $('#icon').val($(this).data('icon'));
        $('#description').val($(this).data('description'));
        $('#estimated_price').val($(this).data('price'));
        $('#iconPreview').attr('class', 'fas ' + ($(this).data('icon') || 'fa-tools'));
        $('#categoryModalTitle').html('<i class="fas fa-edit me-2"></i>Edit Service Category');
        new bootstrap.Modal(document.getElementById('categoryModal')).show();
    });

    // Submit (create or update)
    $('#categoryForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#category_id').val();
        const btn = $('#saveCategoryBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

        $.ajax({
            url: id ? "/admin/categories/" + id : "{{ route('admin.categories.store') }}",
            type: id ? "PUT" : "POST",
            data: $(this).serialize(),
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save');
                if(response.success) {
                    bootstrap.Modal.getInstance(document.getElementById('categoryModal')).hide();
                    showAlert('success', response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save');
                let msg = 'Failed to save category.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors)[0][0];
                }
                showAlert('danger', msg);
            }
        });
    });

    // Delete
    $(document).on('click', '.delete-category-btn', function() {
        const id = $(this).data('id');
        const count = $(this).data('count');
        if (count > 0) {
            showAlert('warning', 'This category has ' + count + ' booking(s) and cannot be deleted.');
            return;
        }
        if(!confirm('Delete this service category?')) return;

        $.ajax({
            url: "/admin/categories/" + id,
            type: "DELETE",
            success: function(response) {
                if(response.success) {
                    $('#category-row-' + id).fadeOut(300, function() { $(this).remove(); });
                    showAlert('success', response.message);
                }
            },
            error: function(xhr) {
                let msg = 'Failed to delete category.';
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
@endpush

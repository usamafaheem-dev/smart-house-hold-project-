@extends('layouts.app')

@section('title', 'Admin — Registered Users Monitoring')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-users-cog me-2 text-danger"></i>Registered Users Monitoring</h2>
        <p class="text-muted mb-0">Overview of all registered Customers, Service Providers, and Administrators.</p>
    </div>
    <div class="btn-group" role="group">
        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary {{ !$roleFilter ? 'active' : '' }}">All Users</a>
        <a href="{{ route('admin.users', ['role' => 'customer']) }}" class="btn btn-outline-primary {{ $roleFilter === 'customer' ? 'active' : '' }}">Customers</a>
        <a href="{{ route('admin.users', ['role' => 'provider']) }}" class="btn btn-outline-info {{ $roleFilter === 'provider' ? 'active' : '' }}">Providers</a>
        <a href="{{ route('admin.users', ['role' => 'admin']) }}" class="btn btn-outline-danger {{ $roleFilter === 'admin' ? 'active' : '' }}">Admins</a>
    </div>
</div>

<div id="ajax-alert-container"></div>

<div class="card card-custom p-3 p-md-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 40px;">#</th>
                    <th class="text-nowrap">User Name</th>
                    <th class="text-nowrap">Email Address</th>
                    <th class="text-nowrap">Phone</th>
                    <th>Address</th>
                    <th class="text-nowrap text-center">Role</th>
                    <th class="text-nowrap text-center">Status</th>
                    <th class="text-nowrap">Registered</th>
                    <th class="text-end text-nowrap">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $u)
                    <tr>
                        <td class="text-muted fw-bold">{{ $index + 1 }}</td>
                        <td class="text-nowrap fw-bold text-dark">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold {{ $u->role === 'admin' ? 'bg-danger' : ($u->role === 'provider' ? 'bg-info' : 'bg-primary') }}" style="width: 32px; height: 32px; font-size: 0.85rem; flex-shrink: 0;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <span>{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="text-nowrap small">{{ $u->email }}</td>
                        <td class="text-nowrap small">
                            @if($u->phone)
                                <i class="fas fa-phone-alt fa-xs text-muted me-1"></i>{{ $u->phone }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td class="small text-muted" style="max-width: 200px;">
                            <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $u->address }}">
                                {{ $u->address ?: 'N/A' }}
                            </span>
                        </td>
                        <td class="text-nowrap text-center">
                            @if($u->role === 'admin')
                                <span class="badge bg-danger rounded-pill px-2.5 py-1">Admin</span>
                            @elseif($u->role === 'provider')
                                <span class="badge bg-info text-dark rounded-pill px-2.5 py-1">Provider</span>
                            @else
                                <span class="badge bg-primary rounded-pill px-2.5 py-1">Customer</span>
                            @endif
                        </td>
                        <td class="text-nowrap text-center" id="status-cell-{{ $u->id }}">
                            @if($u->role === 'provider')
                                @if($u->is_approved)
                                    <span class="badge bg-success rounded-pill px-2.5 py-1 shadow-sm"><i class="fas fa-check-circle me-1"></i>Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 shadow-sm"><i class="fas fa-hourglass-half me-1"></i>Pending</span>
                                @endif
                            @else
                                <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1">Active</span>
                            @endif
                        </td>
                        <td class="small text-muted text-nowrap">{{ $u->created_at->format('M d, Y') }}</td>
                        <td class="text-end text-nowrap" id="action-cell-{{ $u->id }}">
                            @if($u->role === 'provider')
                                <form method="POST" action="{{ route('admin.users.approval', $u->id) }}" class="d-inline approval-toggle-form" data-provider-id="{{ $u->id }}" data-provider-name="{{ $u->name }}">
                                    @csrf
                                    @if($u->is_approved)
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 shadow-none btn-approval-toggle" title="Revoke provider approval">
                                            <i class="fas fa-ban me-1"></i> Revoke
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold shadow-sm btn-approval-toggle" title="Approve this provider">
                                            <i class="fas fa-check-circle me-1"></i> Approve
                                        </button>
                                    @endif
                                </form>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $(document).on('submit', '.approval-toggle-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('.btn-approval-toggle');
        const providerId = form.data('provider-id');
        const providerName = form.data('provider-name');
        const url = form.attr('action');

        // Disable button & show spinner
        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Updating...');

        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.success) {
                    const statusCell = $('#status-cell-' + providerId);
                    const actionCell = $('#action-cell-' + providerId);

                    if (res.is_approved) {
                        statusCell.html('<span class="badge bg-success rounded-pill px-2.5 py-1 shadow-sm"><i class="fas fa-check-circle me-1"></i>Approved</span>');
                        actionCell.html(`
                            <form method="POST" action="${url}" class="d-inline approval-toggle-form" data-provider-id="${providerId}" data-provider-name="${providerName}">
                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 shadow-none btn-approval-toggle" title="Revoke provider approval">
                                    <i class="fas fa-ban me-1"></i> Revoke
                                </button>
                            </form>
                        `);
                    } else {
                        statusCell.html('<span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 shadow-sm"><i class="fas fa-hourglass-half me-1"></i>Pending</span>');
                        actionCell.html(`
                            <form method="POST" action="${url}" class="d-inline approval-toggle-form" data-provider-id="${providerId}" data-provider-name="${providerName}">
                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold shadow-sm btn-approval-toggle" title="Approve this provider">
                                    <i class="fas fa-check-circle me-1"></i> Approve
                                </button>
                            </form>
                        `);
                    }

                    // Display flash alert banner
                    $('#ajax-alert-container').html(`
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>${res.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                } else {
                    btn.prop('disabled', false).html(originalHtml);
                    alert(res.message || 'Something went wrong.');
                }
            },
            error: function(xhr) {
                // Graceful fallback to normal form submission if AJAX fails
                form.off('submit').submit();
            }
        });
    });
});
</script>
@endpush

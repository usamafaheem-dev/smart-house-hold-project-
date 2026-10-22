@extends('layouts.app')

@section('title', 'Admin — Complaint Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-headset me-2 text-danger"></i>Complaint Management</h2>
        <p class="text-muted mb-0">Review customer complaints and post an official response.</p>
    </div>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Complaint</th>
                    <th>Booking</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $complaint)
                    <tr>
                        <td><span class="badge bg-dark text-uppercase font-monospace">{{ $complaint->complaint_id }}</span></td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $complaint->customer->name ?? 'N/A' }}</div>
                            <div class="small text-muted">{{ $complaint->customer->email ?? '' }}</div>
                        </td>
                        <td style="max-width: 280px;">
                            <div class="fw-semibold text-dark">{{ $complaint->subject }}</div>
                            <div class="small text-muted">{{ $complaint->message }}</div>
                            @if($complaint->admin_reply)
                                <div class="small mt-2 p-2 rounded bg-light border-start border-success border-3">
                                    <i class="fas fa-reply text-success me-1"></i><strong>Reply:</strong> {{ $complaint->admin_reply }}
                                </div>
                            @endif
                        </td>
                        <td class="small">
                            @if($complaint->booking)
                                <span class="font-monospace text-primary">{{ $complaint->booking->booking_id }}</span>
                            @else
                                <span class="text-muted fst-italic">General</span>
                            @endif
                        </td>
                        <td>
                            @if($complaint->isResolved())
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Resolved</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Open</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $complaint->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            @if($complaint->isOpen())
                                <button type="button" class="btn btn-sm btn-primary fw-bold shadow-sm open-reply-modal"
                                        data-action="{{ route('admin.complaints.reply', $complaint->id) }}"
                                        data-id="{{ $complaint->complaint_id }}"
                                        data-subject="{{ $complaint->subject }}"
                                        data-message="{{ $complaint->message }}"
                                        data-customer="— {{ $complaint->customer->name ?? 'Customer' }}">
                                    <i class="fas fa-reply me-1"></i> Reply & Resolve
                                </button>
                            @else
                                <span class="badge bg-light text-success border border-success py-2 px-3"><i class="fas fa-check-double me-1"></i>Handled</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-comment-slash fa-3x mb-3 d-block text-secondary"></i>
                            No complaints have been filed.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Reusable Reply Modal (Clean root placement outside table/card) -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="replyModalTitle"><i class="fas fa-reply me-2"></i>Respond to Complaint</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="replyComplaintForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div class="mb-3 p-3 rounded bg-light border">
                        <div class="fw-bold text-dark" id="modalSubject"></div>
                        <div class="small text-muted mt-1" id="modalMessage"></div>
                        <div class="small text-secondary mt-1 fw-semibold" id="modalCustomer"></div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Your Response <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="admin_reply" id="adminReplyInput" rows="4" placeholder="Write your official response..." required></textarea>
                    </div>
                    <p class="small text-muted mb-0"><i class="fas fa-info-circle me-1"></i>Submitting a reply marks this complaint as <strong>resolved</strong>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fas fa-check me-1"></i> Submit & Resolve</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $(document).on('click', '.open-reply-modal', function() {
        const action = $(this).data('action');
        const id = $(this).data('id');
        const subject = $(this).data('subject');
        const message = $(this).data('message');
        const customer = $(this).data('customer');

        $('#replyComplaintForm').attr('action', action);
        $('#replyModalTitle').html('<i class="fas fa-reply me-2"></i>Respond to ' + id);
        $('#modalSubject').text(subject);
        $('#modalMessage').text(message);
        $('#modalCustomer').text(customer);
        $('#adminReplyInput').val('');

        const modal = new bootstrap.Modal(document.getElementById('replyModal'));
        modal.show();
    });
});
</script>
@endpush

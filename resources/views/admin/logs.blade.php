@extends('layouts.app')

@section('title', 'Admin — System Activity Logs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-history me-2 text-danger"></i>System Activity Audit Logs</h2>
        <p class="text-muted mb-0">Track real-time actions logged across registration, tasks, and bookings (Module 5 Integration).</p>
    </div>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Logged Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($log->user)
                                <strong class="text-dark"><i class="fas fa-user me-1 text-primary"></i>{{ $log->user->name }}</strong>
                                <small class="text-muted d-block">({{ ucfirst($log->user->role) }})</small>
                            @else
                                <span class="text-muted">System / Guest</span>
                            @endif
                        </td>
                        <td><span class="badge bg-primary">{{ $log->action }}</span></td>
                        <td>{{ $log->description }}</td>
                        <td class="small font-monospace text-muted">{{ $log->ip_address ?: '127.0.0.1' }}</td>
                        <td class="small text-muted">{{ $log->created_at->format('M d, Y - h:i:s A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No activity logs recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

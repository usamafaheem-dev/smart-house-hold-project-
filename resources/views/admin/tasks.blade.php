@extends('layouts.app')

@section('title', 'Household Tasks Monitoring')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-tasks me-2 text-primary"></i>Customer Household Tasks Monitoring</h2>
        <p class="text-muted mb-0">System-wide overview of all household tasks, reminders, and chores created by customers.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary fw-bold">
        <i class="fas fa-arrow-left me-1"></i> Dashboard
    </a>
</div>

<!-- Metrics Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 bg-primary bg-opacity-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Total Tasks</span>
                    <h3 class="fw-bold text-primary mb-0 mt-1">{{ number_format($totalTasks) }}</h3>
                </div>
                <div class="bg-primary text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-clipboard-list fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 bg-warning bg-opacity-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Pending Tasks</span>
                    <h3 class="fw-bold text-warning mb-0 mt-1">{{ number_format($pendingTasks) }}</h3>
                </div>
                <div class="bg-warning text-dark rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-clock fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 bg-success bg-opacity-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Completed Tasks</span>
                    <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($completedTasks) }}</h3>
                </div>
                <div class="bg-success text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-check-circle fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 border-0 bg-danger bg-opacity-10">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">High Priority (Pending)</span>
                    <h3 class="fw-bold text-danger mb-0 mt-1">{{ number_format($highPriorityTasks) }}</h3>
                </div>
                <div class="bg-danger text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-exclamation-triangle fs-5"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="card card-custom p-3 mb-4">
    <form method="GET" action="{{ route('admin.tasks') }}" class="row g-2 align-items-center">
        <div class="col-auto">
            <label class="fw-semibold text-muted small"><i class="fas fa-filter me-1"></i>Filter by Status:</label>
        </div>
        <div class="col-auto">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $statusFilter === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div class="col-auto ms-md-3">
            <label class="fw-semibold text-muted small"><i class="fas fa-flag me-1"></i>Priority:</label>
        </div>
        <div class="col-auto">
            <select name="priority" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Priorities</option>
                <option value="high" {{ $priorityFilter === 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ $priorityFilter === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ $priorityFilter === 'low' ? 'selected' : '' }}>Low</option>
            </select>
        </div>

        @if($statusFilter || $priorityFilter)
            <div class="col-auto">
                <a href="{{ route('admin.tasks') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </a>
            </div>
        @endif
    </form>
</div>

<!-- Tasks Table Card -->
<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Task Title & Details</th>
                    <th>Customer</th>
                    <th>Scheduled Date & Time</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $index => $task)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong class="text-dark d-block">{{ $task->task_title }}</strong>
                            <small class="text-muted">{{ Str::limit($task->description, 60, '...') ?: 'No description provided' }}</small>
                        </td>
                        <td>
                            @if($task->customer)
                                <div><strong class="text-dark">{{ $task->customer->name }}</strong></div>
                                <small class="text-muted">{{ $task->customer->email }}</small>
                            @else
                                <span class="text-muted fst-italic">Unknown user</span>
                            @endif
                        </td>
                        <td>
                            <div><i class="far fa-calendar-alt text-primary me-1"></i>{{ \Carbon\Carbon::parse($task->reminder_date)->format('M d, Y') }}</div>
                            <small class="text-muted"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($task->reminder_time)->format('h:i A') }}</small>
                        </td>
                        <td>
                            @if($task->priority === 'high')
                                <span class="badge bg-danger">High</span>
                            @elseif($task->priority === 'medium')
                                <span class="badge bg-warning text-dark">Medium</span>
                            @else
                                <span class="badge bg-secondary">Low</span>
                            @endif
                        </td>
                        <td>
                            @if($task->status === 'completed')
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Completed</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>Pending</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $task->created_at->format('M d, Y') }}</small>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 d-block text-secondary"></i>
                            No household task records found matching criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

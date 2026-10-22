<?php $__env->startSection('title', 'Admin — Payments & Analytics'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge px-2.5 py-1 text-uppercase fw-semibold" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-size: 0.72rem; letter-spacing: 0.05em; border-radius: 6px;">
                Financial Intelligence
            </span>
        </div>
        <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">
            <i class="fas fa-chart-line text-primary me-2"></i>Payments & Analytics Overview
        </h2>
        <p class="text-muted small mb-0">Track real-time transactions, revenue breakdown by category, and weekly growth performance.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.payments')); ?>" class="btn btn-outline-primary fw-bold px-3 py-2 shadow-sm rounded-3">
            <i class="fas fa-receipt me-1"></i> Full Payment Ledger
        </a>
    </div>
</div>

<!-- Modern Stat Cards -->
<div class="row g-3 mb-4">
    <!-- Paid Revenue -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom h-100 border-0 shadow-sm bg-white" style="border-radius: 14px; border-left: 5px solid #10b981 !important; padding: 1.25rem 1.4rem !important;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.04em;">Revenue (Paid)</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fas fa-wallet fs-6"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">Rs. <?php echo e(number_format($totalRevenue, 0)); ?></div>
            <div class="small text-success d-flex align-items-center gap-1 fw-semibold">
                <i class="fas fa-check-circle"></i> Cleared & Received
            </div>
        </div>
    </div>

    <!-- Outstanding -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom h-100 border-0 shadow-sm bg-white" style="border-radius: 14px; border-left: 5px solid #f59e0b !important; padding: 1.25rem 1.4rem !important;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.04em;">Outstanding</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                    <i class="fas fa-hourglass-half fs-6"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">Rs. <?php echo e(number_format($pendingRevenue, 0)); ?></div>
            <div class="small text-warning d-flex align-items-center gap-1 fw-semibold">
                <i class="fas fa-clock"></i> Unpaid & Pending
            </div>
        </div>
    </div>

    <!-- Total Bookings -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom h-100 border-0 shadow-sm bg-white" style="border-radius: 14px; border-left: 5px solid #4f46e5 !important; padding: 1.25rem 1.4rem !important;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.04em;">Total Bookings</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(79, 70, 229, 0.12); color: #4f46e5;">
                    <i class="fas fa-calendar-check fs-6"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark mb-1" style="letter-spacing: -0.02em;"><?php echo e($totalBookings); ?></div>
            <div class="small text-muted">
                <span class="text-success fw-semibold"><?php echo e($completedBookings); ?> completed</span> · <?php echo e($pendingBookings); ?> pending
            </div>
        </div>
    </div>

    <!-- Providers -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom h-100 border-0 shadow-sm bg-white" style="border-radius: 14px; border-left: 5px solid #06b6d4 !important; padding: 1.25rem 1.4rem !important;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.04em;">Active Providers</span>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(6, 182, 212, 0.12); color: #0891b2;">
                    <i class="fas fa-user-shield fs-6"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark mb-1" style="letter-spacing: -0.02em;"><?php echo e($totalProviders); ?></div>
            <div class="small text-muted">
                <?php if($pendingProviders > 0): ?>
                    <span class="text-danger fw-semibold"><?php echo e($pendingProviders); ?> awaiting approval</span>
                <?php else: ?>
                    <span class="text-muted">All providers approved</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
    <!-- Left Chart: Revenue by Category (Doughnut + Breakdown) -->
    <div class="col-lg-5">
        <div class="card card-custom p-4 h-100 border-0 shadow-sm bg-white" style="border-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(79, 70, 229, 0.1); color: #4f46e5;">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0 fs-6">Revenue by Category</h5>
                        <small class="text-muted">Earnings split across specialties</small>
                    </div>
                </div>
            </div>

            <?php if($revenueByCategory->isEmpty()): ?>
                <div class="text-center text-muted py-5">
                    <i class="fas fa-chart-pie fa-3x mb-3 d-block text-secondary opacity-50"></i>
                    No paid transactions recorded yet.
                </div>
            <?php else: ?>
                <!-- Doughnut Canvas with Center Stat -->
                <div class="position-relative my-2 d-flex justify-content-center align-items-center" style="height: 220px;">
                    <canvas id="categoryChart"></canvas>
                    <div class="position-absolute text-center" style="pointer-events: none; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 0.68rem; letter-spacing: 0.05em;">Total Paid</span>
                        <div class="fw-bold text-dark fs-5 mb-0">Rs. <?php echo e(number_format($totalRevenue, 0)); ?></div>
                    </div>
                </div>

                <!-- Sleek Category Breakdown Progress List -->
                <div class="mt-3 pt-3 border-top">
                    <?php
                        $palette = ['#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'];
                        $totalCatSum = $revenueByCategory->sum() ?: 1;
                        $catIdx = 0;
                    ?>
                    <div class="d-flex flex-column gap-2.5">
                        <?php $__currentLoopData = $revenueByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catName => $catAmount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $color = $palette[$catIdx % count($palette)];
                                $percent = round(($catAmount / $totalCatSum) * 100);
                                $catIdx++;
                            ?>
                            <div>
                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                    <span class="d-flex align-items-center gap-2 fw-semibold text-dark">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: <?php echo e($color); ?>; display: inline-block;"></span>
                                        <?php echo e($catName); ?>

                                    </span>
                                    <span class="text-muted fw-bold">
                                        Rs. <?php echo e(number_format($catAmount, 0)); ?>

                                        <span class="badge bg-light text-muted border ms-1" style="font-size: 0.7rem;"><?php echo e($percent); ?>%</span>
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #f1f5f9; border-radius: 10px;">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo e($percent); ?>%; background-color: <?php echo e($color); ?>; border-radius: 10px;"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Chart: Paid Revenue (Last 7 Days) -->
    <div class="col-lg-7">
        <div class="card card-custom p-4 h-100 border-0 shadow-sm bg-white" style="border-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-chart-area"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0 fs-6">Paid Revenue — Last 7 Days</h5>
                        <small class="text-muted">Daily incoming transaction volume</small>
                    </div>
                </div>
                <div class="badge px-3 py-1.5 rounded-pill fw-bold" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-size: 0.78rem;">
                    7-Day Total: Rs. <?php echo e(number_format($paymentsByDayData->sum(), 0)); ?>

                </div>
            </div>

            <!-- Line Chart Area -->
            <div class="position-relative" style="height: 260px;">
                <canvas id="dailyChart"></canvas>
            </div>

            <!-- Chart Quick Insights Footer -->
            <div class="row g-2 mt-3 pt-3 border-top text-center">
                <div class="col-4">
                    <div class="text-muted small text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">Peak Day</div>
                    <div class="fw-bold text-dark small">Rs. <?php echo e(number_format($paymentsByDayData->max(), 0)); ?></div>
                </div>
                <div class="col-4 border-start border-end">
                    <div class="text-muted small text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">Daily Avg</div>
                    <div class="fw-bold text-dark small">Rs. <?php echo e(number_format($paymentsByDayData->avg(), 0)); ?></div>
                </div>
                <div class="col-4">
                    <div class="text-muted small text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">Recorded Invoices</div>
                    <div class="fw-bold text-dark small"><?php echo e($recentPayments->count()); ?> Invoices</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Payments Table Card -->
<div class="card card-custom p-4 border-0 shadow-sm bg-white" style="border-radius: 16px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(79, 70, 229, 0.1); color: #4f46e5;">
                <i class="fas fa-receipt"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0 fs-6">Recent Payments Ledger</h5>
                <small class="text-muted">Latest settled customer transactions</small>
            </div>
        </div>
        <a href="<?php echo e(route('admin.payments')); ?>" class="btn btn-sm btn-light fw-bold border rounded-pill px-3">
            View All <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase small text-muted" style="font-size: 0.75rem;">
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Service Category</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Paid At</th>
                    <th class="text-end">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <span class="badge bg-dark bg-opacity-75 font-monospace px-2 py-1"><?php echo e($payment->invoice_number); ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white small" style="width: 30px; height: 30px; background: var(--primary-gradient);">
                                    <?php echo e(strtoupper(substr($payment->booking->customer->name ?? 'C', 0, 1))); ?>

                                </div>
                                <span class="fw-semibold text-dark"><?php echo e($payment->booking->customer->name ?? 'N/A'); ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border"><?php echo e($payment->booking->category->name ?? 'General'); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 text-uppercase px-2">
                                <i class="fas fa-credit-card me-1"></i> <?php echo e($payment->method); ?>

                            </span>
                        </td>
                        <td>
                            <span class="text-success fw-bold fs-6">Rs. <?php echo e(number_format($payment->amount, 0)); ?></span>
                        </td>
                        <td class="small text-muted">
                            <?php echo e($payment->paid_at ? $payment->paid_at->format('M d, Y · h:i A') : '-'); ?>

                        </td>
                        <td class="text-end">
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1">
                                <i class="fas fa-check-circle me-1"></i> Paid
                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No payments recorded yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
$(document).ready(function() {
    // Modern curated color palette
    const modernPalette = ['#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'];

    // 1. Doughnut Chart (Revenue by Category)
    <?php if(!$revenueByCategory->isEmpty()): ?>
    const catLabels = <?php echo json_encode($revenueByCategory->keys(), 15, 512) ?>;
    const catData = <?php echo json_encode($revenueByCategory->values(), 15, 512) ?>.map(Number);
    const catCtx = document.getElementById('categoryChart').getContext('2d');

    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{
                data: catData,
                backgroundColor: modernPalette,
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 6,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: {
                    display: false // Using our custom sleek progress breakdown below
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const val = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = Math.round((val / total) * 100);
                            return ` Rs. ${val.toLocaleString()} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });
    <?php endif; ?>

    // 2. Line Chart (Paid Revenue — Last 7 Days)
    const dayLabels = <?php echo json_encode($paymentsByDayLabels, 15, 512) ?>;
    const dayData = <?php echo json_encode($paymentsByDayData, 15, 512) ?>.map(Number);
    const dailyCanvas = document.getElementById('dailyChart');
    const dailyCtx = dailyCanvas.getContext('2d');

    // Create elegant smooth vertical gradient
    const gradient = dailyCtx.createLinearGradient(0, 0, 0, 260);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.28)');
    gradient.addColorStop(0.7, 'rgba(16, 185, 129, 0.05)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dayLabels,
            datasets: [{
                label: 'Paid Revenue',
                data: dayData,
                borderColor: '#10b981',
                borderWidth: 2.8,
                backgroundColor: gradient,
                fill: true,
                tension: 0.38,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2.5,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: Rs. ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(226, 232, 240, 0.7)',
                        borderDash: [4, 4]
                    },
                    ticks: {
                        color: '#64748b',
                        font: { size: 11 },
                        callback: function(val) {
                            return 'Rs. ' + val.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#64748b',
                        font: { size: 11, weight: '500' }
                    }
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/admin/analytics.blade.php ENDPATH**/ ?>
<nav class="navbar navbar-expand-xl navbar-light navbar-glass sticky-top" style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
    <div class="container-fluid px-3 px-xl-4">
        <a class="navbar-brand d-flex align-items-center me-2 me-xl-3" href="<?php echo e(url('/')); ?>">
            <img src="<?php echo e(asset('images/logo_icon.jpg')); ?>" alt="Smart Household" height="32" class="me-2" style="object-fit: contain; mix-blend-mode: multiply; border-radius: 8px;">
            <span class="fw-bold d-none d-sm-inline" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 1.35rem; letter-spacing: -0.5px;">Smart Household</span>
            <span class="fw-bold d-inline d-sm-none" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 1.2rem;">SHS</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse mt-2 mt-xl-0" id="navbarNav">
            <ul class="navbar-nav mx-auto align-items-center">
                <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <?php if(auth()->user()->isCustomer()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('tasks.*') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('tasks.index')); ?>">
                                <i class="fas fa-tasks me-1"></i>Household Tasks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('bookings.browse') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('bookings.browse')); ?>">
                                <i class="fas fa-concierge-bell me-1"></i>Browse Services
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('bookings.index') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('bookings.index')); ?>">
                                <i class="fas fa-calendar-check me-1"></i>My Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('payments.*') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('payments.index')); ?>">
                                <i class="fas fa-file-invoice-dollar me-1"></i>Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('complaints.*') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('complaints.index')); ?>">
                                <i class="fas fa-comment-dots me-1"></i>Complaints
                            </a>
                        </li>
                    <?php elseif(auth()->user()->isProvider()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('bookings.index') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('bookings.index')); ?>">
                                <i class="fas fa-tools me-1"></i>Service Requests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('provider.availability') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('provider.availability')); ?>">
                                <i class="fas fa-business-time me-1"></i>Availability
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('provider.earnings') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('provider.earnings')); ?>">
                                <i class="fas fa-wallet me-1"></i>Earnings
                            </a>
                        </li>
                    <?php elseif(auth()->user()->isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.users') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('admin.users')); ?>">
                                <i class="fas fa-users-cog me-1"></i>Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.bookings') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('admin.bookings')); ?>">
                                <i class="fas fa-clipboard-list me-1"></i>Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.tasks') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('admin.tasks')); ?>">
                                <i class="fas fa-tasks me-1"></i>Task Records
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.categories') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('admin.categories')); ?>">
                                <i class="fas fa-tags me-1"></i>Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.complaints') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('admin.complaints')); ?>">
                                <i class="fas fa-headset me-1"></i>Complaints
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.payments') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('admin.payments')); ?>">
                                <i class="fas fa-money-check-alt me-1"></i>Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.analytics') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('admin.analytics')); ?>">
                                <i class="fas fa-chart-line me-1"></i>Analytics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.logs') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('admin.logs')); ?>">
                                <i class="fas fa-history me-1"></i>Logs
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav align-items-center">
                <?php if(auth()->guard()->guest()): ?>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold <?php echo e(request()->routeIs('login') ? 'text-primary' : ''); ?>" href="<?php echo e(route('login')); ?>">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-primary px-4 py-2 shadow-sm rounded-pill fw-bold" href="<?php echo e(route('register')); ?>">
                            <i class="fas fa-user-plus me-1"></i> Register Free
                        </a>
                    </li>
                <?php else: ?>
                    <?php if(auth()->user()->isCustomer()): ?>
                        <?php
                            $todayStr = \Carbon\Carbon::today()->toDateString();
                            $navDueTasks = \App\Models\HouseholdTask::where('customer_id', auth()->id())
                                ->where('status', 'pending')
                                ->where('reminder_date', '<=', $todayStr)
                                ->get();
                        ?>
                        <li class="nav-item dropdown me-3 position-relative">
                            <button class="btn btn-sm btn-light position-relative rounded-circle d-flex align-items-center justify-content-center p-0 border shadow-none" 
                                    id="reminderDropdown" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false" 
                                    title="Routine Reminders"
                                    style="width: 36px; height: 36px; background-color: #ffffff; border-color: #e2e8f0; color: #4b5563;">
                                <i class="fas fa-bell" style="font-size: 0.95rem;"></i>
                                <?php if($navDueTasks->count() > 0): ?>
                                    <span class="position-absolute rounded-pill bg-danger text-white d-flex align-items-center justify-content-center shadow-sm" 
                                          style="top: -2px; right: -2px; width: 17px; height: 17px; font-size: 0.62rem; font-weight: 700; border: 2px solid #ffffff; line-height: 1;">
                                        <?php echo e($navDueTasks->count()); ?>

                                    </span>
                                <?php endif; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg p-2 mt-2 border-0" style="width: 320px;" aria-labelledby="reminderDropdown">
                                <li class="d-flex justify-content-between align-items-center px-2 py-1 mb-2 border-bottom">
                                    <span class="fw-bold text-dark small"><i class="fas fa-bell text-warning me-1"></i> Reminders Due Today</span>
                                    <span class="badge bg-danger rounded-pill"><?php echo e($navDueTasks->count()); ?></span>
                                </li>
                                <?php $__empty_1 = true; $__currentLoopData = $navDueTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <li class="p-2 border-bottom rounded bg-light bg-opacity-75 mb-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <strong class="text-dark small"><?php echo e($due->task_title); ?></strong>
                                            <span class="badge <?php echo e($due->priority === 'high' ? 'bg-danger' : 'bg-warning text-dark'); ?>" style="font-size: 0.65rem;"><?php echo e(ucfirst($due->priority)); ?></span>
                                        </div>
                                        <div class="text-muted mt-1 d-flex justify-content-between align-items-center" style="font-size: 0.75rem;">
                                            <span><i class="far fa-clock text-primary me-1"></i><?php echo e(\Carbon\Carbon::parse($due->reminder_time)->format('h:i A')); ?></span>
                                            <form method="POST" action="<?php echo e(route('tasks.toggle-status', $due->id)); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-success py-0 px-2 fw-bold" style="font-size: 0.7rem;" title="Mark Done">
                                                    ✓ Done
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <li class="text-center py-3 text-muted small">
                                        <i class="fas fa-check-circle text-success me-1"></i> No routine tasks due right now!
                                    </li>
                                <?php endif; ?>
                                <li class="text-center pt-2">
                                    <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-sm btn-outline-primary w-100 py-1 fw-semibold" style="font-size: 0.78rem;">
                                        Manage All Tasks
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center py-1 px-3 border rounded-pill bg-light bg-opacity-50" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle fa-lg me-2 text-primary"></i> 
                            <span class="fw-bold me-1"><?php echo e(auth()->user()->name); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><h6 class="dropdown-header">Signed in as: <?php echo e(auth()->user()->email); ?></h6></li>
                            <li>
                                <a class="dropdown-item <?php echo e(request()->routeIs('profile.edit') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('profile.edit')); ?>">
                                    <i class="fas fa-id-card me-2 text-primary"></i>My Profile
                                </a>
                            </li>
                            <?php if(auth()->user()->isProvider()): ?>
                                <li>
                                    <a class="dropdown-item <?php echo e(request()->routeIs('provider.availability') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('provider.availability')); ?>">
                                        <i class="fas fa-business-time me-2 text-primary"></i>Skills & Availability
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH F:\Web dev Project work\University_projects\hosue_hold_project_complelte\Smart_Household_and_Home_Services_Management_System\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>
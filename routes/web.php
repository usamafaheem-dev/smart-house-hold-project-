<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Smart Household Management System Prototype
| Modules 1 to 5 Complete Implementation
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Guest Routes (Module 1)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Authenticated Routes (Modules 1 - 5)
Route::middleware('auth')->group(function () {
    // Module 1: Dashboard, Profile & Logout
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Module 2: Household Task Management (Customer Access)
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/tasks/{id}/toggle-status', [TaskController::class, 'toggleStatus'])->name('tasks.toggle-status');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Module 3: Home Service Booking
    Route::get('/services/browse', [BookingController::class, 'browse'])->name('bookings.browse');
    Route::get('/services/book/{category}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{id}/toggle-status', [BookingController::class, 'toggleStatus'])->name('bookings.toggle-status');
    Route::post('/bookings/{id}/accept', [BookingController::class, 'acceptBooking'])->name('bookings.accept');
    Route::post('/bookings/{id}/reject', [BookingController::class, 'rejectBooking'])->name('bookings.reject');

    // Module 6: Payments & Invoices (Customer / shared)
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}/invoice', [PaymentController::class, 'invoice'])->name('payments.invoice');
    Route::post('/payments/{id}/pay', [PaymentController::class, 'pay'])->name('payments.pay');

    // Module 7: Ratings & Reviews (Customer -> Provider)
    Route::post('/bookings/{id}/review', [ReviewController::class, 'store'])->name('reviews.store');

    // Module 8: Complaints (Customer submit)
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');

    // Module 9: Provider — Availability & Earnings
    Route::middleware('role:provider')->group(function () {
        Route::get('/provider/availability', [ProviderController::class, 'availability'])->name('provider.availability');
        Route::post('/provider/availability', [ProviderController::class, 'updateAvailability'])->name('provider.availability.update');
        Route::get('/provider/earnings', [ProviderController::class, 'earnings'])->name('provider.earnings');
    });

    // Module 4 & 5: Admin Monitoring & System Logs
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/admin/users/{id}/approval', [AdminController::class, 'toggleApproval'])->name('admin.users.approval');
        Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
        Route::get('/admin/tasks', [AdminController::class, 'tasks'])->name('admin.tasks');
        Route::post('/admin/bookings/{id}/assign', [AdminController::class, 'assignProvider'])->name('admin.bookings.assign');
        Route::get('/admin/logs', [AdminController::class, 'logs'])->name('admin.logs');

        // Service Category CRUD
        Route::get('/admin/categories', [ServiceCategoryController::class, 'index'])->name('admin.categories');
        Route::post('/admin/categories', [ServiceCategoryController::class, 'store'])->name('admin.categories.store');
        Route::put('/admin/categories/{id}', [ServiceCategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/admin/categories/{id}', [ServiceCategoryController::class, 'destroy'])->name('admin.categories.destroy');

        // Complaint Management
        Route::get('/admin/complaints', [ComplaintController::class, 'adminIndex'])->name('admin.complaints');
        Route::post('/admin/complaints/{id}/reply', [ComplaintController::class, 'reply'])->name('admin.complaints.reply');

        // Payments & Analytics
        Route::get('/admin/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
        Route::get('/admin/payments', [AdminController::class, 'payments'])->name('admin.payments');
    });
});

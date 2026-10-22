<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\HouseholdTask;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display all registered users (Customers, Service Providers, Admins).
     */
    public function users(Request $request)
    {
        $roleFilter = $request->query('role');

        $query = User::orderBy('created_at', 'desc');

        if ($roleFilter && in_array($roleFilter, ['customer', 'provider', 'admin'])) {
            $query->where('role', $roleFilter);
        }

        $users = $query->get();

        return view('admin.users', compact('users', 'roleFilter'));
    }

    /**
     * Display all service bookings system-wide.
     */
    public function bookings()
    {
        $bookings = Booking::with(['customer', 'provider', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $providers = User::where('role', 'provider')->orderBy('name')->get();

        return view('admin.bookings', compact('bookings', 'providers'));
    }

    /**
     * Display system activity logs (Module 5).
     */
    public function logs()
    {
        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return view('admin.logs', compact('logs'));
    }

    /**
     * Assign an unassigned booking to a specific provider (Admin Fallback).
     */
    public function assignProvider(Request $request, $id)
    {
        $request->validate([
            'provider_id' => 'required|exists:users,id'
        ]);

        $booking = Booking::findOrFail($id);

        if ($booking->status === 'completed') {
            return back()->with('error', 'Completed bookings cannot be reassigned.');
        }

        $provider = User::findOrFail($request->provider_id);
        if (!$provider->isProvider()) {
            return back()->with('error', 'Selected user is not a service provider.');
        }

        $isReassign = $booking->provider_id !== null;
        $booking->provider_id = $provider->id;
        $booking->save();

        $action = $isReassign ? 'Admin Manual Reassignment' : 'Admin Manual Assignment';
        ActivityLog::record(auth()->id(), $action, "Assigned service booking #{$booking->booking_id} to Provider: {$provider->name}");

        $verb = $isReassign ? 'reassigned' : 'assigned';
        return back()->with('success', "Booking successfully {$verb} to {$provider->name}.");
    }

    /**
     * Approve or revoke a service provider's vetting status.
     */
    public function toggleApproval(Request $request, $id)
    {
        $provider = User::findOrFail($id);

        if (!$provider->isProvider()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Only service providers can be approved.'], 422);
            }
            return back()->with('error', 'Only service providers can be approved.');
        }

        $provider->is_approved = !$provider->is_approved;
        $provider->save();

        $action = $provider->is_approved ? 'Approved' : 'Revoked';
        ActivityLog::record(auth()->id(), 'Provider Approval', "{$action} provider account: {$provider->name}");

        $verb = $provider->is_approved ? 'approved' : 'revoked approval for';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_approved' => $provider->is_approved,
                'provider_id' => $provider->id,
                'provider_name' => $provider->name,
                'message' => "You have {$verb} provider {$provider->name}."
            ]);
        }

        return back()->with('success', "You have {$verb} provider {$provider->name}.");
    }

    /**
     * Payments & revenue analytics dashboard (Chart.js data).
     */
    public function analytics()
    {
        $totalRevenue = (float) Payment::where('status', 'paid')->sum('amount');
        $pendingRevenue = (float) Payment::where('status', 'unpaid')->sum('amount');

        $totalBookings = Booking::count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();

        $totalCustomers = User::where('role', 'customer')->count();
        $totalProviders = User::where('role', 'provider')->count();
        $pendingProviders = User::where('role', 'provider')->where('is_approved', false)->count();

        // Revenue grouped by service category (paid invoices only) — for the doughnut chart.
        $revenueByCategory = Payment::where('payments.status', 'paid')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('service_categories', 'bookings.category_id', '=', 'service_categories.id')
            ->selectRaw('service_categories.name as name, SUM(payments.amount) as total')
            ->groupBy('service_categories.name')
            ->pluck('total', 'name');

        // Paid revenue over the last 7 days — for the line chart.
        $series = collect();
        for ($i = 6; $i >= 0; $i--) {
            $series->put(now()->subDays($i)->toDateString(), 0.0);
        }
        $rawByDay = Payment::where('status', 'paid')
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(paid_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day');
        foreach ($rawByDay as $day => $total) {
            $series->put($day, (float) $total);
        }
        $paymentsByDayLabels = $series->keys()
            ->map(fn ($d) => \Carbon\Carbon::parse($d)->format('M d'))
            ->values();
        $paymentsByDayData = $series->values();

        $recentPayments = Payment::with(['booking.customer', 'booking.category'])
            ->where('status', 'paid')
            ->orderBy('paid_at', 'desc')
            ->limit(8)
            ->get();

        return view('admin.analytics', compact(
            'totalRevenue',
            'pendingRevenue',
            'totalBookings',
            'completedBookings',
            'pendingBookings',
            'totalCustomers',
            'totalProviders',
            'pendingProviders',
            'revenueByCategory',
            'paymentsByDayLabels',
            'paymentsByDayData',
            'recentPayments'
        ));
    }

    /**
     * Full payments ledger.
     */
    public function payments()
    {
        $payments = Payment::with(['booking.customer', 'booking.category', 'booking.provider'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPaid = (float) Payment::where('status', 'paid')->sum('amount');
        $totalUnpaid = (float) Payment::where('status', 'unpaid')->sum('amount');

        return view('admin.payments', compact('payments', 'totalPaid', 'totalUnpaid'));
    }

    /**
     * Display all household task records system-wide (Admin monitoring).
     */
    public function tasks(Request $request)
    {
        $statusFilter = $request->query('status');
        $priorityFilter = $request->query('priority');

        $query = HouseholdTask::with('customer')->orderBy('created_at', 'desc');

        if ($statusFilter && in_array($statusFilter, ['pending', 'completed'])) {
            $query->where('status', $statusFilter);
        }

        if ($priorityFilter && in_array($priorityFilter, ['low', 'medium', 'high'])) {
            $query->where('priority', $priorityFilter);
        }

        $tasks = $query->get();

        $totalTasks = HouseholdTask::count();
        $pendingTasks = HouseholdTask::where('status', 'pending')->count();
        $completedTasks = HouseholdTask::where('status', 'completed')->count();
        $highPriorityTasks = HouseholdTask::where('priority', 'high')->where('status', 'pending')->count();

        return view('admin.tasks', compact(
            'tasks',
            'statusFilter',
            'priorityFilter',
            'totalTasks',
            'pendingTasks',
            'completedTasks',
            'highPriorityTasks'
        ));
    }
}

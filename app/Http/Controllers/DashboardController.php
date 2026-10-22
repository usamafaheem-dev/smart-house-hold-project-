<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Complaint;
use App\Models\HouseholdTask;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the role-specific dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $pendingProvidersCount = User::where('role', 'provider')->where('is_approved', false)->count();
            $openComplaintsCount = Complaint::where('status', 'open')->count();
            $totalTasksCount = HouseholdTask::count();
            $pendingBookingsCount = Booking::where('status', 'pending')->count();

            return view('dashboard.admin', compact(
                'user',
                'pendingProvidersCount',
                'openComplaintsCount',
                'totalTasksCount',
                'pendingBookingsCount'
            ));
        } elseif ($user->isProvider()) {
            if (!$user->is_approved) {
                return view('dashboard.provider', [
                    'user' => $user,
                    'bookings' => collect(),
                    'pendingApproval' => true,
                ]);
            }

            $bookings = Booking::with(['category', 'customer'])
                ->where('provider_id', $user->id)
                ->orWhereNull('provider_id')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('dashboard.provider', [
                'user' => $user,
                'bookings' => $bookings,
                'pendingApproval' => false,
            ]);
        } else {
            // Customer Dashboard: compute task reminders and status counts
            $today = Carbon::today()->toDateString();

            // Overdue or due today pending tasks
            $dueReminders = HouseholdTask::where('customer_id', $user->id)
                ->where('status', 'pending')
                ->where('reminder_date', '<=', $today)
                ->orderBy('reminder_date', 'asc')
                ->orderBy('reminder_time', 'asc')
                ->get();

            $upcomingTasksCount = HouseholdTask::where('customer_id', $user->id)
                ->where('status', 'pending')
                ->where('reminder_date', '>', $today)
                ->count();

            $activeBookingsCount = Booking::where('customer_id', $user->id)
                ->where('status', 'pending')
                ->count();

            $unpaidInvoicesCount = Payment::whereHas('booking', function ($q) use ($user) {
                    $q->where('customer_id', $user->id);
                })
                ->where('status', 'unpaid')
                ->count();

            return view('dashboard.customer', compact(
                'user',
                'dueReminders',
                'upcomingTasksCount',
                'activeBookingsCount',
                'unpaidInvoicesCount'
            ));
        }
    }
}

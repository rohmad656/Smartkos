<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Bill;
use App\Models\Complaint;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'rooms' => Room::count(),
            'rooms_available' => Room::where('is_available', true)->count(),
            'tenants' => Tenant::where('status', 'active')->count(),
            'bills_pending' => Bill::whereIn('status', ['pending', 'overdue', 'partial'])->count(),
            'complaints_open' => Complaint::whereIn('status', ['pending', 'in_progress'])->count(),
            'revenue_month' => Bill::where('status', 'paid')
                ->whereMonth('updated_at', now()->month)
                ->sum('paid_amount'),
        ];

        $recentBills = Bill::with(['tenant.room'])->latest()->take(8)->get();
        $recentComplaints = Complaint::with(['tenant', 'room'])->latest()->take(6)->get();
        $announcements = Announcement::active()->latest()->take(4)->get();
        $usersCount = User::count();

        return view('admin.dashboard', compact('stats', 'recentBills', 'recentComplaints', 'announcements', 'usersCount'));
    }
}

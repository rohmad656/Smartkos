<?php

namespace App\Http\Controllers\Caretaker;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Room;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'rooms_available' => Room::where('is_available', true)->count(),
            'complaints_open' => Complaint::whereIn('status', ['pending', 'in_progress'])->count(),
        ];

        $complaints = Complaint::with(['tenant.room', 'room'])->whereIn('status', ['pending', 'in_progress'])->latest()->get();

        return view('caretaker.dashboard', compact('stats', 'complaints'));
    }
}

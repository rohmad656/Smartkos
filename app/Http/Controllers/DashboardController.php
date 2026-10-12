<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        return match ($user->role) {
            'super_admin', 'admin' => redirect()->route('admin.dashboard'),
            'caretaker' => redirect()->route('caretaker.dashboard'),
            default => redirect()->route('tenant.dashboard'),
        };
    }
}

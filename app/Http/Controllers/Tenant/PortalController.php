<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Complaint;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;
        if (! $tenant) {
            return view('tenant.setup');
        }

        $bills = Bill::where('tenant_id', $tenant->id)->latest()->paginate(5);
        $complaints = Complaint::where('tenant_id', $tenant->id)->latest()->paginate(5);
        
        return view('tenant.dashboard', compact('tenant', 'bills', 'complaints'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Tenant;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $q = Bill::with(['tenant.room', 'tenant.user']);

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }
        if ($request->filled('tenant_id')) {
            $q->where('tenant_id', $request->integer('tenant_id'));
        }

        $bills = $q->latest()->paginate(20)->withQueryString();
        $tenants = Tenant::orderBy('full_name')->get();
        return view('admin.bills.index', compact('bills', 'tenants'));
    }

    public function create()
    {
        $tenants = Tenant::with('room')->orderBy('full_name')->get();
        return view('admin.bills.create', compact('tenants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'exists:tenants,id'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'between:2020,2035'],
            'room_rental' => ['required', 'integer', 'min:0'],
            'electricity' => ['nullable', 'integer', 'min:0'],
            'water' => ['nullable', 'integer', 'min:0'],
            'wifi' => ['nullable', 'integer', 'min:0'],
            'late_fee' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:pending,partial,paid,overdue'],
        ]);

        foreach (['electricity','water','wifi','late_fee'] as $k) $data[$k] = $data[$k] ?? 0;
        $data['total_amount'] = $data['room_rental'] + $data['electricity'] + $data['water'] + $data['wifi'] + $data['late_fee'];
        $data['paid_amount'] = $data['status'] === 'paid' ? $data['total_amount'] : 0;
        $data['paid_at'] = $data['status'] === 'paid' ? now()->toDateString() : null;

        // prevent duplicate bill for same tenant/month/year
        $exists = Bill::where('tenant_id', $data['tenant_id'])->where('month', $data['month'])->where('year', $data['year'])->exists();
        if ($exists) {
            return back()->withErrors(['month' => 'Tagihan untuk penghuni pada bulan/tahun tersebut sudah ada.'])->withInput();
        }

        Bill::create($data);
        return redirect()->route('admin.bills.index')->with('success', 'Tagihan berhasil dibuat.');
    }

    public function updateStatus(Request $request, Bill $bill)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,partial,paid,overdue'],
            'paid_amount' => ['nullable', 'integer', 'min:0', 'max:'.$bill->total_amount],
        ]);

        $bill->status = $data['status'];
        if (isset($data['paid_amount'])) $bill->paid_amount = $data['paid_amount'];
        if ($bill->status === 'paid') {
            $bill->paid_amount = $bill->total_amount;
            $bill->paid_at = now()->toDateString();
        } elseif ($bill->status === 'pending') {
            $bill->paid_at = null;
        }
        $bill->save();

        return back()->with('success', 'Status tagihan diperbarui.');
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();
        return redirect()->route('admin.bills.index')->with('success', 'Tagihan dihapus.');
    }
}

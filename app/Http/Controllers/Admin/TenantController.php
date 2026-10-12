<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['user', 'room'])->latest()->paginate(20);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $rooms = Room::where('is_available', true)->orderBy('room_number')->get();
        return view('admin.tenants.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', 'max:30'],
            'id_card_number' => ['required', 'string', 'max:30', 'unique:tenants,id_card_number'],
            'address' => ['required', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:30'],
            'room_id' => ['required', 'exists:rooms,id'],
            'check_in_date' => ['nullable', 'date'],
            'contract_start' => ['nullable', 'date'],
            'contract_end' => ['nullable', 'date', 'after_or_equal:contract_start'],
            'status' => ['required', 'in:pending,active,inactive'],
        ]);

        $user = User::create([
            'name' => $data['full_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'tenant',
            'is_active' => true,
        ]);

        Tenant::create([
            'user_id' => $user->id,
            'room_id' => $data['room_id'],
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'id_card_number' => $data['id_card_number'],
            'address' => $data['address'],
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'check_in_date' => $data['check_in_date'] ?? null,
            'contract_start' => $data['contract_start'] ?? null,
            'contract_end' => $data['contract_end'] ?? null,
            'status' => $data['status'],
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Penghuni berhasil ditambahkan.');
    }

    public function edit(Tenant $tenant)
    {
        $rooms = Room::orderBy('room_number')->get();
        return view('admin.tenants.edit', compact('tenant', 'rooms'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'id_card_number' => ['required', 'string', 'max:30', Rule::unique('tenants', 'id_card_number')->ignore($tenant->id)],
            'address' => ['required', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:30'],
            'room_id' => ['required', 'exists:rooms,id'],
            'check_in_date' => ['nullable', 'date'],
            'contract_start' => ['nullable', 'date'],
            'contract_end' => ['nullable', 'date', 'after_or_equal:contract_start'],
            'status' => ['required', 'in:pending,active,inactive'],
        ]);

        $tenant->update($data);
        $tenant->user->update(['name' => $data['full_name']]);

        return redirect()->route('admin.tenants.index')->with('success', 'Data penghuni diperbarui.');
    }

    public function destroy(Tenant $tenant)
    {
        // Do not delete user automatically — keep auth record; only remove tenant link
        // If bills exist, prevent delete
        if ($tenant->bills()->exists()) {
            return back()->withErrors(['tenant' => 'Penghuni memiliki tagihan, tidak dapat dihapus.']);
        }
        $tenant->delete();
        return redirect()->route('admin.tenants.index')->with('success', 'Penghuni dihapus.');
    }
}

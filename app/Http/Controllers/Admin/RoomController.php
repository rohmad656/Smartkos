<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount('tenants')->latest()->paginate(20);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_number' => ['required', 'string', 'max:20', 'unique:rooms,room_number'],
            'type' => ['required', 'in:standard,deluxe,vip'],
            'price' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['string', 'max:50'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $data['is_available'] = (bool) ($data['is_available'] ?? true);
        if (! isset($data['facilities'])) $data['facilities'] = null;

        Room::create($data);
        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'room_number' => ['required', 'string', 'max:20', 'unique:rooms,room_number,'.$room->id],
            'type' => ['required', 'in:standard,deluxe,vip'],
            'price' => ['required', 'integer', 'min:0'],
            'size' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['string', 'max:50'],
            'is_available' => ['nullable', 'boolean'],
        ]);
        $data['is_available'] = (bool) ($data['is_available'] ?? false);
        if (! isset($data['facilities'])) $data['facilities'] = null;

        $room->update($data);
        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        if ($room->tenants()->exists()) {
            return back()->withErrors(['room' => 'Kamar masih memiliki penghuni, tidak dapat dihapus.']);
        }
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}

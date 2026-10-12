@extends('layouts.admin', [
    'title' => 'Tambah Kamar',
    'navItems' => [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
        ['label' => 'Kamar', 'route' => 'admin.rooms.index'],
        ['label' => 'Penghuni', 'route' => 'admin.tenants.index'],
        ['label' => 'Tagihan', 'route' => 'admin.bills.index'],
    ],
])

<div class="card" style="max-width:720px">
    <form method="POST" action="{{ route('admin.rooms.store') }}">
        @csrf
        <div class="form-group">
            <label>Nomor Kamar</label>
            <input type="text" name="room_number" required maxlength="20" value="{{ old('room_number') }}" placeholder="A-101">
        </div>
        <div class="form-group">
            <label>Tipe</label>
            <select name="type" required>
                <option value="standard" {{ old('type') === 'standard' ? 'selected' : '' }}>Standard</option>
                <option value="deluxe" {{ old('type') === 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                <option value="vip" {{ old('type') === 'vip' ? 'selected' : '' }}>VIP</option>
            </select>
        </div>
        <div class="form-group">
            <label>Harga per Bulan (Rp)</label>
            <input type="number" name="price" required min="0" step="100000" value="{{ old('price') }}" placeholder="1200000">
        </div>
        <div class="form-group">
            <label>Luas (m²) — opsional</label>
            <input type="number" name="size" min="0" value="{{ old('size') }}" placeholder="12">
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="4" maxlength="2000" placeholder="Deskripsi singkat kamar...">{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
            <label>Fasilitas (pisahkan dengan koma)</label>
            <input type="text" name="facilities_input" placeholder="AC, WiFi, Kasur, Lemari, Kamar Mandi Dalam" value="{{ old('facilities_input', implode(', ', $room->facilities ?? [])) }}">
            <small style="color:var(--muted)">Contoh: AC, WiFi, Kasur, Lemari, Kamar Mandi Dalam, Balkon</small>
        </div>
        <div class="form-group">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                <input type="checkbox" name="is_available" {{ old('is_available', true) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--teak)">
                Tersedia untuk disewa
            </label>
        </div>
        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
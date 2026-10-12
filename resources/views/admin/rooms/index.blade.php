@extends('layouts.admin', [
    'title' => 'Kelola Kamar',
    'navItems' => [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
        ['label' => 'Kamar', 'route' => 'admin.rooms.index'],
        ['label' => 'Penghuni', 'route' => 'admin.tenants.index'],
        ['label' => 'Tagihan', 'route' => 'admin.bills.index'],
    ],
    'pageAction' => ['url' => route('admin.rooms.create'), 'label' => 'Tambah Kamar'],
])

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nomor</th><th>Tipe</th><th>Harga/Bln</th><th>Luas</th><th>Fasilitas</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($rooms as $r)
                <tr>
                    <td><strong>{{ $r->room_number }}</strong></td>
                    <td><span class="badge badge-{{ $r->type === 'vip' ? 'active' : ($r->type === 'deluxe' ? 'in_progress' : 'pending') }}">{{ ucfirst($r->type) }}</span></td>
                    <td>Rp{{ number_format($r->price, 0, ',', '.') }}</td>
                    <td>{{ $r->size ? $r->size.' m²' : '—' }}</td>
                    <td>
                        @if($r->facilities)
                            @foreach($r->facilities as $f)
                                <span class="badge badge-inactive" style="margin:2px;background:#f0f0f0;border-color:var(--line)">{{ $f }}</span>
                            @endforeach
                        @else
                            <span style="color:var(--muted)">—</span>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ $r->is_available ? 'active' : 'inactive' }}">{{ $r->is_available ? 'Tersedia' : 'Tidak Tersedia' }}</span></td>
                    <td>
                        <a href="{{ route('admin.rooms.edit', $r) }}" class="btn btn-ghost" style="padding:6px 10px;font-size:12px">Edit</a>
                        <form action="{{ route('admin.rooms.destroy', $r) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus kamar ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-ghost" style="padding:6px 10px;font-size:12px;color:#DC3545;border-color:#DC3545">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:24px">Belum ada kamar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $rooms->links() }}
</div>
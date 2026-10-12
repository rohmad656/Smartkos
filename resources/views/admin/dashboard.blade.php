@extends('layouts.admin', [
    'title' => 'Dashboard Admin',
    'subtitle' => 'Ringkasan operasi SmartKos',
    'navItems' => [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
        ['label' => 'Kamar', 'route' => 'admin.rooms.index'],
        ['label' => 'Penghuni', 'route' => 'admin.tenants.index'],
        ['label' => 'Tagihan', 'route' => 'admin.bills.index', 'count' => $stats['bills_pending'] ?? 0],
    ],
    'pageAction' => ['url' => route('admin.rooms.create'), 'label' => 'Tambah Kamar'],
])

<div class="stat-grid">
    <div class="stat-card"><div class="stat-label">Total Kamar</div><div class="stat-value">{{ $stats['rooms'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Tersedia</div><div class="stat-value">{{ $stats['rooms_available'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Penghuni Aktif</div><div class="stat-value">{{ $stats['tenants'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Tagihan Belum Lunas</div><div class="stat-value">{{ $stats['bills_pending'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Keluhan Terbuka</div><div class="stat-value">{{ $stats['complaints_open'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Pendapatan Bulan Ini</div><div class="stat-value">Rp{{ number_format($stats['revenue_month'], 0, ',', '.') }}</div></div>
</div>

<div class="card" style="margin-bottom: 16px;">
    <h3 style="margin:0 0 12px;font-size:16px">Tagihan Terbaru</h3>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Penghuni</th><th>Kamar</th><th>Periode</th><th>Total</th><th>Dibayar</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($recentBills as $b)
                <tr>
                    <td>{{ $b->tenant->full_name ?? '-' }}</td>
                    <td>{{ $b->tenant->room->room_number ?? '-' }}</td>
                    <td>{{ $b->month }}/{{ $b->year }}</td>
                    <td>Rp{{ number_format($b->total_amount, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($b->paid_amount, 0, ',', '.') }}</td>
                    <td><span class="badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="stat-grid" style="grid-template-columns: 1fr 1fr;">
    <div class="card">
        <h3 style="margin:0 0 12px;font-size:16px">Keluhan Terbuka</h3>
        @forelse($recentComplaints as $c)
            <div style="padding:10px 0;border-bottom:1px solid var(--line)">
                <strong>{{ $c->title }}</strong><br>
                <small>{{ $c->tenant->full_name }} — Kamar {{ $c->room->room_number }}</small>
                <span class="badge badge-{{ $c->status }}" style="margin-left:8px">{{ ucfirst($c->status) }}</span>
            </div>
        @empty
            <p style="color:var(--muted);margin:0">Belum ada keluhan.</p>
        @endforelse
    </div>
    <div class="card">
        <h3 style="margin:0 0 12px;font-size:16px">Pengumuman Aktif</h3>
        @forelse($announcements as $a)
            <div style="padding:10px 0;border-bottom:1px solid var(--line)">
                <strong>{{ $a->title }}</strong><br>
                <small>{{ \Carbon\Carbon::parse($a->published_at)->translatedFormat('d M Y') }}</small>
            </div>
        @empty
            <p style="color:var(--muted);margin:0">Tidak ada pengumuman.</p>
        @endforelse
    </div>
</div>
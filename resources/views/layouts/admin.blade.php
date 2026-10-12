<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SmartKos' }}</title>
    <meta name="description" content="Platform kelola kos pintar untuk manajemen kamar, penghuni, tagihan, dan keluhan.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;500;700&family=Fraunces:opsz,wght@9..144,700;9..144,900&display=swap" rel="stylesheet">
    <style>
        :root {
            --paper: #FFF9EB;
            --ink: #1B1E1A;
            --teak: #3D2C17;
            --teal: #0E7C6B;
            --mustard: #E8B44A;
            --line: #D9D6CF;
            --muted: #6B6760;
        }
        * { box-sizing: border-box; }
        body { font-family: "DM Sans", system-ui, sans-serif; color: var(--ink); background: var(--paper); margin: 0; line-height: 1.6; }
        .layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
        .sidebar { background: var(--ink); color: var(--paper); padding: 20px 16px; position: sticky; top: 0; height: 100vh; overflow-y: auto; }
        .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; color: inherit; margin-bottom: 24px; }
        .brand-mark { width: 32px; height: 32px; background: var(--teak); border: 2px solid var(--paper); border-radius: 8px; position: relative; }
        .brand-mark::after { content: ""; position: absolute; right: 4px; top: 4px; width: 10px; height: 10px; background: var(--mustard); border: 1.5px solid var(--paper); border-radius: 50%; }
        .brand-type { font-family: "Fraunces", serif; font-weight: 900; letter-spacing: -0.02em; font-size: 20px; }
        .nav { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 4px; }
        .nav a { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; text-decoration: none; color: var(--paper); opacity: 0.8; transition: opacity 0.12s, background 0.12s; }
        .nav a:hover, .nav a.active { opacity: 1; background: rgba(255,255,255,0.08); }
        .nav a .badge { margin-left: auto; background: var(--mustard); color: var(--ink); font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 999px; }
        .main { padding: 24px; overflow-x: auto; }
        .page-head { display: flex; align-items: end; justify-content: space-between; gap: 16px; margin-bottom: 16px; flex-wrap: wrap; }
        .page-head h1 { font-family: "Fraunces", serif; font-weight: 900; font-size: 28px; letter-spacing: -0.02em; margin: 0; line-height: 1.1; }
        .page-head p { color: var(--muted); margin: 4px 0 0; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 16px; border: 2px solid var(--ink); border-radius: 999px; font-weight: 700; font-size: 14px; text-decoration: none; cursor: pointer; transition: transform 0.12s, background 0.12s; }
        .btn:active { transform: translate(1px, 1px); }
        .btn-primary { background: var(--teak); color: var(--paper); }
        .btn-primary:hover { background: var(--ink); }
        .btn-ghost { background: transparent; }
        .btn-ghost:hover { background: var(--ink); color: var(--paper); }
        .card { background: #fff; border: 2px solid var(--ink); border-radius: 18px; box-shadow: 4px 4px 0 var(--ink); padding: 16px; }
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 16px; }
        .stat-card { background: #fff; border: 2px solid var(--ink); border-radius: 14px; box-shadow: 3px 3px 0 var(--ink); padding: 14px; }
        .stat-label { font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-value { font-family: "Fraunces", serif; font-weight: 900; font-size: 28px; line-height: 1; color: var(--ink); }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid var(--line); }
        th { font-weight: 700; font-size: 12px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.03em; }
        tbody tr:hover { background: #FFFEF7; }
        .badge { display: inline-flex; align-items: center; padding: 4px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; }
        .badge-pending { background: #FFF3D0; color: #8B6B00; border: 1px solid var(--mustard); }
        .badge-active, .badge-paid { background: #C9E6E0; color: #065A4D; border: 1px solid var(--teal); }
        .badge-overdue, .badge-rejected { background: #F8D7DA; color: #721C24; border: 1px solid #DC3545; }
        .badge-inactive { background: #E8E6E3; color: var(--muted); border: 1px solid var(--line); }
        .badge-in_progress { background: #D6E9F8; color: #1E4D88; border: 1px solid #2563EB; }
        .badge-resolved { background: #E2E2E2; color: var(--muted); border: 1px solid var(--line); }
        .form-group { margin-bottom: 14px; }
        label { display: block; font-size: 12px; font-weight: 700; margin-bottom: 6px; color: var(--ink); }
        input, select, textarea { width: 100%; padding: 11px 12px; border: 1.5px solid var(--line); border-radius: 12px; background: #fff; font: inherit; }
        input:focus, select:focus, textarea:focus { outline: 3px solid var(--teal); outline-offset: 1px; border-color: var(--teal); }
        .alert { padding: 12px 14px; border-radius: 12px; border: 1.5px solid; margin-bottom: 14px; font-size: 14px; }
        .alert-success { background: #C9E6E0; border-color: var(--teal); color: #065A4D; }
        .alert-error { background: #F8D7DA; border-color: #DC3545; color: #721C24; }
        .pagination { display: flex; gap: 6px; justify-content: center; margin-top: 16px; }
        .pagination a, .pagination span { display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 10px; border: 1.5px solid var(--line); border-radius: 10px; text-decoration: none; color: var(--ink); }
        .pagination a:hover { background: var(--ink); color: var(--paper); border-color: var(--ink); }
        .pagination .current { background: var(--ink); color: var(--paper); border-color: var(--ink); }
        .mobile-menu-btn { display: none; position: fixed; bottom: 20px; right: 20px; z-index: 100; background: var(--teak); color: var(--paper); border: 2px solid var(--ink); border-radius: 50%; width: 56px; height: 56px; font-size: 24px; box-shadow: 4px 4px 0 var(--ink); }
        @media (max-width: 960px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: fixed; left: -280px; top: 0; bottom: 0; width: 260px; z-index: 200; transition: left 0.2s; box-shadow: 8px 0 24px rgba(0,0,0,0.15); }
            .sidebar.open { left: 0; }
            .mobile-menu-btn { display: flex; align-items: center; justify-content: center; }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 150; }
            .sidebar-overlay.open { display: block; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <aside class="sidebar" id="sidebar">
            <a href="{{ route('home') }}" class="brand" aria-label="SmartKos Home">
                <span class="brand-mark" aria-hidden="true"></span>
                <span class="brand-type">SmartKos</span>
            </a>
            <nav aria-label="Navigasi utama">
                <ul class="nav">
                    @foreach($navItems as $item)
                        <li>
                            <a href="{{ $item['route'] }}" class="{{ request()->routeIs($item['route']) || request()->routeIs($item['route'].'*') ? 'active' : '' }}" {{ isset($item['count']) ? 'title="'.$item['count'].' item"' : '' }}>
                                {{ $item['label'] }}
                                @if(isset($item['count']) && $item['count'] > 0)
                                    <span class="badge">{{ $item['count'] }}</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </aside>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <main class="main">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <strong>Terdapat kesalahan:</strong>
                    <ul style="margin:6px 0 0 18px;padding:0">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif
            @if(isset($title))
                <div class="page-head">
                    <div>
                        <h1>{{ $title }}</h1>
                        @if(isset($subtitle))<p>{{ $subtitle }}</p>@endif
                    </div>
                    @if(isset($pageAction))
                        <a href="{{ $pageAction['url'] }}" class="btn btn-primary">{{ $pageAction['label'] }}</a>
                    @endif
                </div>
            @endif
            {{ $slot }}
        </main>
    </div>
    <button class="mobile-menu-btn" id="menuBtn" aria-label="Buka menu">☰</button>
    <script>
        const btn = document.getElementById('menuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if(btn && sidebar && overlay) {
            btn.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('open'); });
            overlay.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); });
            sidebar.querySelectorAll('a').forEach(a => a.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); }));
        }
    </script>
</body>
</html>
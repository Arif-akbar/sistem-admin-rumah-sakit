<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Clinical Precision</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i data-lucide="activity" style="color: var(--color-primary);"></i>
            <span>MediFlow</span>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ url('/') }}" class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard Utama</span>
            </a>
            <a href="{{ url('/pasien') }}" class="nav-item {{ request()->is('pasien*') ? 'active' : '' }}">
                <i data-lucide="users"></i>
                <span>Data Pasien</span>
            </a>
            <a href="{{ url('/jadwal') }}" class="nav-item {{ request()->is('jadwal*') ? 'active' : '' }}">
                <i data-lucide="calendar"></i>
                <span>Jadwal Dokter</span>
            </a>
            <a href="{{ url('/rekam-medis') }}" class="nav-item {{ request()->is('rekam-medis*') ? 'active' : '' }}">
                <i data-lucide="file-text"></i>
                <span>Rekam Medis</span>
            </a>
            <a href="#" class="nav-item">
                <i data-lucide="bed-double"></i>
                <span>Status Rawat Inap</span>
            </a>
        </nav>
        <div class="sidebar-nav" style="flex: 0; border-top: 1px solid rgba(255,255,255,0.1);">
            <a href="#" class="nav-item">
                <i data-lucide="settings"></i>
                <span>Pengaturan</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

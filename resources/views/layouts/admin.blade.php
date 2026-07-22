<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel - @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/panel.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
@php
    $user = Auth::user();
    $isApprover = in_array($user->role, ['head_department', 'gm'], true);
    $isHrd = $user->role === 'hrd';
    $canManageStaff = $isHrd;
    $notificationCount = $isApprover
        ? \App\Models\Cuti::where('status', 'pending')->count()
        : \App\Models\Cuti::latest()->count();
    $notifications = $isApprover
        ? \App\Models\Cuti::with('pegawai')->where('status', 'pending')->latest()->take(50)->get()
        : \App\Models\Cuti::with('pegawai')->latest()->take(50)->get();
@endphp
<div class="app-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <div>
                <div class="fw-black fw-bold">Hotel Leave</div>
                <small>Hospitality Staff Leave</small>
            </div>
        </div>

        <div class="sidebar-section-title">Menu Utama</div>
        <nav class="sidebar-nav">
            @if($user->isManagement())
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}">
                    <i class="fa fa-gauge-high"></i> Dashboard
                </a>
            @endif

            @if($isHrd)
                <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.index') || request()->routeIs('admin.create') || request()->routeIs('admin.edit') ? 'active-link' : '' }}">
                    <i class="fa fa-user-shield"></i> Akun Manajemen
                </a>
                <a href="{{ route('admin.pegawai.index') }}" class="{{ request()->routeIs('admin.pegawai.*') ? 'active-link' : '' }}">
                    <i class="fa fa-users"></i> Staff
                </a>
                <a href="{{ route('admin.master-data.index') }}" class="{{ request()->routeIs('admin.master-data.*') ? 'active-link' : '' }}">
                    <i class="fa fa-layer-group"></i> Departemen
                </a>
            @endif

            @if($isApprover)
                <a href="{{ route('admin.cuti.index') }}" class="{{ request()->routeIs('admin.cuti.index') ? 'active-link' : '' }}">
                    <i class="fa fa-calendar-days"></i> Pengajuan Cuti
                </a>
            @endif

            <a href="{{ route('admin.cuti.laporan') }}" class="{{ request()->routeIs('admin.cuti.laporan') ? 'active-link' : '' }}">
                <i class="fa fa-chart-column"></i> Laporan Cuti
            </a>
            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active-link' : '' }}">
                <i class="fa fa-id-card"></i> Profil Saya
            </a>
        </nav>

        <div class="hotel-mini-card">
            <div class="fw-bold mb-1"><i class="fa fa-hotel me-2"></i>Sistem Hotel</div>
            <small>Monitoring cuti tahunan, staff lintas departemen, dan proses approval.</small>
        </div>

        <hr class="sidebar-divider">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout">
                <i class="fa fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </aside>

    <main class="main-area">
        <header class="topbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-soft-primary mobile-menu-btn" type="button" data-sidebar-toggle>
                    <i class="fa fa-bars"></i>
                </button>
                <div>
                    <div class="topbar-title text-muted small fw-bold">Hotel Leave Management</div>
                    <h5 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h5>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <button class="btn btn-light position-relative rounded-pill icon-btn" data-bs-toggle="dropdown" type="button">
                        <i class="fa fa-bell"></i>
                        @if($notificationCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $notificationCount }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0 shadow notification-menu">
                        <div class="px-3 py-2 border-bottom fw-bold notification-header">Notifikasi</div>
                        <div class="notification-list">
                        @forelse($notifications as $notif)
                            <a class="dropdown-item py-2 notification-item" href="{{ $isApprover ? route('admin.cuti.index') : route('admin.cuti.laporan') }}">
                                <div class="fw-semibold">{{ $notif->pegawai->name ?? 'Staff' }} - {{ ucfirst($notif->status) }}</div>
                                <small class="text-muted">{{ $notif->jenis_cuti ?? 'Cuti' }} · {{ optional($notif->created_at)->format('d M Y H:i') }}</small>
                            </a>
                        @empty
                            <div class="px-3 py-3 text-muted small">Belum ada notifikasi.</div>
                        @endforelse
                        </div>
                    </div>
                </div>

                <button class="btn btn-light icon-btn" type="button" data-theme-toggle title="Ganti tema">
                    <i class="fa fa-moon"></i>
                </button>

                <div class="dropdown">
                    <button class="user-chip user-chip-premium border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar user-chip-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div class="user-chip-text d-none d-md-flex">
                            <span>{{ $user->name }}</span>
                            <small>{{ $user->position_label }}</small>
                        </div>
                        <i class="fa fa-chevron-down small text-muted"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end shadow p-0 profile-dropdown">
                        <div class="profile-dropdown-head">
                            <div class="profile-dropdown-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="profile-dropdown-name">{{ $user->name }}</div>
                                <div class="profile-dropdown-email">{{ $user->email }}</div>
                            </div>
                        </div>

                        <div class="profile-dropdown-body">
                            <div class="profile-role-badge">
                                <i class="fa fa-shield-halved"></i>
                                <span>{{ $user->position_label }}</span>
                            </div>

                            <div class="profile-meta-list">
                                <div class="profile-meta-item">
                                    <i class="fa fa-building-user"></i>
                                    <div>
                                        <span>Departemen</span>
                                        <strong>{{ $user->department ?: 'Belum diisi' }}</strong>
                                    </div>
                                </div>

                                @if($user->role === 'staff')
                                    <div class="profile-meta-item">
                                        <i class="fa fa-id-badge"></i>
                                        <div>
                                            <span>Jabatan</span>
                                            <strong>{{ $user->jabatan ?: 'Belum diisi' }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="profile-dropdown-actions">
                            <a class="dropdown-item profile-action" href="{{ route('profile.edit') }}">
                                <i class="fa fa-id-card"></i>
                                <span>Edit Profil</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item profile-action profile-action-danger">
                                    <i class="fa fa-right-from-bracket"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const savedTheme = localStorage.getItem('hotelLeaveTheme');
    if (savedTheme === 'dark') {
        document.body.setAttribute('data-theme', 'dark');
    }

    function syncThemeIcons() {
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        document.querySelectorAll('[data-theme-toggle] i').forEach(icon => {
            icon.className = isDark ? 'fa fa-sun' : 'fa fa-moon';
        });
    }
    syncThemeIcons();

    document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const isDark = document.body.getAttribute('data-theme') === 'dark';
            if (isDark) {
                document.body.removeAttribute('data-theme');
                localStorage.setItem('hotelLeaveTheme', 'light');
            } else {
                document.body.setAttribute('data-theme', 'dark');
                localStorage.setItem('hotelLeaveTheme', 'dark');
            }
            syncThemeIcons();
        });
    });

    document.querySelectorAll('[data-sidebar-toggle]').forEach(btn => {
        btn.addEventListener('click', () => document.body.classList.toggle('sidebar-open'));
    });
    document.querySelectorAll('[data-sidebar-close], .sidebar-nav a').forEach(el => {
        el.addEventListener('click', () => document.body.classList.remove('sidebar-open'));
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') document.body.classList.remove('sidebar-open');
    });
</script>
@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Komponen</title>

    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font.css') }}">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .font-display {
            font-family: 'Syne', sans-serif;
        }

        .font-mono-custom {
            font-family: 'DM Mono', monospace;
        }


        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }

        @keyframes pulseDot {
            0%, 100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.5); }
            50%       { box-shadow: 0 0 0 5px rgba(52, 211, 153, 0);  }
        }

        @keyframes sidebarEnter {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes overlayFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes iconBounce {
            0%, 100% { transform: translateY(0);   }
            40%      { transform: translateY(-3px); }
            60%      { transform: translateY(-1px); }
        }

        @keyframes logoGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
            50%       { box-shadow: 0 0 30px rgba(99, 102, 241, 0.6); }
        }


        #sidebar {
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity  0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #sidebar .sidebar__link {
            animation: fadeSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) both;
        }
        #sidebar .sidebar__link:nth-child(1) { animation-delay: 0.05s; }
        #sidebar .sidebar__link:nth-child(2) { animation-delay: 0.10s; }
        #sidebar .sidebar__link:nth-child(3) { animation-delay: 0.15s; }
        #sidebar .sidebar__link:nth-child(4) { animation-delay: 0.20s; }

        .active-link {
            background: linear-gradient(
                135deg,
                #0ea5e9 0%,
                #6366f1 50%,
                #0ea5e9 100%
            );
            background-size: 200% auto;
            animation: shimmer 3s linear infinite;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
        }


        .sidebar__link {
            position: relative;
            overflow: hidden;
        }

        .sidebar__link::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(14, 165, 233, 0.08), rgba(99, 102, 241, 0.08));
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: inherit;
        }

        .sidebar__link:not(.active-link):hover::before {
            transform: translateX(0);
        }

        .sidebar__link i {
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1),
                        color     0.2s  ease;
        }

        .sidebar__link:hover i {
            transform: scale(1.2);
        }

        .active-link i {
            animation: iconBounce 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }


        .logo-icon {
            animation: logoGlow 3s ease-in-out infinite;
        }


        .status-dot {
            animation: pulseDot 2s ease-in-out infinite;
        }


        #sidebar::-webkit-scrollbar       { width: 4px; }
        #sidebar::-webkit-scrollbar-track { background: transparent; }
        #sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }


        #sidebar-overlay {
            transition: opacity 0.3s ease;
            opacity: 0;
        }
        #sidebar-overlay.visible {
            opacity: 1;
        }



        .sidebar-select {
            appearance: none;
            -webkit-appearance: none;
            background: transparent;
            cursor: pointer;
        }
        .sidebar-select option {
            background: #0f172a;
            color: #cbd5e1;
        }

 

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9999;
            opacity: 0.4;
        }



        .sidebar-action-btn {
            transition: background-color 0.2s ease,
                        color           0.2s ease,
                        transform       0.15s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .sidebar-action-btn:hover {
            transform: translateX(3px);
        }



        #main {
            animation: fadeIn 0.5s ease 0.1s both;
            transition: padding-left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }


        #header {
            animation: fadeSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) both;
        }


        .user-card {
            transition: background-color 0.2s ease,
                        border-color     0.2s ease;
        }
        .user-card:hover {
            background-color: rgba(51, 65, 85, 0.6);
            border-color: rgba(99, 102, 241, 0.3);
        }
    </style>
</head>

<body class="bg-slate-950 text-slate-300 min-h-screen">

    <header id="header"
        class="fixed top-0 left-0 right-0 z-50 h-14 bg-slate-900/80 backdrop-blur-md border-b border-slate-800/60 lg:hidden">
        <div class="flex items-center justify-between h-full px-4">
            <a href="#" class="flex items-center gap-2 font-display font-700 text-lg text-white tracking-tight">
                <span class="logo-icon w-8 h-8 rounded-lg bg-gradient-to-br from-sky-400 to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="ri-box-3-fill text-white text-sm"></i>
                </span>
                <span>Komponen</span>
            </a>
            <button id="header-toggle"
                class="w-9 h-9 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400
                       hover:text-white hover:bg-slate-700 transition-all duration-200 active:scale-95">
                <i class="ri-menu-line text-lg transition-transform duration-200"></i>
            </button>
        </div>
    </header>

    <nav id="sidebar"
        class="fixed top-0 left-0 h-full w-64 bg-slate-900 border-r border-slate-800/60 z-40 flex flex-col overflow-y-auto
               -translate-x-full lg:translate-x-0">

        <div class="flex flex-col h-full">

            <div class="px-5 py-5 border-b border-slate-800/60">
                <a href="#" class="flex items-center gap-3 group">
                    <span class="logo-icon w-9 h-9 rounded-xl bg-gradient-to-br from-sky-400 to-indigo-500 flex items-center justify-center
                                 shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50
                                 transition-all duration-300 group-hover:scale-110">
                        <i class="ri-box-3-fill text-white"></i>
                    </span>
                    <div>
                        <p class="font-display font-700 text-white text-base leading-none tracking-tight">Komponen</p>
                        <p class="font-mono-custom text-xs text-slate-500 mt-0.5">v2.0 system</p>
                    </div>
                </a>
            </div>

            <div class="px-4 py-4 border-b border-slate-800/60">
                <div class="user-card flex items-center gap-3 p-3 rounded-xl bg-slate-800/50 border border-slate-700/40 cursor-default">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center border border-slate-600/50 shrink-0
                                transition-transform duration-300 hover:scale-105">
                        <i class="ri-user-fill text-slate-300 text-sm"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-200 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">NRP : {{ auth()->user()->nrp }}</p>
                    </div>
                    <span class="status-dot w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>
                </div>
            </div>

            <div class="flex-1 px-3 py-4 space-y-1">
                <p class="font-mono-custom text-[10px] font-500 text-slate-600 uppercase tracking-widest px-2 mb-3">
                    Manage
                </p>

                <a href="/dashboard"
                    class="sidebar__link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->is('dashboard') ? 'active-link text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <i class="ri-pie-chart-2-fill text-base {{ request()->is('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-sky-400' }} transition-colors"></i>
                    <span>Dashboard</span>
                </a>
                @if (Auth::user()->role === 'admin')                    
                <a href="/user"
                    class="sidebar__link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->is('user*') ? 'active-link text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <i class="ri-user-line text-base {{ request()->is('user*') ? 'text-white' : 'text-slate-500 group-hover:text-sky-400' }} transition-colors"></i>
                    <span>Master User</span>
                </a>
                @endif
                <a href="/komponen"
                    class="sidebar__link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->is('komponen*') ? 'active-link text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <i class="ri-cpu-line text-base {{ request()->is('komponen*') ? 'text-white' : 'text-slate-500 group-hover:text-sky-400' }} transition-colors"></i>
                    <span>Master Komponen</span>
                </a>

                <a href="/departemen"
                    class="sidebar__link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->is('departemen*') ? 'active-link text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <i class="ri-community-fill text-base {{ request()->is('departemen*') ? 'text-white' : 'text-slate-500 group-hover:text-sky-400' }} transition-colors"></i>
                    <span>Departemen</span>
                </a>

                <a href="/mutasi"
                    class="sidebar__link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->is('mutasi*') ? 'active-link text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <i class="ri-arrow-left-right-fill text-base {{ request()->is('mutasi*') ? 'text-white' : 'text-slate-500 group-hover:text-sky-400' }} transition-colors"></i>
                    <span>Mutasi Barang</span>
                </a>
            </div>

            <div class="px-3 border-t border-slate-800/60">
                <a href="{{ route('backup.index') }}"
                    class="sidebar-action-btn sidebar__link group w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                           {{ request()->is('backup*') ? 'active-link text-white' : 'text-slate-400 hover:text-blue-400 hover:bg-blue-500/10' }}">
                    <i class="ri-settings-3-line text-base text-slate-500 group-hover:text-blue-400 transition-colors"></i>
                    <span>Backup</span>
                </a>
            </div>

            <div class="px-3 py-4">
                <form id="logoutForm" action="/logout" method="POST">
                    @csrf
                    <button type="button"
                        class="sidebar-action-btn group w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400
                               hover:text-red-400 hover:bg-red-500/10 text-left">
                        <i class="ri-logout-box-r-fill text-base text-slate-500 group-hover:text-red-400 transition-colors"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>

        </div>
    </nav>

    <div id="sidebar-overlay"
        class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-30 hidden lg:hidden"
        onclick="closeSidebar()">
    </div>

    <main id="main" class="min-h-screen pt-14 lg:pt-0 lg:pl-64 transition-all duration-300">
        <div class="p-6">
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const headerToggle = document.getElementById('header-toggle');
        let sidebarOpen = false;

        function openSidebar() {
            sidebarOpen = true;
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');

            requestAnimationFrame(() => overlay.classList.add('visible'));
            headerToggle.querySelector('i').classList.replace('ri-menu-line', 'ri-close-line');
        }

        function closeSidebar() {
            sidebarOpen = false;
            sidebar.classList.add('-translate-x-full');
            overlay.classList.remove('visible');

            overlay.addEventListener('transitionend', () => {
                if (!sidebarOpen) overlay.classList.add('hidden');
            }, { once: true });
            headerToggle.querySelector('i').classList.replace('ri-close-line', 'ri-menu-line');
        }

        headerToggle?.addEventListener('click', () => {
            sidebarOpen ? closeSidebar() : openSidebar();
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && sidebarOpen) closeSidebar();
        });

        document.querySelectorAll('#logoutForm button').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Anda akan keluar dari website!',
                    icon: 'warning',
                    background: '#0f172a',
                    color: '#cbd5e1',
                    iconColor: '#f59e0b',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#475569',
                    confirmButtonText: 'Ya, keluar',
                    cancelButtonText: 'Batal',
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // flash messages via SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                background: '#0f172a',
                color: '#cbd5e1',
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                background: '#0f172a',
                color: '#cbd5e1',
            });
        @endif
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi kesalahan',
                text: "{{ $errors->first() }}",
                background: '#0f172a',
                color: '#cbd5e1',
            });
        @endif

        // generic confirm for forms that declare data-confirm attribute
        document.querySelectorAll('form[data-confirm]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const msg = form.getAttribute('data-confirm');
                Swal.fire({
                    title: 'Konfirmasi',
                    text: msg || 'Apakah Anda yakin?',
                    icon: 'warning',
                    background: '#0f172a',
                    color: '#cbd5e1',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#475569',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal',
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        function handleLaporanChange(select) {
            if (select.value) window.location.href = select.value;
        }
    </script>

</body>
</html> 
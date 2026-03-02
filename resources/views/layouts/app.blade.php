<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Komponen</title>

    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font.css') }}">
    <!-- <link
        href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet"> -->
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

        #sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .active-link {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
        }

        #sidebar::-webkit-scrollbar {
            width: 4px;
        }

        #sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 2px;
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
    </style>
</head>

<body class="bg-slate-950 text-slate-300 min-h-screen">

    <header id="header"
        class="fixed top-0 left-0 right-0 z-50 h-14 bg-slate-900/80 backdrop-blur-md border-b border-slate-800/60 lg:hidden">
        <div class="flex items-center justify-between h-full px-4">

            <a href="#" class="flex items-center gap-2 font-display font-700 text-lg text-white tracking-tight">
                <span
                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-sky-400 to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="ri-box-3-fill text-white text-sm"></i>
                </span>
                <span>Komponen</span>
            </a>
            <button id="header-toggle"
                class="w-9 h-9 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-700 transition-all duration-200">
                <i class="ri-menu-line text-lg"></i>
            </button>
        </div>
    </header>
    <nav id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-slate-900 border-r border-slate-800/60 z-40 flex flex-col overflow-y-auto
               -translate-x-full lg:translate-x-0">

        <div class="flex flex-col h-full">

            <div class="px-5 py-5 border-b border-slate-800/60">
                <a href="#" class="flex items-center gap-3 group">
                    <span
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-sky-400 to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-all duration-300">
                        <i class="ri-box-3-fill text-white"></i>
                    </span>
                    <div>
                        <p class="font-display font-700 text-white text-base leading-none tracking-tight">Komponen</p>
                        <p class="font-mono-custom text-xs text-slate-500 mt-0.5">v2.0 system</p>
                    </div>
                </a>
            </div>
            <div class="px-4 py-4 border-b border-slate-800/60">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/50 border border-slate-700/40">
                    <div
                        class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-600 to-slate-700 flex items-center justify-center border border-slate-600/50 shrink-0">
                        <i class="ri-user-fill text-slate-300 text-sm"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-200 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">NRP : {{ auth()->user()->nrp }}</p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0 shadow-sm shadow-emerald-400/50"></span>
                </div>
            </div>
            <div class="flex-1 px-3 py-4 space-y-1">

                <p class="font-mono-custom text-[10px] font-500 text-slate-600 uppercase tracking-widest px-2 mb-3">
                    Manage
                </p>
                <a href="/dashboard"
                    class="sidebar__link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->is('dashboard') ? 'active-link text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <i
                        class="ri-pie-chart-2-fill text-base {{ request()->is('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-sky-400' }} transition-colors"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/komponen"
                    class="sidebar__link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->is('komponen*') ? 'active-link text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <i
                        class="ri-cpu-line text-base {{ request()->is('komponen*') ? 'text-white' : 'text-slate-500 group-hover:text-sky-400' }} transition-colors"></i>
                    <span>Master Komponen</span>
                </a>
                <a href="/departemen"
                    class="sidebar__link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->is('departemen*') ? 'active-link text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <i
                        class="ri-community-fill text-base {{ request()->is('departemen*') ? 'text-white' : 'text-slate-500 group-hover:text-sky-400' }} transition-colors"></i>
                    <span>Departemen</span>
                </a>
                <a href="/mutasi"
                    class="sidebar__link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ request()->is('mutasi*') ? 'active-link text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                    <i
                        class="ri-arrow-left-right-fill text-base {{ request()->is('mutasi*') ? 'text-white' : 'text-slate-500 group-hover:text-sky-400' }} transition-colors"></i>
                    <span>Mutasi Barang</span>
                </a>
                <!-- <div class="relative group">
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                {{ request()->is('laporan*') ? 'bg-gradient-to-r from-sky-600/20 to-indigo-600/20 text-sky-300 border border-sky-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                        <i class="ri-file-chart-line text-base {{ request()->is('laporan*') ? 'text-sky-400' : 'text-slate-500 group-hover:text-sky-400' }} transition-colors shrink-0"></i>
                        <div class="relative flex-1">
                            <select
                                class="sidebar-select w-full text-sm font-medium bg-transparent outline-none
                                       {{ request()->is('laporan*') ? 'text-sky-300' : 'text-slate-400' }} group-hover:text-white transition-colors"
                                onchange="handleLaporanChange(this)">
                                <option value="">Pilih Laporan</option>
                                <option value="{{ route('laporan.transaksi') }}"
                                    {{ request()->is('laporan') && !request()->is('laporan/rekap*') ? 'selected' : '' }}>
                                    Laporan Mutasi
                                </option>
                            </select>
                        </div>
                        <i class="ri-arrow-down-s-line text-xs text-slate-600 shrink-0"></i>
                    </div>
                </div> -->

            </div>
            <div class="px-3 border-t border-slate-800/60">
                <a href="{{ route('backup.index') }}" class="group w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400
                               hover:text-blue-400 hover:bg-blue-500/10 transition-all duration-200 text-left
                                {{ request()->is('backup*') ? 'active-link text-white' : 'text-slate-400 hover:blue -white hover:bg-slate-800/70' }}
                               ">
                    <i
                        class="ri-settings-3-line text-base text-slate-500 group-hover:text-blue-400 transition-colors"></i>
                    <span>Backup</span>
                </a>
            </div>
            <div class="px-3 py-4">
                <form id="logoutForm" action="/logout" method="POST">
                    @csrf
                    <button type="button" class="group w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400
                               hover:text-red-400 hover:bg-red-500/10 transition-all duration-200 text-left">
                        <i
                            class="ri-logout-box-r-fill text-base text-slate-500 group-hover:text-red-400 transition-colors"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-30 hidden lg:hidden"
        onclick="closeSidebar()"></div>

    <main id="main" class="min-h-screen pt-14 lg:pt-0 lg:pl-64 transition-all duration-300">
        <div class="p-6">
            @yield('content')

        </div>
    </main>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        document.getElementById('header-toggle')?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
        document.querySelectorAll('#logoutForm button').forEach(btn => {
            btn.addEventListener('click', function (e) {
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
                    borderRadius: '12px',
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
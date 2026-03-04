<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Manajemen Komponen</title>
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font.css') }}">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Syne', sans-serif; }
        .font-mono-custom { font-family: 'DM Mono', monospace; }

        /* Noise overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9999;
            opacity: 0.4;
        }

        /* Grid pattern background */
        .bg-grid {
            background-image:
                linear-gradient(rgba(99,102,241,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Glow blobs */
        .glow-1 {
            position: fixed; top: -200px; left: -200px;
            width: 700px; height: 700px; border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.12), transparent 70%);
            pointer-events: none; filter: blur(40px);
        }
        .glow-2 {
            position: fixed; bottom: -200px; right: -100px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(14,165,233,0.10), transparent 70%);
            pointer-events: none; filter: blur(40px);
        }

        /* Feature card */
        .feature-card {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(51, 65, 85, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
        }
        .feature-card:hover {
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.08);
            transform: translateY(-3px);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* CTA button */
        .btn-gradient {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            box-shadow: 0 0 24px rgba(99,102,241,0.35);
            transition: box-shadow 0.2s ease, transform 0.15s ease;
        }
        .btn-gradient:hover {
            box-shadow: 0 0 36px rgba(99,102,241,0.55);
            transform: translateY(-1px);
        }
        .btn-outline {
            background: rgba(30,41,59,0.6);
            border: 1px solid rgba(51,65,85,0.8);
            transition: border-color 0.2s ease, background 0.2s ease;
        }
        .btn-outline:hover {
            border-color: rgba(99,102,241,0.5);
            background: rgba(30,41,59,0.9);
        }

        /* Divider line */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.3), transparent);
        }

        /* Stat item */
        .stat-item {
            border-right: 1px solid rgba(51,65,85,0.5);
        }
        .stat-item:last-child { border-right: none; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .au  { animation: fadeUp 0.55s cubic-bezier(0.4,0,0.2,1) both; }
        .d1  { animation-delay: 0.05s; } .d2 { animation-delay: 0.12s; }
        .d3  { animation-delay: 0.19s; } .d4 { animation-delay: 0.26s; }
        .d5  { animation-delay: 0.33s; } .d6 { animation-delay: 0.40s; }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }
        .floating { animation: float 4s ease-in-out infinite; }
    </style>
</head>

<body class="bg-slate-950 text-slate-300 min-h-screen antialiased">

    <div class="glow-1"></div>
    <div class="glow-2"></div>

    <div class="bg-grid min-h-screen flex flex-col">

        {{-- ── Navbar ── --}}
        <header class="border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-6 h-14 flex items-center justify-between">

                <a href="/" class="flex items-center gap-2.5 group">
                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-sky-400 to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-all duration-300">
                        <i class="ri-box-3-fill text-white text-sm"></i>
                    </span>
                    <span class="font-display font-700 text-white text-base tracking-tight">Komponen</span>
                    <span class="font-mono-custom text-[10px] text-slate-600 hidden sm:inline">v2.0</span>
                </a>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="btn-gradient px-4 py-2 rounded-lg text-sm font-medium text-white flex items-center gap-2">
                            <i class="ri-layout-grid-fill text-xs"></i>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="btn-outline px-4 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white transition-colors">
                            Masuk
                        </a>
                    @endauth
                </div>

            </div>
        </header>

        {{-- ── Hero ── --}}
        <main class="flex-1 flex flex-col items-center justify-center px-6 py-20 text-center">

            {{-- Badge --}}
            <div class="au d1 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs font-mono-custom text-indigo-300 mb-8">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                Sistem Manajemen Komponen · v2.0
            </div>

            {{-- Headline --}}
            <h1 class="au d2 font-display font-800 text-4xl sm:text-5xl lg:text-6xl text-white leading-tight tracking-tight max-w-3xl mb-6">
                Kelola Komponen <br>
                <span class="gradient-text">Lebih Efisien</span>
            </h1>

            <p class="au d3 text-slate-400 text-base sm:text-lg max-w-xl leading-relaxed mb-10">
                Platform terpusat untuk manajemen inventaris komponen, mutasi barang, dan monitoring stok antar departemen secara real-time.
            </p>

            {{-- CTA --}}
            <div class="au d4 flex items-center gap-3 mb-16">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="btn-gradient px-6 py-3 rounded-xl text-sm font-medium text-white flex items-center gap-2">
                        <i class="ri-layout-grid-fill"></i>
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="btn-gradient px-6 py-3 rounded-xl text-sm font-medium text-white flex items-center gap-2">
                        <i class="ri-login-box-line"></i>
                        Masuk Sekarang
                    </a>
                    <a href="/komponen"
                       class="btn-outline px-6 py-3 rounded-xl text-sm font-medium text-slate-300 hover:text-white flex items-center gap-2 transition-colors">
                        <i class="ri-eye-line"></i>
                        Lihat Sistem
                    </a>
                @endauth
            </div>

            {{-- Stats bar --}}
            <div class="au d5 w-full max-w-2xl rounded-2xl bg-slate-900/60 border border-slate-800/60 backdrop-blur-md grid grid-cols-3 mb-20">
                <div class="stat-item px-6 py-5 text-center">
                    <p class="font-display font-700 text-white text-2xl">5</p>
                    <p class="font-mono-custom text-[11px] text-slate-500 uppercase tracking-wider mt-1">Departemen</p>
                </div>
                <div class="stat-item px-6 py-5 text-center">
                    <p class="font-display font-700 text-white text-2xl">8</p>
                    <p class="font-mono-custom text-[11px] text-slate-500 uppercase tracking-wider mt-1">Jenis Komponen</p>
                </div>
                <div class="px-6 py-5 text-center">
                    <p class="font-display font-700 text-white text-2xl">Real-time</p>
                    <p class="font-mono-custom text-[11px] text-slate-500 uppercase tracking-wider mt-1">Monitoring Stok</p>
                </div>
            </div>

            {{-- Feature cards --}}
            <div class="au d6 w-full max-w-4xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-16">

                <div class="feature-card rounded-2xl p-6 text-left">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 text-sky-400"
                         style="background:rgba(14,165,233,0.12);">
                        <i class="ri-cpu-line text-lg"></i>
                    </div>
                    <h3 class="font-display font-700 text-white text-sm mb-2">Master Komponen</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Kelola data komponen lengkap dengan kode, tipe, satuan, lokasi rak, dan stok minimal.
                    </p>
                </div>

                <div class="feature-card rounded-2xl p-6 text-left">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 text-indigo-400"
                         style="background:rgba(99,102,241,0.12);">
                        <i class="ri-arrow-left-right-fill text-lg"></i>
                    </div>
                    <h3 class="font-display font-700 text-white text-sm mb-2">Mutasi Barang</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Catat setiap pergerakan komponen masuk dan keluar antar departemen secara akurat.
                    </p>
                </div>

                <div class="feature-card rounded-2xl p-6 text-left">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 text-emerald-400"
                         style="background:rgba(52,211,153,0.12);">
                        <i class="ri-community-fill text-lg"></i>
                    </div>
                    <h3 class="font-display font-700 text-white text-sm mb-2">Manajemen Departemen</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Atur departemen dan kepemilikan komponen untuk visibilitas inventaris yang jelas.
                    </p>
                </div>

                <div class="feature-card rounded-2xl p-6 text-left">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 text-amber-400"
                         style="background:rgba(251,191,36,0.12);">
                        <i class="ri-error-warning-fill text-lg"></i>
                    </div>
                    <h3 class="font-display font-700 text-white text-sm mb-2">Peringatan Stok Rendah</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Notifikasi otomatis ketika stok komponen jatuh di bawah batas minimal yang ditentukan.
                    </p>
                </div>

                <div class="feature-card rounded-2xl p-6 text-left">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 text-rose-400"
                         style="background:rgba(251,113,133,0.12);">
                        <i class="ri-pie-chart-2-fill text-lg"></i>
                    </div>
                    <h3 class="font-display font-700 text-white text-sm mb-2">Dashboard Analitik</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Visualisasi tren mutasi 6 bulan terakhir dengan grafik interaktif berbasis Chart.js.
                    </p>
                </div>

                <div class="feature-card rounded-2xl p-6 text-left">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 text-purple-400"
                         style="background:rgba(192,132,252,0.12);">
                        <i class="ri-settings-3-line text-lg"></i>
                    </div>
                    <h3 class="font-display font-700 text-white text-sm mb-2">Backup & Restore</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Fitur backup database untuk keamanan data inventaris komponen yang tersimpan.
                    </p>
                </div>

            </div>

            <div class="divider w-full max-w-4xl mb-8 au d6"></div>

        </main>

        {{-- ── Footer ── --}}
        <footer class="border-t border-slate-800/60 py-5">
            <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-gradient-to-br from-sky-400 to-indigo-500 flex items-center justify-center">
                        <i class="ri-box-3-fill text-white text-[10px]"></i>
                    </span>
                    <span class="font-mono-custom text-xs text-slate-600">Sistem Manajemen Komponen &copy; {{ date('Y') }}</span>
                </div>
                <span class="font-mono-custom text-xs text-slate-700">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} · PHP v{{ PHP_VERSION }}
                </span>
            </div>
        </footer>

    </div>

</body>
</html>
@extends('layouts.app')

@section('content')

<style>
    .stat-card {
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(51, 65, 85, 0.6);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }
    .stat-card:hover {
        border-color: rgba(99, 102, 241, 0.4);
        box-shadow: 0 0 24px rgba(99, 102, 241, 0.08);
        transform: translateY(-2px);
    }
    .chart-card {
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(51, 65, 85, 0.6);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }
    .icon-badge {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .trend-up   { color: #34d399; background: rgba(52,211,153,0.12); }
    .trend-down { color: #f87171; background: rgba(248,113,113,0.12); }
    .trend-neu  { color: #94a3b8; background: rgba(148,163,184,0.10); }

    .data-table th {
        color: #64748b; font-size: 0.7rem;
        font-family: 'DM Mono', monospace;
        text-transform: uppercase; letter-spacing: 0.08em;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(51,65,85,0.5);
        font-weight: 500;
    }
    .data-table td {
        padding: 0.8rem 1rem; font-size: 0.875rem;
        border-bottom: 1px solid rgba(30,41,59,0.6);
        color: #cbd5e1;
    }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: rgba(30,41,59,0.4); }

    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 9999px;
        font-size: 0.7rem; font-family: 'DM Mono', monospace; font-weight: 500;
    }
    .status-masuk  { background: rgba(52,211,153,0.12); color: #34d399; border: 1px solid rgba(52,211,153,0.2); }
    .status-keluar { background: rgba(248,113,113,0.12); color: #f87171; border: 1px solid rgba(248,113,113,0.2); }
    .small-badge   { padding: 3px 8px; border-radius: 8px; }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .au { animation: fadeUp 0.45s cubic-bezier(0.4,0,0.2,1) both; }
    .d1 { animation-delay: 0.05s; } .d2 { animation-delay: 0.10s; }
    .d3 { animation-delay: 0.15s; } .d4 { animation-delay: 0.20s; }
    .d5 { animation-delay: 0.25s; } .d6 { animation-delay: 0.30s; }
</style>

{{-- ── Page Header ──────────────────────────────────────────────── --}}
<div class="mb-8 au d1">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display font-700 text-white text-2xl tracking-tight">Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1 font-mono-custom">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <div class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800/60 border border-slate-700/50 text-xs font-mono-custom text-slate-400">
            <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-sm shadow-emerald-400/50 animate-pulse"></span>
            Live
        </div>
    </div>
</div>

{{-- ── Stat Cards ───────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="stat-card rounded-2xl p-5 au d1">
        <div class="flex items-start justify-between mb-4">
            <div class="icon-badge" style="background:rgba(14,165,233,0.15);color:#38bdf8;">
                <i class="ri-cpu-line"></i>
            </div>
            <span class="trend-neu status-badge small-badge text-[11px]">
                <i class="ri-database-2-line text-xs"></i> Item
            </span>
        </div>
        <p class="font-mono-custom text-[11px] text-slate-500 uppercase tracking-wider mb-1">Total Komponen</p>
        <p class="font-display font-700 text-white text-3xl tracking-tight">{{ number_format($totalKomponen) }}</p>
        <p class="text-xs text-slate-600 mt-1">jenis komponen terdaftar</p>
    </div>

    <div class="stat-card rounded-2xl p-5 au d2">
        <div class="flex items-start justify-between mb-4">
            <div class="icon-badge" style="background:rgba(99,102,241,0.15);color:#a5b4fc;">
                <i class="ri-community-fill"></i>
            </div>
            <span class="trend-neu status-badge small-badge text-[11px]">
                <i class="ri-checkbox-circle-line text-xs"></i> Aktif
            </span>
        </div>
        <p class="font-mono-custom text-[11px] text-slate-500 uppercase tracking-wider mb-1">Departemen</p>
        <p class="font-display font-700 text-white text-3xl tracking-tight">{{ $totalDepartemen }}</p>
        <p class="text-xs text-slate-600 mt-1">departemen terdaftar</p>
    </div>

    <div class="stat-card rounded-2xl p-5 au d3">
        <div class="flex items-start justify-between mb-4">
            <div class="icon-badge" style="background:rgba(52,211,153,0.15);color:#34d399;">
                <i class="ri-arrow-left-right-fill"></i>
            </div>
            <span class="trend-up status-badge small-badge text-[11px]">
                <i class="ri-calendar-line text-xs"></i> {{ now()->translatedFormat('M Y') }}
            </span>
        </div>
        <p class="font-mono-custom text-[11px] text-slate-500 uppercase tracking-wider mb-1">Mutasi Bulan Ini</p>
        <p class="font-display font-700 text-white text-3xl tracking-tight">{{ $mutasiBulanIni }}</p>
        <p class="text-xs text-slate-600 mt-1">transaksi tercatat</p>
    </div>

    <div class="stat-card rounded-2xl p-5 au d4">
        <div class="flex items-start justify-between mb-4">
            <div class="icon-badge" style="background:rgba(248,113,113,0.15);color:#f87171;">
                <i class="ri-error-warning-fill"></i>
            </div>
            @if($stokRendah > 0)
                <span class="trend-down status-badge small-badge text-[11px]">
                    <i class="ri-alert-line text-xs"></i> Perlu cek
                </span>
            @else
                <span class="trend-up status-badge small-badge text-[11px]">
                    <i class="ri-check-line text-xs"></i> Aman
                </span>
            @endif
        </div>
        <p class="font-mono-custom text-[11px] text-slate-500 uppercase tracking-wider mb-1">Stok Rendah</p>
        <p class="font-display font-700 text-3xl tracking-tight {{ $stokRendah > 0 ? 'text-red-400' : 'text-white' }}">{{ $stokRendah }}</p>
        <p class="text-xs text-slate-600 mt-1">item di bawah stok minimal</p>
    </div>

</div>

{{-- ── Chart + Top Komponen ─────────────────────────────────────── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-4">

    <div class="xl:col-span-2 chart-card rounded-2xl p-6 au d5">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display font-700 text-white text-base tracking-tight">Tren Mutasi Barang</h2>
                <p class="text-xs text-slate-500 font-mono-custom mt-0.5">6 bulan terakhir · total unit</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5 text-xs text-slate-500">
                    <span class="w-3 h-0.5 rounded-full inline-block" style="background:#38bdf8;"></span>Masuk
                </span>
                <span class="flex items-center gap-1.5 text-xs text-slate-500">
                    <span class="w-3 h-0.5 rounded-full inline-block" style="background:#f87171;"></span>Keluar
                </span>
            </div>
        </div>
        <div class="relative" style="height:260px;">
            <canvas id="mutasiChart"></canvas>
        </div>
    </div>

    <div class="chart-card rounded-2xl p-6 au d6">
        <div class="mb-5">
            <h2 class="font-display font-700 text-white text-base tracking-tight">Top Komponen</h2>
            <p class="text-xs text-slate-500 font-mono-custom mt-0.5">Volume mutasi terbesar</p>
        </div>
        @php $barColors = ['#38bdf8','#818cf8','#34d399','#fbbf24','#f87171']; @endphp
        @forelse($topKomponen as $i => $item)
        <div class="{{ !$loop->last ? 'mb-4' : '' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-sm text-slate-300 truncate max-w-[160px]">{{ $item['name'] }}</span>
                <span class="font-mono-custom text-xs text-slate-500 shrink-0 ml-2">{{ $item['count'] }} unit</span>
            </div>
            <div class="h-1.5 rounded-full bg-slate-800 overflow-hidden">
                <div class="h-full rounded-full" style="width:{{ $item['pct'] }}%; background:{{ $barColors[$i % count($barColors)] }};"></div>
            </div>
        </div>
        @empty
        <p class="text-sm text-slate-600 font-mono-custom py-4 text-center">Belum ada data mutasi.</p>
        @endforelse
    </div>

</div>

{{-- ── Stok Rendah Alert ────────────────────────────────────────── --}}
@if($stokRendah > 0)
<div class="chart-card rounded-2xl p-4 mb-4 au d6" style="border-color:rgba(248,113,113,0.25);">
    <div class="flex items-center gap-3">
        <div class="icon-badge shrink-0" style="background:rgba(248,113,113,0.15);color:#f87171;width:36px;height:36px;border-radius:10px;">
            <i class="ri-alarm-warning-fill text-sm"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-red-300">Peringatan Stok Rendah</p>
            <p class="text-xs text-slate-500 font-mono-custom mt-0.5">
                Terdapat <span class="text-red-400 font-medium">{{ $stokRendah }}</span> komponen dengan stok di bawah batas minimal.
            </p>
        </div>
        <a href="/komponen" class="shrink-0 text-xs font-mono-custom text-red-400 hover:text-red-300 transition-colors flex items-center gap-1">
            Cek sekarang <i class="ri-arrow-right-line"></i>
        </a>
    </div>
</div>
@endif

{{-- ── Mutasi Terbaru ───────────────────────────────────────────── --}}
<div class="chart-card rounded-2xl au d6">
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-800/60">
        <div>
            <h2 class="font-display font-700 text-white text-base tracking-tight">Mutasi Terbaru</h2>
            <p class="text-xs text-slate-500 font-mono-custom mt-0.5">10 transaksi terakhir</p>
        </div>
        <a href="/mutasi" class="text-xs font-mono-custom text-sky-400 hover:text-sky-300 transition-colors flex items-center gap-1">
            Lihat semua <i class="ri-arrow-right-line"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table w-full">
            <thead>
                <tr>
                    <th class="text-left">Tanggal</th>
                    <th class="text-left">Kode</th>
                    <th class="text-left">Komponen</th>
                    <th class="text-left">Dari</th>
                    <th class="text-left">Ke</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-left">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentMutasi as $row)
                <tr>
                    <td class="font-mono-custom text-xs text-slate-500 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}
                    </td>
                    <td class="font-mono-custom text-xs text-sky-400 whitespace-nowrap">
                        {{ $row->komponen->kode_komponen ?? '-' }}
                    </td>
                    <td class="font-medium text-slate-200 whitespace-nowrap">
                        {{ $row->komponen->nama_komponen ?? '-' }}
                    </td>
                    <td class="text-slate-400 text-xs whitespace-nowrap">
                        {{ $row->departemenAsal->nama_departemen ?? '-' }}
                    </td>
                    <td class="text-slate-400 text-xs whitespace-nowrap">
                        {{ $row->departemenTujuan->nama_departemen ?? '-' }}
                    </td>
                    <td class="text-right font-mono-custom text-slate-300 whitespace-nowrap">
                        {{ number_format($row->jumlah) }}
                        <span class="text-slate-600 text-[10px] ml-0.5">{{ $row->komponen->satuan ?? '' }}</span>
                    </td>
                    <td class="text-center whitespace-nowrap">
                        @if($row->jenis === 'masuk')
                            <span class="status-badge status-masuk">
                                <i class="ri-arrow-down-line text-[10px]"></i>Masuk
                            </span>
                        @else
                            <span class="status-badge status-keluar">
                                <i class="ri-arrow-up-line text-[10px]"></i>Keluar
                            </span>
                        @endif
                    </td>
                    <td class="text-slate-500 text-xs max-w-[160px] truncate">
                        {{ $row->keterangan ?: '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-slate-600 font-mono-custom text-sm py-10">
                        <i class="ri-inbox-line text-3xl block mb-2 opacity-40"></i>
                        Belum ada data mutasi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="{{ asset('js/chart.js') }}"></script>
<script>
(function () {
    const labels  = {!! json_encode($chartLabels) !!};
    const dMasuk  = {!! json_encode($chartMasuk)  !!};
    const dKeluar = {!! json_encode($chartKeluar) !!};

    const ctx = document.getElementById('mutasiChart').getContext('2d');

    const gradMasuk = ctx.createLinearGradient(0, 0, 0, 260);
    gradMasuk.addColorStop(0, 'rgba(56,189,248,0.25)');
    gradMasuk.addColorStop(1, 'rgba(56,189,248,0.00)');

    const gradKeluar = ctx.createLinearGradient(0, 0, 0, 260);
    gradKeluar.addColorStop(0, 'rgba(248,113,113,0.20)');
    gradKeluar.addColorStop(1, 'rgba(248,113,113,0.00)');

    Chart.defaults.color       = '#64748b';
    Chart.defaults.font.family = "'DM Mono', monospace";
    Chart.defaults.font.size   = 11;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Masuk',
                    data: dMasuk,
                    borderColor: '#38bdf8',
                    backgroundColor: gradMasuk,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#38bdf8',
                    pointBorderColor: '#0f172a',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    tension: 0.42,
                    fill: true,
                },
                {
                    label: 'Keluar',
                    data: dKeluar,
                    borderColor: '#f87171',
                    backgroundColor: gradKeluar,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#f87171',
                    pointBorderColor: '#0f172a',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    tension: 0.42,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    borderColor: '#334155',
                    borderWidth: 1,
                    titleColor: '#94a3b8',
                    bodyColor: '#e2e8f0',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y} unit`,
                    },
                },
            },
            scales: {
                x: {
                    grid: { color: 'rgba(30,41,59,0.7)', drawTicks: false },
                    border: { display: false },
                    ticks: { padding: 8 },
                },
                y: {
                    grid: { color: 'rgba(30,41,59,0.7)', drawTicks: false },
                    border: { display: false },
                    ticks: { padding: 8, stepSize: 5 },
                    beginAtZero: true,
                },
            },
        },
    });
})();
</script>

@endsection
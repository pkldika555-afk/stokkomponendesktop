@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-950 text-gray-100 font-sans">
    <div class="max-w-8xl mx-auto py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-indigo-400 font-semibold mb-0.5">Gudang</p>
                <h2 class="text-2xl font-bold text-white">Rekap Stok</h2>
                <p class="text-gray-500 text-xs mt-0.5">
                    Status stok seluruh komponen &mdash;
                    <span id="labelPeriode">{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('mutasi.rekap') }}"
                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-700 text-gray-400 hover:text-gray-200 text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    History Mutasi
                </a>

                <button onclick="document.getElementById('modalExport').classList.remove('hidden')"
                    class="flex items-center gap-1.5 bg-emerald-700 hover:bg-emerald-600 text-white rounded-xl px-4 py-2 text-sm font-semibold transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export .xlsx
                </button>
            </div>
        </div>

        <form method="GET" action="{{ route('mutasi.rekap') }}" class="flex items-center gap-3 mb-6">
            <div class="flex items-center gap-2 bg-gray-900 border border-gray-800 rounded-xl px-4 py-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <select name="bulan" onchange="this.form.submit()"
                    class="bg-transparent text-gray-200 text-sm focus:outline-none cursor-pointer">
                    @foreach(range(1, 12) as $b)
                        <option value="{{ $b }}" {{ $b == $bulan ? 'selected' : '' }}
                            class="bg-gray-900">
                            {{ \Carbon\Carbon::createFromDate(null, $b, 1)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="tahun" onchange="this.form.submit()"
                    class="bg-transparent text-gray-200 text-sm focus:outline-none cursor-pointer">
                    @foreach(range(now()->year, now()->year - 4) as $t)
                        <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}
                            class="bg-gray-900">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
        </form>


        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <p class="text-xs text-gray-500 mb-1">Total Komponen</p>
                <p class="text-2xl font-bold text-white">{{ $totalKomponen }}</p>
                <p class="text-xs text-gray-600 mt-0.5">jenis item</p>
            </div>
            <div class="bg-gray-900 border {{ $stokRendah > 0 ? 'border-rose-900/50' : 'border-gray-800' }} rounded-xl p-4">
                <p class="text-xs text-gray-500 mb-1">Stok Rendah</p>
                <p class="text-2xl font-bold {{ $stokRendah > 0 ? 'text-rose-400' : 'text-gray-400' }}">{{ $stokRendah }}</p>
                <p class="text-xs text-gray-600 mt-0.5">perlu restock</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <p class="text-xs text-gray-500 mb-1">Masuk Bulan Ini</p>
                <p class="text-2xl font-bold text-emerald-400">+{{ number_format($totalMasuk) }}</p>
                <p class="text-xs text-gray-600 mt-0.5">unit masuk</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <p class="text-xs text-gray-500 mb-1">Keluar Bulan Ini</p>
                <p class="text-2xl font-bold text-amber-400">-{{ number_format($totalKeluar) }}</p>
                <p class="text-xs text-gray-600 mt-0.5">unit keluar</p>
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            <div class="md:col-span-2 bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-200">Mutasi Harian</p>
                        <p class="text-xs text-gray-600 mt-0.5">Masuk & keluar per hari bulan ini</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Masuk
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Keluar
                        </span>
                    </div>
                </div>
                <div class="h-48">
                    <canvas id="chartMutasi"></canvas>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-200">Status Stok</p>
                    <p class="text-xs text-gray-600 mt-0.5">Distribusi kondisi inventori</p>
                </div>
                <div class="h-36 flex items-center justify-center">
                    <canvas id="chartStatus"></canvas>
                </div>
                <div class="mt-4 space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5 text-gray-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Normal
                        </span>
                        <span class="font-mono text-gray-300">{{ $statusNormal }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5 text-gray-400">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span> Rendah
                        </span>
                        <span class="font-mono text-gray-300">{{ $statusRendah }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5 text-gray-400">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Habis
                        </span>
                        <span class="font-mono text-gray-300">{{ $statusHabis }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-800 flex items-center justify-between">
                <p class="text-sm font-semibold text-gray-200">Status Stok Per Komponen</p>
                @if($stokRendah > 0)
                    <span class="flex items-center gap-1.5 text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-lg">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                        {{ $stokRendah }} stok rendah
                    </span>
                @endif
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Komponen</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Min.</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60">
                    @forelse($komponen as $k)
                        @php
                            $rendah = $k->stok_sekarang <= $k->stok_minimal;
                            $habis  = $k->stok_sekarang <= 0;
                            $statusColor = $habis  ? 'text-rose-400 bg-rose-500/10 border-rose-500/20'
                                         : ($rendah ? 'text-amber-400 bg-amber-500/10 border-amber-500/20'
                                                    : 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20');
                            $statusLabel = $habis  ? 'Habis' : ($rendah ? 'Rendah' : 'Normal');
                        @endphp
                        <tr class="hover:bg-gray-800/30 transition-colors {{ $rendah ? 'bg-rose-950/5' : '' }}">
                            <td class="px-5 py-3.5">
                                <p class="text-gray-200 text-sm font-medium">{{ $k->nama_komponen }}</p>
                                <p class="text-gray-600 text-xs font-mono">{{ $k->kode_komponen }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs text-gray-400 bg-gray-800 px-2 py-0.5 rounded-md">{{ strtoupper($k->tipe) }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500 font-mono">R{{ $k->rak }} / L{{ $k->lokasi }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="font-mono font-bold text-sm {{ $habis ? 'text-rose-400' : ($rendah ? 'text-amber-400' : 'text-gray-200') }}">
                                    {{ number_format($k->stok_sekarang) }}
                                </span>
                                <span class="text-xs text-gray-600 ml-0.5">{{ $k->satuan }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right text-xs text-gray-600 font-mono">{{ number_format($k->stok_minimal) }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg border text-xs font-medium {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <a href="{{ route('mutasi.create') }}?id_komponen={{ $k->id }}"
                                    class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors">
                                    + Mutasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-600 text-sm">Belum ada data komponen</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<div id="modalExport"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
    <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-sm shadow-2xl">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-800">
            <div>
                <p class="text-sm font-semibold text-white">Export Laporan</p>
                <p class="text-xs text-gray-500 mt-0.5">Pilih periode yang ingin diekspor</p>
            </div>
            <button onclick="document.getElementById('modalExport').classList.add('hidden')"
                class="text-gray-600 hover:text-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('backup.excel') }}" method="POST" class="px-5 py-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5">Bulan</label>
                <select name="bulan"
                    class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach(range(1, 12) as $b)
                        <option value="{{ $b }}" {{ $b == $bulan ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $b, 1)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5">Tahun</label>
                <select name="tahun"
                    class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach(range(now()->year, now()->year - 4) as $t)
                        <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-start gap-2 bg-indigo-950/40 border border-indigo-800/30 rounded-lg px-3 py-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
                </svg>
                <p class="text-xs text-indigo-300 leading-relaxed">
                    File berisi <span class="font-semibold">4 sheet</span>: Ringkasan + chart, Master Komponen, Mutasi, dan Rekap Stok.
                </p>
            </div>
            <div class="flex gap-2 pt-1">
                <button type="button"
                    onclick="document.getElementById('modalExport').classList.add('hidden')"
                    class="flex-1 px-4 py-2 rounded-lg border border-gray-700 text-gray-400 hover:text-gray-200 text-sm transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-1.5 bg-emerald-700 hover:bg-emerald-600 text-white rounded-lg px-4 py-2 text-sm font-semibold transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download
                </button>
            </div>
        </form>
    </div>
</div>


<script src="{{ asset('js/chart.js') }}"></script>
<script>

const mutasiHarian = @json($mutasiHarian);  
const statusData   = {
    normal: {{ $statusNormal }},
    rendah: {{ $statusRendah }},
    habis:  {{ $statusHabis }},
};

const ctxBar = document.getElementById('chartMutasi').getContext('2d');
new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels:   mutasiHarian.map(d => d.tanggal),
        datasets: [
            {
                label: 'Masuk',
                data:  mutasiHarian.map(d => d.masuk),
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderColor:     'rgba(16, 185, 129, 1)',
                borderWidth: 1,
                borderRadius: 3,
            },
            {
                label: 'Keluar',
                data:  mutasiHarian.map(d => d.keluar),
                backgroundColor: 'rgba(244, 63, 94, 0.7)',
                borderColor:     'rgba(244, 63, 94, 1)',
                borderWidth: 1,
                borderRadius: 3,
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                grid:  { color: 'rgba(255,255,255,0.04)' },
                ticks: { color: '#6B7280', font: { size: 10 } },
            },
            y: {
                grid:  { color: 'rgba(255,255,255,0.04)' },
                ticks: { color: '#6B7280', font: { size: 10 } },
                beginAtZero: true,
            },
        },
    },
});

const ctxDoughnut = document.getElementById('chartStatus').getContext('2d');
new Chart(ctxDoughnut, {
    type: 'doughnut',
    data: {
        labels:   ['Normal', 'Rendah', 'Habis'],
        datasets: [{
            data:            [statusData.normal, statusData.rendah, statusData.habis],
            backgroundColor: ['rgba(16,185,129,0.8)', 'rgba(251,191,36,0.8)', 'rgba(244,63,94,0.8)'],
            borderColor:     ['#10B981', '#FBBF24', '#F43F5E'],
            borderWidth: 1.5,
            hoverOffset: 4,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.label}: ${ctx.raw} item`,
                },
            },
        },
    },
});

document.getElementById('modalExport').addEventListener('click', function(e) {
    if (e.target === this) this.classList.add('hidden');
});
</script>
@endsection
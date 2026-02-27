@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-950 text-gray-100 font-sans">
    <div class="max-w-6xl mx-auto px-6 py-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-indigo-400 font-semibold mb-0.5">Gudang</p>
                <h2 class="text-2xl font-bold text-white">Rekap Stok</h2>
                <p class="text-gray-500 text-xs mt-0.5">Status stok seluruh komponen &mdash; bulan {{ now()->translatedFormat('F Y') }}</p>
            </div>
            <a href="{{ route('mutasi.index') }}"
                class="flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-700 text-gray-400 hover:text-gray-200 text-sm font-medium transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                History Mutasi
            </a>
        </div>

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
                            $statusLabel = $habis  ? 'Habis'
                                         : ($rendah ? 'Rendah' : 'Normal');
                        @endphp
                        <tr class="hover:bg-gray-800/30 transition-colors {{ $rendah ? 'bg-rose-950/5' : '' }}">
                            <td class="px-5 py-3.5">
                                <p class="text-gray-200 text-sm font-medium">{{ $k->nama_komponen }}</p>
                                <p class="text-gray-600 text-xs font-mono">{{ $k->kode_komponen }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs text-gray-400 bg-gray-800 px-2 py-0.5 rounded-md">{{ strtoupper($k->tipe) }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500 font-mono">
                                R{{ $k->rak }} / L{{ $k->lokasi }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="font-mono font-bold text-sm {{ $habis ? 'text-rose-400' : ($rendah ? 'text-amber-400' : 'text-gray-200') }}">
                                    {{ number_format($k->stok_sekarang) }}
                                </span>
                                <span class="text-xs text-gray-600 ml-0.5">{{ $k->satuan }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right text-xs text-gray-600 font-mono">
                                {{ number_format($k->stok_minimal) }}
                            </td>
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
@endsection
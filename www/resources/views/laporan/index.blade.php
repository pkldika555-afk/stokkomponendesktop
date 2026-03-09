@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-950 text-gray-100 font-sans">
        <div class="max-w-6xl mx-auto px-6 py-10">
            <div class="mb-8">
                <p class="text-xs uppercase tracking-[0.3em] text-indigo-400 font-semibold mb-1">Inventaris</p>
                <h2 class="text-3xl font-bold text-white">Laporan Transaksi</h2>
            </div>
            <form method="GET" class="bg-gray-900 border border-gray-800 rounded-2xl p-5 mb-8">
                <p class="text-xs uppercase tracking-widest text-gray-500 font-semibold mb-4">Filter Laporan</p>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1 ml-1">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                            class="w-full bg-gray-800 border border-gray-700 text-gray-100 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1 ml-1">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            class="w-full bg-gray-800 border border-gray-700 text-gray-100 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1 ml-1">Komponen</label>
                        <select name="komponen"
                            class="w-full bg-gray-800 border border-gray-700 text-gray-100 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            <option value="">— Semua Komponen —</option>
                            @foreach($komponen as $k)
                                <option value="{{ $k->id }}" {{ request('komponen') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_komponen }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl px-4 py-2.5 text-sm transition-colors duration-200 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="relative bg-gray-900 border border-emerald-800/50 rounded-2xl p-6 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/30 to-transparent pointer-events-none">
                    </div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-emerald-400 font-semibold mb-1">Total Masuk</p>
                            <p class="text-3xl font-bold text-white">{{ number_format($totalMasuk) }}</p>
                            <p class="text-xs text-gray-500 mt-1">unit diterima</p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-emerald-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="relative bg-gray-900 border border-rose-800/50 rounded-2xl p-6 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-900/30 to-transparent pointer-events-none">
                    </div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-rose-400 font-semibold mb-1">Total Keluar</p>
                            <p class="text-3xl font-bold text-white">{{ number_format($totalKeluar) }}</p>
                            <p class="text-xs text-gray-500 mt-1">unit dikeluarkan</p>
                        </div>
                        <div
                            class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-rose-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-200">Riwayat Transaksi</p>
                    <span class="text-xs text-gray-500">{{ $transaksi->count() ?? count($transaksi) }} record</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-800/60 text-xs uppercase tracking-wider text-gray-400">
                                <th class="text-left px-6 py-3 font-semibold">Tanggal</th>
                                <th class="text-left px-6 py-3 font-semibold">Komponen</th>
                                <th class="text-left px-6 py-3 font-semibold">Jenis</th>
                                <th class="text-right px-6 py-3 font-semibold">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($transaksi as $t)
                                <tr class="hover:bg-gray-800/40 transition-colors duration-150">
                                    <td class="px-6 py-4 text-gray-400 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-100 font-medium">
                                        {{ $t->komponen->nama_komponen }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($t->jenis == 'masuk')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                Masuk
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-semibold text-gray-100">
                                        {{ number_format($t->jumlah) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-700" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-gray-500 text-sm">Tidak ada data transaksi</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($transaksi, 'links'))
                    <div class="px-6 py-4 border-t border-gray-800">
                        {{ $transaksi->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
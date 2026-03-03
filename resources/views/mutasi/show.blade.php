@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-950 text-gray-100 font-sans">
        <div class="max-w-xl mx-auto px-6 py-8">

            <div class="mb-6">
                <a href="{{ route('mutasi.index') }}"
                    class="inline-flex items-center gap-1.5 text-gray-500 hover:text-gray-300 text-xs transition-colors mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke History Mutasi
                </a>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-400 font-semibold mb-0.5">Gudang</p>
                        <h2 class="text-2xl font-bold text-white">Detail Mutasi</h2>
                    </div>
                    <code
                        class="text-xs font-mono text-indigo-400 bg-gray-900 border border-gray-800 px-3 py-1.5 rounded-lg">
                        #{{ str_pad($mutasi->id_mutasi, 5, '0', STR_PAD_LEFT) }}
                    </code>
                </div>
            </div>

            @php
                $isMasuk = in_array($mutasi->jenis, \App\Models\MutasiBarang::JENIS_MASUK);
                $jenisColor = match ($mutasi->jenis) {
                    'pembelian' => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
                    'internal' => 'text-amber-400 bg-amber-500/10 border-amber-500/20',
                    'retur' => 'text-sky-400 bg-sky-500/10 border-sky-500/20',
                    'repair_kembali' => 'text-violet-400 bg-violet-500/10 border-violet-500/20',
                    default => 'text-gray-400 bg-gray-800 border-gray-700',
                };
            @endphp
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Jumlah</p>
                <p class="text-5xl font-bold font-mono {{ $isMasuk ? 'text-emerald-400' : 'text-rose-400' }}">
                    {{ $isMasuk ? '+' : '-' }}{{ number_format($mutasi->jumlah) }}
                </p>
                <p class="text-sm text-gray-500 mt-1">{{ $mutasi->komponen->satuan ?? 'unit' }}</p>
                <div class="mt-3">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-lg border text-xs font-medium {{ $jenisColor }}">
                        {{ $mutasi->label_jenis }}
                    </span>
                </div>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
                @php
                    $rows = [
                        ['Komponen', $mutasi->komponen->nama_komponen ?? '-'],
                        ['Kode', $mutasi->komponen->kode_komponen ?? '-'],
                        ['Harga Satuan', number_format($mutasi->komponen->harga ?? 0, 0, ',', '.')],
                        ['Tanggal', date('d F Y', strtotime($mutasi->tanggal))],
                        ['Dari', $mutasi->departemenAsal->nama_departemen ?? '-'],
                        ['Ke', $mutasi->departemenTujuan->nama_departemen ?? '-'],
                        ['Dicatat', $mutasi->created_at?->format('d M Y, H:i') ?? '-'],
                    ];
                @endphp

                @foreach($rows as [$label, $value])
                    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-800/60 last:border-0">
                        <span class="text-xs text-gray-500">{{ $label }}</span>
                        <span class="text-sm text-gray-200 font-medium text-right max-w-xs">{{ $value }}</span>
                    </div>
                @endforeach

                @if($mutasi->keterangan)
                    <div class="px-5 py-3 border-t border-gray-800/60">
                        <p class="text-xs text-gray-500 mb-1">Keterangan</p>
                        <p class="text-sm text-gray-300 leading-relaxed">{{ $mutasi->keterangan }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            background-color: #1f2937;
            border: 1px solid #374151;
            border-radius: 0.5rem;
            height: 2rem;
            display: flex;
            align-items: center;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #4b5563;
        }

        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #6366f1;
            box-shadow: 0 0 0 1px #6366f1;
            outline: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #d1d5db;
            font-size: 0.75rem;
            line-height: 1rem;
            padding-left: 0.75rem;
            padding-right: 2rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6b7280;
            font-size: 0.75rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 0.5rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6b7280 transparent transparent transparent;
            border-width: 4px 4px 0 4px;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #6366f1 transparent;
            border-width: 0 4px 4px 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            color: #6b7280;
            font-size: 1rem;
            margin-right: 0.25rem;
            cursor: pointer;
            transition: color 0.15s;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #f87171;
        }

        .select2-dropdown {
            background-color: #111827;
            border: 1px solid #374151;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 4px 10px -2px rgba(0, 0, 0, 0.4);
            margin-top: 2px;
            overflow: hidden;
        }

        .select2-container--default .select2-search--dropdown {
            padding: 0.5rem;
            border-bottom: 1px solid #1f2937;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #1f2937;
            border: 1px solid #374151;
            border-radius: 0.375rem;
            color: #d1d5db;
            font-size: 0.75rem;
            padding: 0.375rem 0.625rem;
            width: 100%;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 1px #6366f1;
        }

        .select2-results__options {
            padding: 0.25rem;
            max-height: 220px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #374151 transparent;
        }

        .select2-results__options::-webkit-scrollbar { width: 4px; }
        .select2-results__options::-webkit-scrollbar-track { background: transparent; }
        .select2-results__options::-webkit-scrollbar-thumb { background-color: #374151; border-radius: 4px; }

        .select2-container--default .select2-results__option {
            color: #9ca3af;
            font-size: 0.75rem;
            padding: 0.375rem 0.625rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.1s, color 0.1s;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #312e81;
            color: #a5b4fc;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #1e1b4b;
            color: #818cf8;
        }

        .select2-container--default .select2-results__option[aria-selected=true]::before {
            content: "✓ ";
            font-size: 0.65rem;
        }

        .select2-results__message,
        .select2-container--default .select2-results__option[aria-disabled=true] {
            color: #6b7280;
            font-size: 0.75rem;
            padding: 0.5rem 0.625rem;
            font-style: italic;
        }
    </style>

    <div class="min-h-screen bg-gray-950 text-gray-100 font-sans">
        <div class="max-w-8xl mx-auto py-10">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-400 font-semibold mb-0.5">Gudang</p>
                    <h2 class="text-2xl font-bold text-white">Mutasi Barang</h2>
                    <p class="text-gray-500 text-xs mt-0.5">History keluar masuk komponen</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('mutasi.rekap') }}"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-700 text-gray-400 hover:text-gray-200 hover:border-gray-600 text-sm font-medium transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Rekap Stok
                    </a>
                    <a href="{{ route('mutasi.create') }}"
                        class="flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl px-4 py-2 text-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Mutasi
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('mutasi.index') }}"
                class="bg-gray-900 border border-gray-800 rounded-xl px-5 py-4 mb-5">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
<!-- 
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Komponen</label>
                        <select name="id_komponen" id="filter-komponen"
                            class="bg-gray-800 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-xs w-full">
                            <option value="">Semua Komponen</option>
                            @foreach($komponen as $k)
                                <option value="{{ $k->id }}" {{ request('id_komponen') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_komponen }}
                                </option>
                            @endforeach
                        </select>
                    </div> -->

                    <div class="relative">
                        <label class="block text-xs text-gray-500 mb-1">Cari Nama / Kode</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-500 pointer-events-none z-10"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                            </svg>
                            <input type="text" name="search" id="search-komponen" value="{{ request('search') }}"
                                placeholder="Cari nama atau kode..." autocomplete="off"
                                class="w-full bg-gray-800 border border-gray-700 text-gray-300 rounded-lg pl-9 pr-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">

                            <div id="suggestion-box"
                                class="absolute z-50 w-full bg-gray-900 border border-gray-700 rounded-lg mt-1 hidden max-h-48 overflow-y-auto shadow-xl">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jenis</label>
                        <select name="jenis" id="filter-jenis"
                            class="bg-gray-800 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-xs w-full">
                            <option value="">Semua Jenis</option>
                            <option value="masuk"  {{ request('jenis') == 'masuk'  ? 'selected' : '' }}>Masuk</option>
                            <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ request('dari') }}"
                            class="w-full bg-gray-800 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                        <div class="flex gap-2">
                            <input type="date" name="sampai" value="{{ request('sampai') }}"
                                class="w-full bg-gray-800 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg px-3 py-2 text-xs font-medium transition-colors shrink-0">
                                Filter
                            </button>
                        </div>
                    </div>

                </div>

                @if(request()->hasAny(['jenis', 'id_komponen', 'search', 'dari', 'sampai']))
                    <div class="mt-2 text-right">
                        <a href="{{ route('mutasi.index') }}" class="text-xs text-gray-500 hover:text-gray-300 underline">
                            Reset filter
                        </a>
                    </div>
                @endif
            </form>

            <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Komponen</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dari → Ke</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Sekarang</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60">
                        @forelse($mutasi as $m)
                            @php
                                $isMasuk = ($m->jenis === 'masuk');
                                $jenisColor = $isMasuk
                                    ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20'
                                    : 'text-rose-400 bg-rose-500/10 border-rose-500/20';
                            @endphp
                            <tr class="hover:bg-gray-800/30 transition-colors">
                                <td class="px-5 py-3.5">
                                    <p class="text-gray-200 text-sm font-medium">{{ $m->tanggal }}</p>
                                    <p class="text-gray-600 text-xs">{{ $m->created_at?->format('H:i') }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-gray-200 text-sm">{{ $m->komponen->nama_komponen ?? '-' }}</p>
                                    <p class="text-gray-600 text-xs font-mono">{{ $m->komponen->kode_komponen ?? '' }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg border text-xs font-medium {{ $jenisColor }}">
                                        {{ $m->label_jenis }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-gray-400">
                                    <span>{{ $m->departemenAsal->nama_departemen ?? '-' }}</span>
                                    <span class="mx-1 text-gray-700">→</span>
                                    <span>{{ $m->departemenTujuan->nama_departemen ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <span class="font-mono font-semibold text-sm {{ $isMasuk ? 'text-emerald-400' : 'text-rose-400' }}">
                                        {{ $isMasuk ? '+' : '-' }}{{ number_format($m->jumlah) }}
                                    </span>
                                    <p class="text-xs text-gray-600">{{ $m->komponen->satuan ?? 'unit' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-mono font-semibold {{ ($m->komponen->stok ?? 0) > 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                        {{ number_format($m->komponen->stok ?? 0) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-gray-200 text-sm">{{ $m->keterangan ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <a href="{{ route('mutasi.show', $m->id) }}"
                                        class="inline-flex items-center gap-1 text-xs text-indigo-400 hover:text-indigo-300 transition-colors">
                                        Detail
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <div class="text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="text-sm">Belum ada data mutasi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($mutasi->hasPages())
                    {{ $mutasi->links() }}
                @endif
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        const komponenData = @json($komponen->map(fn($k) => ['id' => $k->id, 'nama' => $k->nama_komponen, 'kode' => $k->kode_komponen]));

        const input = document.getElementById('search-komponen');
        const box   = document.getElementById('suggestion-box');

        input.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            box.innerHTML = '';

            if (!q) { box.classList.add('hidden'); return; }

            const filtered = komponenData.filter(k =>
                k.nama.toLowerCase().includes(q) || (k.kode && k.kode.toLowerCase().includes(q))
            ).slice(0, 8);

            if (!filtered.length) { box.classList.add('hidden'); return; }

            filtered.forEach(k => {
                const div = document.createElement('div');
                div.className = 'px-3 py-2 text-xs text-gray-300 hover:bg-indigo-600/30 hover:text-white cursor-pointer flex justify-between items-center';
                div.innerHTML = `<span>${k.nama}</span><code class="text-indigo-400 text-xs bg-gray-800 px-1.5 py-0.5 rounded">${k.kode ?? ''}</code>`;
                div.addEventListener('mousedown', function () {
                    input.value = k.nama;
                    box.classList.add('hidden');
                    input.closest('form').submit();
                });
                box.appendChild(div);
            });

            box.classList.remove('hidden');
        });

        document.addEventListener('click', function (e) {
            if (!input.contains(e.target)) box.classList.add('hidden');
        });

        input.addEventListener('focus', function () {
            if (this.value) this.dispatchEvent(new Event('input'));
        });

        $(document).ready(function () {
            $('#filter-komponen').select2({
                placeholder: "Semua Komponen",
                allowClear: true,
                width: 'resolve',
                dropdownParent: $('form')
            });

            $('#filter-jenis').select2({
                placeholder: "Semua Jenis",
                allowClear: true,
                width: 'resolve',
                dropdownParent: $('form')
            });
        });
    </script>
@endsection
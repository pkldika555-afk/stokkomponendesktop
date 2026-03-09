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

        .select2-container--default .select2-search--dropdown .select2-search__field::placeholder {
            color: #6b7280;
        }

        .select2-results__options {
            padding: 0.25rem;
            max-height: 220px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #374151 transparent;
        }

        .select2-results__options::-webkit-scrollbar {
            width: 4px;
        }

        .select2-results__options::-webkit-scrollbar-track {
            background: transparent;
        }

        .select2-results__options::-webkit-scrollbar-thumb {
            background-color: #374151;
            border-radius: 4px;
        }

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

        .select2-container--default .select2-results__group {
            color: #6366f1;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.5rem 0.625rem 0.25rem;

        }

        .select2-container--default .select2-selection--single[aria-disabled="true"],
        .select2-container--disabled .select2-selection--single {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .select2-container--disabled .select2-selection--single .select2-selection__rendered {
            color: #6b7280 !important;
        }

        .select2-container--disabled .select2-selection--single .select2-selection__arrow b {
            border-color: #4b5563 transparent transparent transparent !important;
        }
    </style>
    <div class="min-h-screen bg-gray-950 text-gray-100 font-sans">
        <div class="max-w-2xl mx-auto px-6 py-8">

            <div class="mb-6">
                <a href="{{ route('mutasi.index') }}"
                    class="inline-flex items-center gap-1.5 text-gray-500 hover:text-gray-300 text-xs transition-colors mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke History Mutasi
                </a>
                <p class="text-xs uppercase tracking-[0.25em] text-indigo-400 font-semibold mb-0.5">Gudang</p>
                <h2 class="text-2xl font-bold text-white">Tambah Mutasi Barang</h2>
                <p class="text-gray-500 text-xs mt-0.5">Catat transaksi keluar/masuk komponen</p>
            </div>

            @if($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 rounded-xl px-4 py-3 mb-5 flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-400 shrink-0 mt-0.5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-rose-400 font-semibold text-xs mb-1">Terdapat kesalahan input</p>
                        <ul class="space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li class="text-rose-300 text-xs">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('mutasi.store') }}" method="POST"
                class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
                @csrf

                <div class="px-6 py-4 border-b border-gray-800">
                    <p class="text-sm font-semibold text-gray-200">Detail Transaksi</p>
                    <p class="text-xs text-gray-500 mt-0.5">Isi semua field yang diperlukan</p>
                </div>

                <div class="px-6 py-5 space-y-4">

                    <div>
                        <label for="id_komponen" class="block text-xs font-medium text-gray-400 mb-1.5">
                            Komponen <span class="text-rose-400">*</span>
                        </label>
                        <select id="id_komponen" name="id_komponen" class="filter-komponen">
                            <option value="">— Pilih Komponen —</option>
                            @foreach($komponen as $k)
                                <option value="{{ $k->id }}" data-stok="{{ $k->stok }}" data-satuan="{{ $k->satuan }}"
                                    data-stok-minimal="{{ $k->stok_minimal }}" data-departemen="{{ $k->departemen_id }}"
                                    data-rak="{{ $k->rak }}" data-lokasi="{{ $k->lokasi }}" {{ old('id_komponen') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_komponen }} ({{ $k->kode_komponen }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_komponen')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror

                        <div id="stok-info"
                            class="hidden mt-2 bg-gray-800/60 border border-gray-700/60 rounded-lg px-3.5 py-2.5 flex items-center justify-between">
                            <span class="text-xs text-gray-500">Stok tersedia</span>
                            <span id="stok-value" class="text-sm font-mono font-semibold text-emerald-400"></span>
                        </div>
                        <div id="rak-info"
                            class="hidden mt-2 bg-gray-800/60 border border-gray-700/60 rounded-lg px-3.5 py-2.5 flex items-center justify-between">
                            <span class="text-xs text-gray-500">Rak</span>
                            <span id="rak-value" class="text-sm font-mono font-semibold text-emerald-400"></span>
                        </div>
                        <div id="lokasi-info"
                            class="hidden mt-2 bg-gray-800/60 border border-gray-700/60 rounded-lg px-3.5 py-2.5 flex items-center justify-between">
                            <span class="text-xs text-gray-500">Lot</span>
                            <span id="lokasi-value" class="text-sm font-mono font-semibold text-emerald-400"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="jenis" class="block text-xs font-medium text-gray-400 mb-1.5">
                                Jenis Mutasi <span class="text-rose-400">*</span>
                            </label>
                            <select id="jenis" name="jenis"
                                class="w-full bg-gray-800 border {{ $errors->has('jenis') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                <option value="">— Pilih Jenis —</option>
                                <option value="masuk" {{ old('jenis') == 'masuk' ? 'selected' : '' }}>📥 Masuk</option>
                                <option value="keluar" {{ old('jenis') == 'keluar' ? 'selected' : '' }}>📤 Keluar</option>
                            </select>
                            @error('jenis')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="tanggal" class="block text-xs font-medium text-gray-400 mb-1.5">
                                Tanggal <span class="text-rose-400">*</span>
                            </label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                                class="w-full bg-gray-800 border {{ $errors->has('tanggal') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            @error('tanggal')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="jumlah" class="block text-xs font-medium text-gray-400 mb-1.5">
                            Jumlah <span class="text-rose-400">*</span>
                        </label>
                        <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" min="1" placeholder="0"
                            class="w-full bg-gray-800 border {{ $errors->has('jumlah') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono">
                        @error('jumlah')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="id_departemen_asal" class="block text-xs font-medium text-gray-400 mb-1.5">
                                Dari (Asal) <span class="text-rose-400">*</span>
                            </label>
                            <select id="id_departemen_asal" name="id_departemen_asal">
                                <option value="">— Pilih —</option>
                                @foreach($departemen as $d)
                                    <option value="{{ $d->id }}" {{ old('id_departemen_asal') == $d->id ? 'selected' : '' }}>
                                        {{ $d->nama_departemen }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_departemen_asal')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="id_departemen_tujuan" class="block text-xs font-medium text-gray-400 mb-1.5">
                                Ke (Tujuan) <span class="text-rose-400">*</span>
                            </label>
                            <select id="id_departemen_tujuan" name="id_departemen_tujuan">
                                <option value="">— Pilih —</option>
                                @foreach($departemen as $d)
                                    <option value="{{ $d->id }}" {{ old('id_departemen_tujuan') == $d->id ? 'selected' : '' }}>
                                        {{ $d->nama_departemen }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_departemen_tujuan')
                                <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="keterangan" class="block text-xs font-medium text-gray-400 mb-1.5">
                            Keterangan
                            <span class="text-gray-600 font-normal">(opsional)</span>
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="2" placeholder="Catatan tambahan..."
                            class="w-full bg-gray-800 border {{ $errors->has('keterangan') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600 resize-none">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="px-6 py-4 border-t border-gray-800 bg-gray-900/50 flex items-center justify-between">
                    <a href="{{ route('mutasi.index') }}"
                        class="px-4 py-2 rounded-xl border border-gray-700 text-gray-400 hover:text-gray-200 hover:border-gray-600 text-sm font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold rounded-xl px-5 py-2 text-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Mutasi
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    <script>
        function updateStokInfo(opt) {
            const panel = document.getElementById('stok-info');
            const valEl = document.getElementById('stok-value');

            if (!opt || !opt.value) { panel.classList.add('hidden'); return; }

            const stok = parseInt(opt.dataset.stok) || 0;
            const stokMinimal = parseInt(opt.dataset.stokMinimal) || 0;
            const satuan = opt.dataset.satuan || 'unit';

            valEl.textContent = `${stok.toLocaleString('id-ID')} ${satuan}`;
            valEl.className = stok <= stokMinimal
                ? 'text-sm font-mono font-semibold text-rose-400'
                : 'text-sm font-mono font-semibold text-emerald-400';
            panel.classList.remove('hidden');
        }

        function updateRakInfo(opt) {
            const panel = document.getElementById('rak-info');
            const valEl = document.getElementById('rak-value');

            if (!opt || !opt.value) { panel.classList.add('hidden'); return; }
            valEl.textContent = opt.dataset.rak || '-';
            panel.classList.remove('hidden');
        }

        function updateLokasiInfo(opt) {
            const panel = document.getElementById('lokasi-info');
            const valEl = document.getElementById('lokasi-value');

            if (!opt || !opt.value) { panel.classList.add('hidden'); return; }
            valEl.textContent = opt.dataset.lokasi || '-';
            panel.classList.remove('hidden');
        }

        function updateLocked() {
            const komponenSel = document.getElementById('id_komponen');
            const jenisSel = document.getElementById('jenis');
            const asalSel = document.getElementById('id_departemen_asal');
            const tujuanSel = document.getElementById('id_departemen_tujuan');

            const komponenOpt = komponenSel.options[komponenSel.selectedIndex];
            const deptId = komponenOpt?.dataset.departemen;
            const jenis = jenisSel.value;

            document.getElementById('id_departemen_asal_hidden')?.remove();
            document.getElementById('id_departemen_tujuan_hidden')?.remove();
            $(asalSel).prop('disabled', false);
            $(tujuanSel).prop('disabled', false);

            $('#id_departemen_asal').trigger('change');
            $('#id_departemen_tujuan').trigger('change');

            if (!deptId || !jenis) return;

            function lockWithHidden(selectEl, hiddenId, hiddenName, value) {
                selectEl.value = value;
                $(selectEl).prop('disabled', true);
                const hid = document.createElement('input');
                hid.type = 'hidden';
                hid.name = hiddenName;
                hid.id = hiddenId;
                hid.value = value;
                selectEl.after(hid);
            }

            if (jenis === 'masuk') {
                lockWithHidden(tujuanSel, 'id_departemen_tujuan_hidden', 'id_departemen_tujuan', deptId);
                $('#id_departemen_tujuan').val(deptId).trigger('change');
            } else if (jenis === 'keluar') {
                lockWithHidden(asalSel, 'id_departemen_asal_hidden', 'id_departemen_asal', deptId);
                $('#id_departemen_asal').val(deptId).trigger('change');
            }
        }

        $(document).ready(function () {

            $('#id_komponen').select2({
                placeholder: "Cari komponen...",
                allowClear: true,
                width: 'resolve',
                dropdownParent: $('form')
            });
            $('#id_departemen_asal').select2({
                placeholder: "Pilih departemen asal...",
                allowClear: true,
                width: 'resolve',
                dropdownParent: $('form')
            });
            $('#id_departemen_tujuan').select2({
                placeholder: "Pilih departemen tujuan...",
                allowClear: true,
                width: 'resolve',
                dropdownParent: $('form')
            });
            const sel = document.getElementById('id_komponen');
            const selectedOpt = sel.options[sel.selectedIndex];
            updateStokInfo(selectedOpt);
            updateRakInfo(selectedOpt);
            updateLokasiInfo(selectedOpt);
            updateLocked();

            $('#id_komponen').on('change', function () {
                const opt = this.options[this.selectedIndex];
                updateStokInfo(opt);
                updateRakInfo(opt);
                updateLokasiInfo(opt);
                updateLocked();
            });

            $('#jenis').on('change', function () {
                updateLocked();
            });
        });
    </script>
@endsection
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
    </style>
    <div class="min-h-screen bg-gray-950 text-gray-100 font-sans">
        <div class="max-w-2xl mx-auto px-6 py-10">

            <div class="mb-8">
                <a href="{{ route('komponen.index') }}"
                    class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-300 text-sm transition-colors mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar Komponen
                </a>
                <p class="text-xs uppercase tracking-[0.3em] text-indigo-400 font-semibold mb-1">Master Data</p>
                <h2 class="text-3xl font-bold text-white">Tambah Komponen</h2>
                <p class="text-gray-500 text-sm mt-1">Isi data komponen baru yang akan ditambahkan ke inventaris</p>
            </div>

            @if($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 rounded-xl px-5 py-4 mb-6">
                    <div class="flex items-center gap-2 text-rose-400 font-semibold text-sm mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Terdapat kesalahan input
                    </div>
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-rose-300 text-xs flex items-start gap-1.5">
                                <span class="mt-0.5 w-1 h-1 rounded-full bg-rose-400 shrink-0"></span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('komponen.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
                @csrf
                @method ('POST')
                <div class="px-6 py-5 border-b border-gray-800">
                    <p class="text-sm font-semibold text-gray-200">Informasi Komponen</p>
                    <p class="text-xs text-gray-500 mt-0.5">Lengkapi semua field yang diperlukan</p>
                </div>

                <div class="px-6 py-6 space-y-5">

                    <div>
                        <label for="nama_komponen" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Nama Komponen <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" id="nama_komponen" name="nama_komponen" value="{{ old('nama_komponen') }}"
                            placeholder="Contoh: Resistor 10K Ohm"
                            class="w-full bg-gray-800 border {{ $errors->has('nama_komponen') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600">
                        @error('nama_komponen')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Gambar Komponen</label>
                        <input for="gambar" type="file" name="gambar" accept="image/*" id="gambar"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-sm file:bg-indigo-600 file:text-white file:border-0 file:rounded file:px-4 file:py-1">
                        @error('gambar') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror

                        <div id="image-preview" class="mt-3 hidden">
                            <img id="preview-img" class="max-h-48 rounded-lg object-cover" src="" alt="Preview">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="kode_komponen" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Kode Komponen
                            </label>
                            <input type="text" id="kode_komponen" name="kode_komponen" value="{{ old('kode_komponen') }}"
                                placeholder="Contoh: KMP-001"
                                class="w-full bg-gray-800 border {{ $errors->has('kode') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600 font-mono">
                            @error('kode')
                                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="satuan" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Satuan <span class="text-rose-400">*</span>
                            </label>
                            <select id="satuan" name="satuan"
                                class="w-full bg-gray-800 border {{ $errors->has('satuan') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                <option value="">— Pilih Satuan —</option>
                                @foreach(['pcs', 'unit', 'buah', 'set', 'meter', 'roll', 'kg', 'liter', 'box', 'pak'] as $s)
                                    <option value="{{ $s }}" {{ old('satuan') == $s ? 'selected' : '' }}>{{ strtoupper($s) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('satuan')
                                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="tipe" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Tipe <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" id="tipe" name="tipe" value="{{ old('tipe') }}" placeholder="Contoh: DIP/XMD"
                                class="w-full bg-gray-800 border {{ $errors->has('tipe') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600 font-mono">
                            @error('tipe')
                                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stok_minimal" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Stok Minimum
                                <span class="ml-1 text-xs text-gray-500 font-normal">(alert)</span>
                            </label>

                            <div class="relative">
                                <input type="number" id="stok_minimal" name="stok_minimal"
                                    value="{{ old('stok_minimal', 0) }}" min="0"
                                    class="w-full bg-gray-800 border {{ $errors->has('stok_minimum') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 pr-16 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">unit</span>
                            </div>
                            @error('stok_minimum')
                                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        <div>
                            <label for="rak" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Rak
                            </label>

                            <div class="relative">
                                <input type="number" id="rak" name="rak" value="{{ old('rak', 0) }}" min="0"
                                    class="w-full bg-gray-800 border {{ $errors->has('rak') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 pr-16 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">nomor</span>
                            </div>
                            @error('rak')
                                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="lokasi" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Lot
                            </label>

                            <div class="relative">
                                <input type="number" id="lokasi" name="lokasi" value="{{ old('lokasi', 0) }}" min="0"
                                    class="w-full bg-gray-800 border {{ $errors->has('lokasi') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 pr-16 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">nomor</span>
                            </div>
                            @error('lokasi')
                                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Status <span class="text-rose-400">*</span>
                        </label>
                        <select id="status" name="status"
                            class="w-full bg-gray-800 border {{ $errors->has('status') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            <option value="">— Pilih Status —</option>
                            @foreach(['baru', 'bekas'] as $s)
                                <option value="{{ $s }}">
                                    {{ strtoupper($s) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Harga Satuan (Rp)
                        </label>

                        <div class="relative">
                            <input type="number" id="harga" name="harga"
                                value="{{ old('harga', number_format($komponen->harga ?? 0, 0, ',', '.')) }}" min="0"
                                class="w-full bg-gray-800 border {{ $errors->has('harga') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 pr-16 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">harga</span>
                        </div>
                        @error('harga')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Departemen</label>
                        <select name="departemen_id" class="js-select2-departemen w-full">
                            @foreach($departemen as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-800 bg-gray-900/50 flex items-center justify-between gap-3">
                    <a href="{{ route('komponen.index') }}"
                        class="px-5 py-2.5 rounded-xl border border-gray-700 text-gray-400 hover:text-gray-200 hover:border-gray-600 text-sm font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl px-6 py-2.5 text-sm transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Komponen
                    </button>
                </div>

            </form>

        </div>
    </div>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    </script>
    <script>
        document.querySelector('input[name="gambar"]').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        $(document).ready(function () {
            $('.js-select2-departemen').select2({
                placeholder: "Pilih Departemen",
                allowClear: true,
                dropdownParent: $('form')
            });
        });
    </script>
@endsection
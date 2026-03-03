@extends('layouts.app')

@section('content')
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
                <h2 class="text-3xl font-bold text-white">Edit Komponen</h2>
                <p class="text-gray-500 text-sm mt-1">Perbarui data komponen <span
                        class="text-indigo-400 font-medium">{{ $komponen->nama_komponen }}</span></p>
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

            <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">Stok Saat Ini</p>
                    <p
                        class="text-2xl font-bold {{ ($komponen->stok ?? 0) > ($komponen->stok_minimum ?? 0) ? 'text-emerald-400' : 'text-rose-400' }}">
                        {{ number_format($komponen->stok ?? 0) }}
                    </p>
                    <p class="text-xs text-gray-600 mt-0.5">{{ $komponen->satuan ?? 'unit' }}</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">Terakhir Diubah</p>
                    <p class="text-sm font-semibold text-gray-300">
                        {{ $komponen->updated_at ? $komponen->updated_at->format('d M Y') : '-' }}
                    </p>
                    <p class="text-xs text-gray-600 mt-0.5">
                        {{ $komponen->updated_at ? $komponen->updated_at->format('H:i') : '' }}
                    </p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">ID Komponen</p>
                    <code
                        class="text-sm font-mono text-indigo-400">#{{ str_pad($komponen->id, 4, '0', STR_PAD_LEFT) }}</code>
                    <p class="text-xs text-gray-600 mt-0.5">sistem</p>
                </div>
            </div>

            <form action="{{ route('komponen.update', $komponen->id) }}" method="POST"
                class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
                @csrf
                @method('PUT')

                <div class="px-6 py-5 border-b border-gray-800">
                    <p class="text-sm font-semibold text-gray-200">Informasi Komponen</p>
                    <p class="text-xs text-gray-500 mt-0.5">Ubah data yang diperlukan lalu simpan</p>
                </div>

                <div class="px-6 py-6 space-y-5">

                    <div>
                        <label for="nama_komponen" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Nama Komponen <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" id="nama_komponen" name="nama_komponen"
                            value="{{ old('nama_komponen', $komponen->nama_komponen) }}"
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

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="kode_komponen" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Kode Komponen
                            </label>
                            <input type="text" id="kode_komponen" name="kode_komponen"
                                value="{{ old('kode_komponen', $komponen->kode_komponen) }}" placeholder="Contoh: KMP-001"
                                class="w-full bg-gray-800 border {{ $errors->has('kode') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600 font-mono">
                            @error('kode_komponen')
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
                                    <option value="{{ $s }}" {{ old('satuan', $komponen->satuan) == $s ? 'selected' : '' }}>
                                        {{ strtoupper($s) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('satuan')
                                <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="tipe" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Tipe <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" id="tipe" name="tipe" value="{{ old('tipe', $komponen->tipe) }}"
                            placeholder="Contoh: DDIP"
                            class="w-full bg-gray-800 border {{ $errors->has('tipe') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600 font-mono">
                        @error('tipe')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="stok_minimal" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Stok Minimum
                            <span class="ml-1 text-xs text-gray-500 font-normal">(batas alert stok rendah)</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="stok_minimal" name="stok_minimal"
                                value="{{ old('stok_minimal', $komponen->stok_minimal) }}" min="0"
                                class="w-full bg-gray-800 border {{ $errors->has('stok_minimum') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 pr-16 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">unit</span>
                        </div>
                        @error('stok_minimal')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">
                            Stok Saat Ini
                            <span class="ml-1 text-xs text-gray-500 font-normal">(hanya bisa diubah melalui mutasi
                                barang)</span>
                        </label>
                        <div class="relative">
                            <input type="number" value="{{ $komponen->stok ?? 0 }}" disabled
                                class="w-full bg-gray-800/50 border border-gray-700 text-gray-500 rounded-xl px-4 py-3 pr-16 text-sm cursor-not-allowed font-mono">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-600">unit</span>
                            <div class="absolute right-10 top-1/2 -translate-y-1/2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1.5 text-xs text-gray-600">
                            Untuk mengubah stok, gunakan fitur
                            <a href="{{ route('mutasi.create') }}"
                                class="text-indigo-400 hover:text-indigo-300 underline underline-offset-2">Mutasi
                                Barang</a>
                        </p>
                    </div>
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Harga
                        </label>

                        <div class="relative">
                            <input type="number" id="harga" name="harga" value="{{ old('harga', $komponen->harga) }}" min="0"
                                class="w-full bg-gray-800 border {{ $errors->has('harga') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 pr-16 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">nomor</span>
                        </div>
                        @error('harga')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="rak" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Rak
                        </label>

                        <div class="relative">
                            <input type="number" id="rak" name="rak" value="{{ old('rak', $komponen->rak) }}" min="0"
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
                            <input type="number" id="lokasi" name="lokasi" value="{{ old('lokasi', $komponen->lokasi) }}"
                                min="0"
                                class="w-full bg-gray-800 border {{ $errors->has('lokasi') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 pr-16 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">nomor</span>
                        </div>
                        @error('lokasi')
                            <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="id_departemen" class="block text-xs font-medium text-gray-400 mb-1.5">
                            Bagian <span class="text-rose-400">*</span>
                        </label>
                        <select id="id_departemen" name="id_departemen"
                            class="w-full bg-gray-800 border {{ $errors->has('id_departemen') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            <option value="">— Pilih Bagian —</option>
                            @foreach($departemen as $d)
                                <option value="{{ $d->id }}" {{ old('id', $komponen->id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama_departemen }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_departemen')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                <div class="px-6 py-4 border-t border-gray-800 bg-gray-900/50 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('komponen.index') }}"
                            class="px-5 py-2.5 rounded-xl border border-gray-700 text-gray-400 hover:text-gray-200 hover:border-gray-600 text-sm font-medium transition-colors">
                            Batal
                        </a>

                    </div>
                    <button type="submit"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl px-6 py-2.5 text-sm transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
        </div>

        </form>

    </div>
    </div>
@endsection
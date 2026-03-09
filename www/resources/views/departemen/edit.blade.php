@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-950 text-gray-100 font-sans">
        <div class="max-w-2xl mx-auto px-6 py-10">

            <div class="mb-8">
                <a href="{{ route('departemen.index') }}"
                    class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-300 text-sm transition-colors mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar Departemen
                </a>
                <p class="text-xs uppercase tracking-[0.3em] text-indigo-400 font-semibold mb-1">Master Data</p>
                <h2 class="text-3xl font-bold text-white">Edit Departemen</h2>
                <p class="text-gray-500 text-sm mt-1">Perbarui data departemen <span
                        class="text-indigo-400 font-medium">{{ $departemen->nama_departemen }}</span></p>
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
                    <p class="text-xs text-gray-500 mb-1">Terakhir Diubah</p>
                    <p class="text-sm font-semibold text-gray-300">
                        {{ $departemen->updated_at ? $departemen->updated_at->format('d M Y') : '-' }}
                    </p>
                    <p class="text-xs text-gray-600 mt-0.5">
                        {{ $departemen->updated_at ? $departemen->updated_at->format('H:i') : '' }}
                    </p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">ID Departemen</p>
                    <code
                        class="text-sm font-mono text-indigo-400">#{{ str_pad($departemen->id, 4, '0', STR_PAD_LEFT) }}</code>
                    <p class="text-xs text-gray-600 mt-0.5">sistem</p>
                </div>
            </div>

            <form action="{{ route('departemen.update', $departemen->id) }}" method="POST"
                class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
                @csrf
                @method('PUT')

                <div class="px-6 py-5 border-b border-gray-800">
                    <p class="text-sm font-semibold text-gray-200">Informasi Departemen</p>
                    <p class="text-xs text-gray-500 mt-0.5">Ubah data yang diperlukan lalu simpan</p>
                </div>

                <div class="px-6 py-6 space-y-5">
                    <div>
                        <label for="nama_departemen" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Nama Departemen <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" id="nama_departemen" name="nama_departemen"
                            value="{{ old('nama_departemen', $departemen->nama_departemen) }}" placeholder="Contoh: Sizing"
                            class="w-full bg-gray-800 border {{ $errors->has('nama_departemen') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600">
                        @error('nama_departemen')
                            <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-800 bg-gray-900/50 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('departemen.index') }}"
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

            </form>

        </div>
    </div>
@endsection
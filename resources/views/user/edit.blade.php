@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-950 text-gray-100 font-sans">
        <div class="max-w-2xl mx-auto px-6 py-10">

            <div class="mb-8">
                <a href="{{ route('user.index') }}"
                    class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-300 text-sm transition-colors mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar User
                </a>
                <p class="text-xs uppercase tracking-[0.3em] text-indigo-400 font-semibold mb-1">Master Data</p>
                <h2 class="text-3xl font-bold text-white">Edit User</h2>
                <p class="text-gray-500 text-sm mt-1">Edit data user yang sudah ada</p>
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
            <form action="{{ route('user.update', $user->id) }}" method="POST"
                class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
                @csrf
                @method('PUT')
                <div class="px-6 py-5 border-b border-gray-800">
                    <p class="text-sm font-semibold text-gray-200">Informasi User</p>
                    <p class="text-xs text-gray-500 mt-0.5">Lengkapi semua field yang diperlukan</p>
                </div>

                <div class="px-6 py-6 space-y-5">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Nama User <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                placeholder="Masukkan nama user"
                                class="w-full bg-gray-800 border {{ $errors->has('name') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600">
                            @error('name')
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
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">
                                NRP <span class="text-rose-400">*</span>
                            </label>
                            <input type="number" id="nrp" name="nrp" value="{{ old('nrp', $user->nrp) }}"
                                placeholder="Masukkan NRP user"
                                class="w-full bg-gray-800 border {{ $errors->has('nrp') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600">
                            @error('nrp')
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
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Email <span class="text-rose-400">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                placeholder="Masukkan email user"
                                class="w-full bg-gray-800 border {{ $errors->has('email') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600">
                            @error('email')
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
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">
                                Password (Jika tidak ingin diubah silahkan kosongkan) <span class="text-rose-400">*</span>
                            </label>
                            <input type="password" id="password" name="password" value="{{ old('password') }}"
                                placeholder="Masukkan password user"
                                class="w-full bg-gray-800 border {{ $errors->has('password') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600">
                            @error('password')
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
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Role <span class="text-rose-400">*</span>
                        </label>
                        <select type="role" id="role" name="role" 
                            class="w-full bg-gray-800 border {{ $errors->has('role') ? 'border-rose-500' : 'border-gray-700' }} text-gray-100 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-600">
                            <option value="">---Pilih User----</option>
                                @foreach(['admin', 'user'] as $s)
                                    <option value="{{ $s }}" {{ old('role', $user->role) == $s ? 'selected' : '' }}>
                                        {{ strtoupper($s) }}
                                    </option>
                                @endforeach
                        </select>
                        @error('role')
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
                    <a href="{{ route('user.index') }}"
                        class="px-5 py-2.5 rounded-xl border border-gray-700 text-gray-400 hover:text-gray-200 hover:border-gray-600 text-sm font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl px-6 py-2.5 text-sm transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan User
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
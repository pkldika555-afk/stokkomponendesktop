@extends('layouts.app')
@section('content')

<div class="mb-8 text-center">
    <h1 class="font-display font-700 text-white text-2xl tracking-tight">Pengaturan</h1>
    <p class="text-sm text-slate-500 mt-1 font-mono-custom">Konfigurasi tampilan aplikasi</p>
</div>

<div class="max-w-xl mx-auto">

    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-6 border"
         style="background:rgba(52,211,153,0.08); border-color:rgba(52,211,153,0.2);">
        <i class="ri-checkbox-circle-fill text-emerald-400 text-base shrink-0"></i>
        <p class="text-sm text-emerald-400">{{ session('success') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 px-4 py-3 rounded-xl mb-6 border"
         style="background:rgba(248,113,113,0.08); border-color:rgba(248,113,113,0.2);">
        <i class="ri-error-warning-fill text-red-400 text-base mt-0.5 shrink-0"></i>
        <p class="text-sm text-red-400">{{ $errors->first() }}</p>
    </div>
    @endif

    <div style="background:rgba(15,23,42,0.7); border:1px solid rgba(51,65,85,0.6); backdrop-filter:blur(16px);"
         class="rounded-2xl overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-800/60 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                 style="background:rgba(99,102,241,0.15); color:#a5b4fc;">
                <i class="ri-settings-3-line text-base"></i>
            </div>
            <div>
                <p class="font-display font-700 text-white text-sm tracking-tight">Konfigurasi Aplikasi</p>
                <p class="font-mono-custom text-[11px] text-slate-500 mt-0.5">Ubah pengaturan tampilan sistem</p>
            </div>
        </div>

        <form method="POST" action="{{ route('setting.update') }}" class="px-6 py-6 space-y-5">
            @csrf

            <div>
                <label class="block font-mono-custom text-[11px] text-slate-500 uppercase tracking-wider mb-2">
                    Nama Aplikasi
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="ri-apps-line text-slate-500 text-sm"></i>
                    </span>
                    <input type="text" name="app_name"
                           value="{{ old('app_name', $config['app_name'] ?? '') }}"
                           placeholder="Nama aplikasi..."
                           class="w-full pl-10 pr-4 py-3 rounded-xl text-sm text-slate-200 placeholder-slate-600 outline-none transition-all duration-200"
                           style="background:rgba(30,41,59,0.6); border:1px solid rgba(51,65,85,0.8);"
                           onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                           onblur="this.style.borderColor='rgba(51,65,85,0.8)'; this.style.boxShadow='none'">
                </div>
            </div>

            <div>
                <label class="block font-mono-custom text-[11px] text-slate-500 uppercase tracking-wider mb-2">
                    Judul Kiri Atas
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="ri-layout-top-line text-slate-500 text-sm"></i>
                    </span>
                    <input type="text" name="app_judul"
                           value="{{ old('app_judul', $config['app_judul'] ?? '') }}"
                           placeholder="Judul header kiri atas..."
                           class="w-full pl-10 pr-4 py-3 rounded-xl text-sm text-slate-200 placeholder-slate-600 outline-none transition-all duration-200"
                           style="background:rgba(30,41,59,0.6); border:1px solid rgba(51,65,85,0.8);"
                           onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                           onblur="this.style.borderColor='rgba(51,65,85,0.8)'; this.style.boxShadow='none'">
                </div>
            </div>

            <div>
                <label class="block font-mono-custom text-[11px] text-slate-500 uppercase tracking-wider mb-2">
                    Judul di Tabel Komponen
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="ri-table-line text-slate-500 text-sm"></i>
                    </span>
                    <input type="text" name="app_judult"
                           value="{{ old('app_judult', $config['app_judult'] ?? '') }}"
                           placeholder="Judul tabel komponen..."
                           class="w-full pl-10 pr-4 py-3 rounded-xl text-sm text-slate-200 placeholder-slate-600 outline-none transition-all duration-200"
                           style="background:rgba(30,41,59,0.6); border:1px solid rgba(51,65,85,0.8);"
                           onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                           onblur="this.style.borderColor='rgba(51,65,85,0.8)'; this.style.boxShadow='none'">
                </div>
            </div>

            <div style="height:1px; background:linear-gradient(90deg, transparent, rgba(99,102,241,0.2), transparent);"></div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white transition-all duration-200"
                        style="background:linear-gradient(135deg,#0ea5e9 0%,#6366f1 100%); box-shadow:0 0 20px rgba(99,102,241,0.3);"
                        onmouseover="this.style.boxShadow='0 0 30px rgba(99,102,241,0.5)'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.boxShadow='0 0 20px rgba(99,102,241,0.3)'; this.style.transform='translateY(0)'">
                    <i class="ri-save-line text-sm"></i>
                    Simpan Perubahan
                </button>
                <button type="reset"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white transition-all duration-200"
                        style="background:rgba(30,41,59,0.6); border:1px solid rgba(51,65,85,0.8);"
                        onmouseover="this.style.borderColor='rgba(99,102,241,0.4)'; this.style.color='white'"
                        onmouseout="this.style.borderColor='rgba(51,65,85,0.8)'; this.style.color=''">
                    <i class="ri-refresh-line text-sm"></i>
                    Reset
                </button>
            </div>

        </form>
    </div>

    <div class="mt-4 flex items-start gap-2.5 px-4 py-3 rounded-xl"
         style="background:rgba(14,165,233,0.06); border:1px solid rgba(14,165,233,0.15);">
        <i class="ri-information-line text-sky-400 text-sm mt-0.5 shrink-0"></i>
        <p class="text-xs text-slate-500 leading-relaxed">
            Perubahan pengaturan akan langsung diterapkan setelah disimpan. Refresh halaman jika tampilan belum berubah.
        </p>
    </div>

</div>

@endsection
@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-950 text-gray-100 font-sans">
        <div class="max-w-2xl mx-auto px-6 py-8">

            <div class="mb-7">
                <p class="text-xs uppercase tracking-[0.25em] text-indigo-400 font-semibold mb-0.5">Sistem</p>
                <h2 class="text-2xl font-bold text-white">Backup & Restore</h2>
                <p class="text-gray-500 text-xs mt-1">Pindahkan atau salin data antar perangkat dengan mudah</p>
            </div>

            @if(session('success'))
                <div
                    class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl px-4 py-3 mb-5 flex items-center gap-2.5 text-emerald-400 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 rounded-xl px-4 py-3 mb-5 flex gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-400 shrink-0 mt-0.5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-rose-400 font-semibold text-xs mb-1">Terjadi kesalahan</p>
                        @foreach($errors->all() as $e)
                            <p class="text-rose-300 text-xs">{{ $e }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-3 gap-2.5 mb-6">
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3.5 text-center">
                    <p class="text-2xl font-bold text-white font-mono">{{ $stats['departemen'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Departemen</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3.5 text-center">
                    <p class="text-2xl font-bold text-white font-mono">{{ $stats['komponen'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Komponen</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-3.5 text-center">
                    <p class="text-2xl font-bold text-white font-mono">{{ $stats['mutasi'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Mutasi</p>
                </div>
            </div>

            <div class="space-y-4">

                <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-800">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 bg-indigo-500/15 rounded-xl flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-200">Backup Data</p>
                                <p class="text-xs text-gray-500">Unduh semua data sebagai file backup</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-4 space-y-3">

                        <div
                            class="flex items-center justify-between p-3.5 bg-gray-800/50 rounded-xl border border-gray-700/50">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-indigo-900/50 rounded-lg flex items-center justify-center">
                                    <span class="text-xs font-bold text-indigo-400 font-mono">JSON</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-200 font-medium">File Backup Lengkap</p>
                                    <p class="text-xs text-gray-500">Semua data — untuk restore ke perangkat lain</p>
                                </div>
                            </div>
                            <a href="{{ route('backup.download') }}"
                                class="flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg px-4 py-2 text-xs font-semibold transition-colors shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Backup .json
                            </a>
                        </div>

                        <div
                            class="flex items-center justify-between p-3.5 bg-gray-800/50 rounded-xl border border-gray-700/50">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-emerald-900/50 rounded-lg flex items-center justify-center">
                                    <span class="text-xs font-bold text-emerald-400 font-mono">XLS</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-200 font-medium">Laporan Excel</p>
                                    <p class="text-xs text-gray-500">3 sheet: Komponen, Mutasi, Rekap Stok</p>
                                </div>
                            </div>
                            <button onclick="document.getElementById('modalExport').classList.remove('hidden')"
                                class="flex items-center gap-1.5 bg-emerald-700 hover:bg-emerald-600 text-white rounded-lg px-4 py-2 text-xs font-semibold transition-colors shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Export .xlsx
                            </button>
                        </div>

                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-800">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 bg-amber-500/15 rounded-xl flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-200">Restore Data</p>
                                <p class="text-xs text-gray-500">Pulihkan dari file backup .json</p>
                            </div>
                        </div>
                    </div>
                    <div id="modalExport"
                        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
                        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-sm shadow-2xl">

                            {{-- Header --}}
                            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-800">
                                <div>
                                    <p class="text-sm font-semibold text-white">Export Laporan</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Pilih periode yang ingin diekspor</p>
                                </div>
                                <button onclick="document.getElementById('modalExport').classList.add('hidden')"
                                    class="text-gray-600 hover:text-gray-300 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            {{-- Form --}}
                            <form action="{{ route('backup.excel') }}" method="POST" class="px-5 py-5 space-y-4">
                                @csrf

                                {{-- Bulan --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Bulan</label>
                                    <select name="bulan"
                                        class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        @foreach(range(1, 12) as $b)
                                            <option value="{{ $b }}" {{ $b == now()->month ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::createFromDate(null, $b, 1)->translatedFormat('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Tahun</label>
                                    <select name="tahun"
                                        class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        @foreach(range(now()->year, now()->year - 4) as $t)
                                            <option value="{{ $t }}" {{ $t == now()->year ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div
                                    class="flex items-start gap-2 bg-indigo-950/40 border border-indigo-800/30 rounded-lg px-3 py-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400 mt-0.5 shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
                                    </svg>
                                    <p class="text-xs text-indigo-300 leading-relaxed">
                                        File akan berisi <span class="font-semibold">4 sheet</span>: Ringkasan (+ chart),
                                        Master Komponen, Mutasi Barang, dan Rekap Stok.
                                    </p>
                                </div>
                                <div class="flex gap-2 pt-1">
                                    <button type="button"
                                        onclick="document.getElementById('modalExport').classList.add('hidden')"
                                        class="flex-1 px-4 py-2 rounded-lg border border-gray-700 text-gray-400 hover:text-gray-200 text-sm transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="flex-1 flex items-center justify-center gap-1.5 bg-emerald-700 hover:bg-emerald-600 text-white rounded-lg px-4 py-2 text-sm font-semibold transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="px-5 py-4">

                        <div
                            class="flex items-start gap-2 bg-rose-500/5 border border-rose-500/20 rounded-xl px-3.5 py-3 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-400 shrink-0 mt-0.5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-rose-300/80 leading-relaxed">
                                <span class="font-semibold text-rose-400">Perhatian:</span>
                                Restore akan <strong>menghapus semua data saat ini</strong> dan menggantinya dengan data
                                dari file backup. Pastikan file backup yang diupload benar.
                            </p>
                        </div>

                        <form action="{{ route('backup.restore') }}" method="POST" enctype="multipart/form-data"
                            onsubmit="return confirmRestore()">
                            @csrf

                            <label for="backup_file"
                                class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-gray-700 hover:border-indigo-500/60 rounded-xl px-5 py-8 cursor-pointer transition-colors group mb-4"
                                id="dropzone">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-8 h-8 text-gray-600 group-hover:text-indigo-400 transition-colors" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div class="text-center">
                                    <p class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors"
                                        id="dropzone-label">
                                        <strong>Klik</strong> atau <strong>Drag</strong> untuk pilih file backup
                                    </p>
                                    <p class="text-xs text-gray-600 mt-0.5">Format: .json — Maks. 20MB</p>
                                </div>
                                <input type="file" id="backup_file" name="backup_file" accept=".json" class="hidden"
                                    onchange="updateDropzone(this)">
                            </label>

                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-rose-700 hover:bg-rose-600 active:bg-rose-800 text-white font-semibold rounded-xl py-2.5 text-sm transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Restore Sekarang
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-gray-900/50 border border-gray-800/60 rounded-2xl px-5 py-4">
                    <p class="text-xs font-semibold text-gray-400 mb-3 uppercase tracking-wider">Cara Pindah Data ke PC Lain
                    </p>
                    <ol class="space-y-2">
                        @foreach([
                                ['indigo', '1', 'Di PC lama, klik <strong>Backup .json</strong> — file akan terunduh otomatis'],
                                ['indigo', '2', 'Pindahkan file backup ke PC baru (USB / Google Drive / WhatsApp / dll)'],
                                ['indigo', '3', 'Di PC baru, buka halaman ini lalu upload file backup'],
                                ['indigo', '4', 'Klik <strong>Restore Sekarang</strong> — semua data akan dipulihkan'],
                            ] as [$color, $num, $text])
                                <li class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 bg-indigo-500/20 text-indigo-400 rounded-full text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $num }}</span>
                                        <p class="text-xs text-gray-500 leading-relaxed">{!! $text !!}</p>
                            </li>
                        @endforeach
                </ol>
                    </div>
                </div>
            </div>
        </div>
        <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('backup_file');
        const label = document.getElementById('dropzone-label');

        function updateDropzone(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                label.textContent = '✓ ' + file.name;
                label.classList.add('text-emerald-400');
                label.classList.remove('text-gray-400');
                document.getElementById('dropzone').classList.add('border-emerald-500/40');
                document.getElementById('dropzone').classList.remove('border-gray-700');
            }
        }
            function confirmRestore() {
                const file = document.getElementById('backup_file').files[0];
                if (!file) {
                    alert('Pilih file backup terlebih dahulu.');
                    return false;
        }
            return confirm(
                '⚠️ PERHATIAN!\n\nSemua data yang ada sekarang akan dihapus dan diganti dengan data dari file backup.\n\nLanjutkan restore?'
            );
        }
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, e => e.preventDefault());
            dropzone.addEventListener(eventName, e => e.stopPropagation());
        });
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => {
                dropzone.classList.add('border-emerald-500', 'bg-emerald-500/10');
            });
        });
        ['dragleave', 'drop'].forEach(event => {
            dropzone.addEventListener(event, () => {
                dropzone.classList.remove('border-emerald-500', 'bg-emerald-500/10');
            });
        });
        dropzone.addEventListener('drop', e => {
            const dt = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            fileInput.files = dt.files;

            updateDropzone(fileInput);
        });
        document.getElementById('modalExport').addEventListener('click', function (e) {
            if (e.target === this) this.classList.add('hidden');
        });
        </script>
@endsection
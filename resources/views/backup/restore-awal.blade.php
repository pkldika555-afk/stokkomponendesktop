<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restore Data - Komponen</title>
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font.css') }}">
    @vite('resources/css/app.css', 'resources/js/app.js')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .font-display {
            font-family: 'Syne', sans-serif;
        }

        .font-mono-custom {
            font-family: 'DM Mono', monospace;
        }

        .login-card {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(51, 65, 85, 0.6);
            backdrop-filter: blur(24px);
        }

        .glow-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.07;
            pointer-events: none;
        }

        .glow-blob-1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #f59e0b, transparent);
            top: -200px;
            right: -200px;
        }

        .input-field {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(51, 65, 85, 0.8);
            color: #e2e8f0;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
        }

        @keyframes fadeup {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeup {
            animation: fadeup 0.5s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.05s;
        }

        .delay-2 {
            animation-delay: 0.12s;
        }

        .delay-3 {
            animation-delay: 0.19s;
        }
    </style>
</head>

<body class="bg-slate-950 text-slate-300 min-h-screen flex items-center justify-center px-4">
    <div class="glow-blob glow-blob-1"></div>

    <div class="w-full max-w-sm">

        <div class="flex flex-col items-center mb-8 animate-fadeup">
            <span
                class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/40 mb-4">
                <i class="ri-refresh-line text-white text-2xl"></i>
            </span>
            <h1 class="font-display font-700 text-white text-2xl tracking-tight">Restore Data</h1>
            <p class="font-mono-custom text-xs text-slate-500 mt-1">Fresh install setup</p>
        </div>

        <div class="login-card rounded-2xl p-8 animate-fadeup delay-1">

            <div class="mb-6">
                <h2 class="font-display font-700 text-white text-xl tracking-tight">Pulihkan Data</h2>
                <p class="text-sm text-slate-500 mt-1">Upload file backup .json untuk memulihkan semua data termasuk
                    akun.</p>
            </div>



            <div class="flex items-start gap-2 bg-amber-500/5 border border-amber-500/20 rounded-xl px-3.5 py-3 mb-5">
                <i class="ri-alert-line text-amber-400 shrink-0 mt-0.5"></i>
                <p class="text-xs text-amber-300/80 leading-relaxed">
                    <span class="font-semibold text-amber-400">Perhatian:</span>
                    Semua data yang ada akan dihapus dan diganti dengan data dari file backup.
                </p>
            </div>

            <form action="{{ route('restore.awal') }}" method="POST" enctype="multipart/form-data"
                onsubmit="return confirmRestore()">
                @csrf

                <label for="backup_file"
                    class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-gray-700 hover:border-amber-500/60 rounded-xl px-5 py-8 cursor-pointer transition-colors group mb-4"
                    id="dropzone">
                    <i
                        class="ri-file-upload-line text-3xl text-gray-600 group-hover:text-amber-400 transition-colors"></i>
                    <div class="text-center">
                        <p class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors"
                            id="dropzone-label">
                            <strong>Klik</strong> atau <strong>Drag</strong> file backup
                        </p>
                        <p class="text-xs text-gray-600 mt-0.5">Format: .json — Maks. 20MB</p>
                    </div>
                    <input type="file" id="backup_file" name="backup_file" accept=".json" class="hidden"
                        onchange="updateDropzone(this)">
                </label>

                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold text-white mb-4"
                    style="background: linear-gradient(135deg, #f59e0b, #f97316); box-shadow: 0 0 20px rgba(245,158,11,0.3);">
                    <i class="ri-refresh-line text-base"></i>
                    Restore & Lanjutkan ke Login
                </button>
            </form>
            <div class="p-3.5 bg-gray-800/50 rounded-xl border border-gray-700/50">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 bg-amber-900/50 rounded-lg flex items-center justify-center shrink-0">
                        <span class="text-xs font-bold text-amber-400 font-mono">ZIP</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-200 font-medium">Import Gambar</p>
                        <p class="text-xs text-gray-500">Upload file .zip hasil export gambar</p>
                    </div>
                </div>

                <form action="{{ route('backup.import-images') }}" method="POST" enctype="multipart/form-data"
                    id="import_zip_form">
                    @csrf
                    <label for="zip_file"
                        class="flex items-center gap-3 border border-dashed border-gray-700 hover:border-violet-500/60 rounded-lg px-4 py-3 cursor-pointer transition-colors group mb-3"
                        id="zip_dropzone">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-gray-600 group-hover:text-violet-400 transition-colors shrink-0"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400 group-hover:text-gray-300 transition-colors truncate"
                                id="zip_label"><strong>Klik</strong> atau <strong>Drag</strong> file .zip</p>
                            <p class="text-xs text-gray-600 mt-0.5">Maks. 50MB</p>
                        </div>
                        <input type="file" id="zip_file" name="zip_file" accept=".zip" class="hidden"
                            onchange="updateZipDropzone(this)">
                    </label>

                    <button type="submit"
                        class="w-full flex items-center justify-center gap-1.5 bg-amber-700 hover:bg-amber-600 text-white rounded-lg px-4 py-2 text-xs font-semibold transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12" />
                        </svg>
                        Import Gambar
                    </button>
                </form>
            </div>

            <div class="text-center">
                <a href="/login" class="text-xs text-slate-600 hover:text-slate-400 transition-colors">
                    ← Kembali ke Login
                </a>
            </div>
        </div>

        <p class="text-center text-xs text-slate-600 mt-6 font-mono-custom">
            Sistem Manajemen Komponen &copy; {{ date('Y') }}
        </p>
    </div>


    <script>
        function updateDropzone(input) {
            if (input.files && input.files[0]) {
                const label = document.getElementById('dropzone-label');
                label.innerHTML = '✓ ' + input.files[0].name;
                label.classList.add('text-emerald-400');
                document.getElementById('dropzone').classList.add('border-emerald-500/40');
                document.getElementById('dropzone').classList.remove('border-gray-700');
            }
        }

        // show session flash using swal
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                background: '#0f172a',
                color: '#cbd5e1',
            });
        @endif
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: "{{ $errors->first() }}",
                background: '#0f172a',
                color: '#cbd5e1',
            });
        @endif

            function confirmRestore() {
                if (!document.getElementById('backup_file').files[0]) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih file terlebih dahulu',
                        text: 'Silakan pilih file backup sebelum melanjutkan.',
                        background: '#0f172a',
                        color: '#cbd5e1',
                    });
                    return false;
                }
                Swal.fire({
                    title: '⚠️ PERHATIAN',
                    text: 'Semua data akan dihapus dan diganti dengan data dari file backup.',
                    icon: 'warning',
                    background: '#0f172a',
                    color: '#cbd5e1',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#475569',
                    confirmButtonText: 'Lanjutkan',
                    cancelButtonText: 'Batal',
                }).then(result => {
                    if (result.isConfirmed) {
                        document.querySelector('form[action="{{ route('restore.awal') }}"]').submit();
                    }
                });
                return false;
            }


        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('backup_file');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e => {
            dropzone.addEventListener(e, ev => { ev.preventDefault(); ev.stopPropagation(); });
        });
        ['dragenter', 'dragover'].forEach(e => {
            dropzone.addEventListener(e, () => dropzone.classList.add('border-amber-500', 'bg-amber-500/10'));
        });
        ['dragleave', 'drop'].forEach(e => {
            dropzone.addEventListener(e, () => dropzone.classList.remove('border-amber-500', 'bg-amber-500/10'));
        });
        dropzone.addEventListener('drop', e => {
            const dt = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            fileInput.files = dt.files;
            updateDropzone(fileInput);
        });
        function updateZipDropzone(input) {
            if (input.files && input.files[0]) {
                const label = document.getElementById('zip_label');
                label.innerHTML = '✓ ' + input.files[0].name;
                label.classList.add('text-emerald-400');
                document.getElementById('zip_dropzone').classList.add('border-emerald-500/40');
                document.getElementById('zip_dropzone').classList.remove('border-gray-700');
            }
        }

        const zipDropzone = document.getElementById('zip_dropzone');
        const zipInput = document.getElementById('zip_file');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e => {
            zipDropzone.addEventListener(e, ev => { ev.preventDefault(); ev.stopPropagation(); });
        });
        ['dragenter', 'dragover'].forEach(e => {
            zipDropzone.addEventListener(e, () => zipDropzone.classList.add('border-violet-500', 'bg-violet-500/10'));
        });
        ['dragleave', 'drop'].forEach(e => {
            zipDropzone.addEventListener(e, () => zipDropzone.classList.remove('border-violet-500', 'bg-violet-500/10'));
        });
        zipDropzone.addEventListener('drop', e => {
            const dt = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            zipInput.files = dt.files;
            updateZipDropzone(zipInput);
        });
    </script>
</body>

</html>
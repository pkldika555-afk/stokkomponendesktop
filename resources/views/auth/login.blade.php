<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Komponen</title>
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .font-display {
            font-family: 'Syne', sans-serif;
        }

        .font-mono-costum {
            font-family: 'DM Mono', monospace;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E") pointer-events: none;
            z-index: 9999;
            pointer-events: none;
            opacity: 0.4;
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
            background: radial-gradient(circle, #6366f1, transparent);
            top: -200px;
            left: -200px;
        }

        .glow-blob-2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #0ea5e9, transparent);
            top: -150px;
            left: -150px;
        }

        .login-card {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(51, 65, 85, 0.6);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        .input-field {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(51, 65, 85, 0.8);
            color: #e2e8f0;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .input-field::placeholder {
            color: #475569;
        }

        .input-field:focus {
            outline: none;
            border-color: #6366f1;
            background: rgba(30, 41, 59, 0.9);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .input-field:focus+.input-icon {
            color: #818cf8;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
            transition: box-shadow 0.2s ease, transform 0.15s ease, opacity 0.2s ease;
        }

        .btn-primary:hover {
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.5);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
            opacity: 0.9;
        }

        .toggle-pw {
            color: #475569;
            transition: color 0.2s;
        }

        .toggle-pw:hover {
            color: #94a3b8;
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

        .delay-4 {
            animation-delay: 0.26s;
        }

        .delay-5 {
            animation-delay: 0.33s;
        }
    </style>
</head>

<body class="bg-slate-950 text-slate-300 min-h-screen flex items-center justify-center px-4">
    <div class="glow-blob glow-blob-1"></div>
    <div class="glow-blob glow-blob-2"></div>

    <div class="w-full max-w-sm">

        <div class="flex flex-col items-center mb-8 animate-fadeup">
            <span
                class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-400 to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/40 mb-4">
                <i class="ri-box-3-fill text-white text-2xl"></i>
            </span>

            <h1 class="font-display font-700 text-white text-2xl tracking-tight">Komponen</h1>
            <p class="font-mono-custom text-xs text-slate-500 mt-1">v1.0 system</p>
        </div>

        <div class="login-card rounded-2xl p-8">

            <div class="mb-7 animate-fadeup delay-1">
                <h2 class="font-display font-700 text-white text-xl tracking-tight">Selamat datang kembali</h2>
                <p class="text-sm text-slate-500 mt-1">Masuk ke akun Anda untuk melanjutkan.</p>
            </div>
            @if ($errors->any())
                <div
                    class="mb-5 flex items-start gap-3 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20 animate-fadeup delay-1">
                    <i class="ri-error-warning-fill text-red-400 text-base mt-0.5 shrink-0"></i>
                    <p class="text-sm text-red-400">{{ $errors->first() }}</p>
                    </ul>
                </div>
            @endif
            <form method="POST" action="/login" class="space-y-5">
                @csrf

                <div class="animate-fadeup delay-2">
                    <label for="nrp"
                        class="block text-xs font-medium text-slate-400 mb-2 font-mono-custom uppercase tracking-wider">NRP:</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <i class="ri-id-card-line text-slate-500 text-sm input-icon transition-colors"></i>
                        </span>
                        <input type="text" id="nrp" name="nrp" placeholder="Masukkan nrp anda..."
                            class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-sm" required>
                    </div>
                </div>

                <div class="animate-fadeup delay-3">
                    <label for="password"
                        class="block text-xs font-medium text-slate-400 mb-2 font-mono-custom uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <i class="ri-lock-password-line text-slate-500 text-sm input-icon transition-colors"></i>
                        </span>
                        <input type="password" id="password" name="password" placeholder="Masukkan password anda..."
                            class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-sm" required>
                        <button type="button" id="togglePw"
                            class="toggle-pw absolute inset-y-0 right-0 flex items-center pr-3.5 text-sm">
                            <i class="ri-eye-off-line" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="animate fadeup delay-4 pt-1">
                    <button type="submit"
                        class="btn-primary w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-medium text-white">
                        <i class="ri-login-box-line text-base"></i>
                        <span>Masuk</span>
                    </button>
                </div>
            </form>
        </div>
        <p class="text-center text-xs text-slate-600 mt-6 font-mono-custom animate-fadeup delay-5">
            Sistem Manajemen Komponen &copy; {{ date('Y') }}
        </p>

        <div class="text-center mt-4 animate-fadeup delay-5">
            <a href="{{ route('restore.awal.form') }}"
                class="inline-flex items-center gap-1.5 text-xs text-slate-600 hover:text-amber-400 transition-colors font-mono-custom">
                <i class="ri-refresh-line"></i>
                Fresh install? Restore data dulu
            </a>
        </div>
    </div>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        const togglePw = document.getElementById('togglePw');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        togglePw.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            toggleIcon.classList.toggle('ri-eye-line');
            toggleIcon.classList.toggle('ri-eye-off-line');
        });
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                background: '#0f172a',
                color: '#cbd5e1',
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                background: '#0f172a',
                color: '#cbd5e1',
            });
        @endif
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi kesalahan',
                text: "{{ $errors->first() }}",
                background: '#0f172a',
                color: '#cbd5e1',
            });
        @endif
    </script>
</body>

</html>
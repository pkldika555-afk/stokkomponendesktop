<!-- Fullscreen Preloader dengan tema dark slate + sky-indigo gradient -->
<div id="loading-screen"
     class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-slate-950 overflow-hidden">

    <!-- Subtle noise overlay biar cinematic (sama seperti body::before) -->
    <div class="absolute inset-0 pointer-events-none z-10 opacity-30 mix-blend-overlay"
         style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\"0 0 256 256\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cfilter id=\"noise\"%3E%3CfeTurbulence type=\"fractalNoise\" baseFrequency=\"0.9\" numOctaves=\"4\" stitchTiles=\"stitch\"/%3E%3C/filter%3E%3Crect width=\"100%25\" height=\"100%25\" filter=\"url(%23noise)\" opacity=\"0.035\"/%3E%3C/svg%3E')">
    </div>

    <!-- Logo + Icon utama (pakai gradient sky-indigo) -->
    <div id="loading-logo" class="relative mb-10 z-20">
        <div class="w-28 h-28 md:w-36 md:h-36 rounded-2xl bg-gradient-to-br from-sky-400 to-indigo-500 flex items-center justify-center shadow-2xl shadow-indigo-500/40 animate-pulse-slow">
            <i class="ri-box-3-fill text-white text-5xl md:text-7xl"></i>
        </div>
        <!-- Ring animasi luar (GSAP akan handle) -->
        <div class="absolute inset-0 rounded-2xl border-4 border-sky-400/30 animate-ping-slow"></div>
    </div>

    <!-- Teks LOADING dengan stagger effect -->
    <div id="loading-text" class="text-5xl md:text-7xl font-display font-bold tracking-[0.4em] text-white flex space-x-4 md:space-x-6 z-20">
        <span class="inline-block">L</span>
        <span class="inline-block">O</span>
        <span class="inline-block">A</span>
        <span class="inline-block">D</span>
        <span class="inline-block">I</span>
        <span class="inline-block">N</span>
        <span class="inline-block">G</span>
    </div>

    <!-- Subtext kecil -->
    <p class="mt-6 text-slate-400 font-mono-custom text-lg md:text-xl tracking-wider z-20">
        Sistem Manajemen Komponen • v2.0
    </p>

    <!-- Progress bar minimal (fake atau real via GSAP) -->
    <div class="mt-16 w-80 md:w-96 h-1.5 bg-slate-800/50 rounded-full overflow-hidden z-20">
        <div id="progress" class="h-full w-0 bg-gradient-to-r from-sky-400 to-indigo-500 rounded-full"></div>
    </div>

</div>
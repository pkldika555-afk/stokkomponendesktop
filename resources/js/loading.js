
import { gsap } from 'gsap';

document.addEventListener('DOMContentLoaded', () => {
    // Tunggu full load (images, scripts, dll selesai)
    window.addEventListener('load', () => {
        const tl = gsap.timeline({
            onComplete: () => {
                // Hilangkan sepenuhnya dari DOM/flow (biar ga ganggu scroll/performance)
                document.getElementById('loading-screen').style.display = 'none';
                // Optional: gsap.set('#loading-screen', { autoAlpha: 0, display: 'none' });
            }
        });

        // Animasi LOADER saja (jangan sentuh main/content)
        tl.to('#loading-logo', {
            scale: 0.7,
            opacity: 0,
            duration: 0.8,
            ease: 'power3.in'
        })
        .to('#loading-text span', {
            y: 50,
            opacity: 0,
            duration: 0.6,
            stagger: 0.08,
            ease: 'power2.in'
        }, '-=0.6')
        .to('p', {  // subtext "Sistem Manajemen..."
            opacity: 0,
            duration: 0.5
        }, '-=0.4')
        .to('#progress', {
            width: '100%',
            duration: 2.2,
            ease: 'power2.inOut'
        }, '-=2.8')  // overlap biar progress jalan dulu
        .to('#loading-screen', {
            yPercent: -100,          // slide up keluar
            duration: 1.2,
            ease: 'power4.inOut'
        }, '-=1.0');

        // NO ANIMASI untuk main/content → biarkan browser render normal
        // Kalau mau fade-in konten utama secara soft (opsional, tapi hati-hati kalau ada tabel/data besar)
        // tl.from('main', { opacity: 0, duration: 0.8, ease: 'power2.out' }, '-=0.8');
    });
});
// Tambahkan di dalam window.load
gsap.to('#progress', {
    width: '100%',
    duration: 3.5,
    ease: 'power2.inOut'
});
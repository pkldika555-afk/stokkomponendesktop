# Sistem Komponen - Desktop App

Aplikasi manajemen stok komponen berbasis Laravel 10 yang dikemas menjadi aplikasi desktop Windows menggunakan Electron. Bundled dengan PHP, MySQL, dan Nginx portable sehingga tidak perlu install apapun di PC klien.

---

## Stack Teknologi

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Backend | Laravel | 10.x |
| Frontend | Tailwind CSS + SweetAlert2 | via npm |
| Database | MySQL portable | 8.0.45 |
| Web Server | Nginx portable | latest stable |
| PHP | PHP NTS x64 | 8.2.x |
| Desktop Wrapper | Electron | 28.x |
| Build Tool | Electron Builder | 24.x |
| Asset Bundler | Vite | 7.x |

---

## Struktur Folder

```
electron-app\
├── main.js              <- entry point Electron
├── preload.js           <- Electron bridge
├── splash.html          <- loading screen
├── package.json         <- config Electron + build
├── .gitignore
├── before-build.bat     <- cleanup sebelum build .exe
├── update.bat           <- script update di PC klien
├── assets\
│   └── icon.ico
├── runtime\             <- TIDAK ada di git, download manual
│   ├── php\             <- PHP 8.2 NTS portable
│   ├── mysql\           <- MySQL 8.0 portable
│   └── nginx\           <- Nginx portable
└── www\                 <- Laravel project
    ├── app\
    ├── public\
    │   └── build\       <- hasil npm run build (tidak ada di git)
    ├── storage\
    └── vendor\          <- tidak ada di git
```

---

## Setup Developer (Pertama Kali)

### 1. Clone repo

```bash
git clone https://github.com/username/sistem-komponen.git
cd sistem-komponen
```

### 2. Download runtime (manual)

Folder `runtime\` tidak ada di git karena terlalu besar. Download manual lalu taruh di dalam folder project:

| Runtime | Download | Taruh di |
|---------|----------|----------|
| PHP 8.2 NTS x64 | [windows.php.net](https://windows.php.net/download) | `runtime\php\` |
| MySQL 8.0 portable | [dev.mysql.com](https://dev.mysql.com/downloads/mysql/) | `runtime\mysql\` |
| Nginx portable | [nginx.org](https://nginx.org/en/download.html) | `runtime\nginx\` |

### 3. Setup Laravel

```bash
cd www
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
cd ..
```

### 4. Install Electron dependencies

```bash
npm install
```

### 5. Jalankan dev mode

```bash
npm start
```

---

## Build Installer (.exe)

### Langkah-langkah:

**1. Pastikan assets sudah di-build**
```bash
cd www
npm run build
cd ..
```

**2. Jalankan cleanup**

Double-click `before-build.bat` — script ini akan:
- Hapus `www\node_modules` (hemat ~150MB)
- Hapus `runtime\node` jika ada (hemat ~80MB)
- Bersihkan cache Laravel
- Diet folder MySQL dan PHP (hapus docs, include, lib)

**3. Rename `www\package.json`**
```
rename www\package.json www\package.json.bak
```
Ini penting agar electron-builder tidak bingung dengan `package.json` milik Laravel.

**4. Build** (dari CMD sebagai Administrator)
```bash
npm run build
```

**5. Kembalikan `www\package.json`**
```
rename www\package.json.bak www\package.json
```

Output installer ada di: `dist\Sistem Komponen Setup 1.0.0.exe`

---

## Update Aplikasi ke PC Klien

Untuk update kode Laravel (fitur baru / bugfix) **tanpa perlu build ulang .exe**:

**1. Selesaikan perubahan kode di `www\`**

**2. Build assets jika ada perubahan CSS/JS**
```bash
cd www && npm run build && cd ..
```

**3. ZIP dua file ini:**
```
www\          <- folder Laravel terbaru
update.bat    <- script update otomatis
```

**4. Kirim ZIP ke klien**

**5. Klien: ekstrak → double-click `update.bat`**

Script `update.bat` otomatis melakukan:
- Copy file `www` baru ke folder instalasi
- Jalankan `php artisan migrate --force`
- Clear semua cache Laravel

---

## Troubleshooting

### MySQL tidak bisa start
- Pastikan port 3307 tidak dipakai aplikasi lain
- Cek `AppData\Roaming\Sistem Komponen\nginx-logs\error.log`

### Error "public/build not found"
- Jalankan `npm run build` di folder `www\` sebelum build .exe

### PHP error / extension missing
- Cek `runtime\php\php.ini` pastikan extension yang dibutuhkan aktif:
  ```ini
  extension=pdo_mysql
  extension=mbstring
  extension=openssl
  extension=fileinfo
  extension=sodium
  extension=zip
  extension=sockets
  ```

### Gambar tidak tampil setelah install
- Cek folder `AppData\Roaming\Sistem Komponen\storage\images\`
- Pastikan folder sudah terbuat (otomatis saat pertama kali buka app)

### Cache lama masih muncul
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## Data Tersimpan di PC Klien

Semua data user disimpan di `AppData` agar writable:

| Data | Lokasi |
|------|--------|
| Database MySQL | `AppData\Roaming\Sistem Komponen\mysql-data\` |
| Gambar upload | `AppData\Roaming\Sistem Komponen\storage\images\` |
| Backup files | `AppData\Roaming\Sistem Komponen\storage\backup\` |
| Nginx logs | `AppData\Roaming\Sistem Komponen\nginx-logs\` |
| Setup flag | `AppData\Roaming\Sistem Komponen\.setup_done` |

> **Catatan:** Hapus file `.setup_done` untuk memaksa first-time setup ulang.

---

## Developer

**PKL Dika** — 2026

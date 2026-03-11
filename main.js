const { app, BrowserWindow, dialog } = require("electron");
const { spawn, execSync, spawnSync } = require("child_process");
const path = require("path");
const fs = require("fs");
const net = require("net");

// ── Konfigurasi ───────────────────────────────────────────
const PORT = 8080;
const MYSQL_PORT = 3307;
const FPM_PORT = 9000;
const DB_NAME = "komponen";

const isPackaged = app.isPackaged;
const BASE = isPackaged ? process.resourcesPath : __dirname;

const PHP = path.join(BASE, "runtime", "php", "php.exe");
const PHP_CGI = path.join(BASE, "runtime", "php", "php-cgi.exe");
const MYSQL_BIN = path.join(BASE, "runtime", "mysql", "bin", "mysqld.exe");
const MYSQL_CLI = path.join(BASE, "runtime", "mysql", "bin", "mysql.exe");
const MYSQL_DATA = isPackaged
  ? path.join(app.getPath("userData"), "mysql-data")
  : path.join(BASE, "runtime", "mysql", "data");
const NGINX_BIN = path.join(BASE, "runtime", "nginx", "nginx.exe");
const NGINX_DIR = path.join(BASE, "runtime", "nginx");
// Node tidak disertakan di production — public/build sudah di-compile sebelum build .exe
const WWW = path.join(BASE, "www");

// ── [BARU] Path storage gambar di AppData ─────────────────
// Alasan: C:\Program Files\ read-only, AppData selalu writable
const STORAGE_IMAGES_PATH = path.join(
  app.getPath("userData"),
  "storage",
  "images",
);
const STORAGE_BACKUP_PATH = path.join(
  app.getPath("userData"),
  "storage",
  "backup",
);

console.log("[PATH] BASE:", BASE);
console.log("[PATH] PHP:", PHP);
console.log("[PATH] MYSQL:", MYSQL_BIN);
console.log("[PATH] NGINX:", NGINX_BIN);
console.log("[PATH] WWW:", WWW);
console.log("[PATH] STORAGE_IMAGES:", STORAGE_IMAGES_PATH); // [BARU]

let mysqlProcess = null;
let fpmProcess = null;
let nginxProcess = null;
let mainWindow = null;
let splashWindow = null;

// ── Validasi binary ada ───────────────────────────────────
function checkBinaries() {
  const required = [PHP, PHP_CGI, MYSQL_BIN, NGINX_BIN];
  for (const f of required) {
    if (!fs.existsSync(f)) {
      throw new Error(`File tidak ditemukan: ${f}`);
    }
  }
  if (!fs.existsSync(WWW)) {
    throw new Error(`Folder www tidak ditemukan: ${WWW}`);
  }
  console.log("[CHECK] Semua binary OK");
}

// ── Helpers ───────────────────────────────────────────────
function isPortBusy(port) {
  return new Promise((resolve) => {
    const server = net.createServer();
    server.once("error", () => resolve(true));
    server.once("listening", () => {
      server.close();
      resolve(false);
    });
    server.listen(port);
  });
}

function waitForPort(port, timeout = 60000) {
  return new Promise((resolve, reject) => {
    const start = Date.now();
    const check = () => {
      const client = new net.Socket();
      client.setTimeout(500);
      client.connect(port, "127.0.0.1", () => {
        client.destroy();
        resolve();
      });
      client.on("error", () => {
        client.destroy();
        if (Date.now() - start > timeout)
          reject(new Error(`Port ${port} timeout setelah ${timeout}ms`));
        else setTimeout(check, 500);
      });
      client.on("timeout", () => {
        client.destroy();
        setTimeout(check, 500);
      });
    };
    check();
  });
}

// ── Setup .env ────────────────────────────────────────────
function setupEnv() {
  const envFile = path.join(WWW, ".env");
  const envExample = path.join(WWW, ".env.example");
  const source = fs.existsSync(envFile) ? envFile : envExample;

  if (!fs.existsSync(source)) return;

  let env = fs.readFileSync(source, "utf8");

  env = env
    .replace(/DB_PORT=\d+/, `DB_PORT=${MYSQL_PORT}`)
    .replace(/DB_DATABASE=\S*/, `DB_DATABASE=${DB_NAME}`)
    .replace(/DB_USERNAME=\S*/, "DB_USERNAME=root")
    .replace(/DB_PASSWORD=.*/, "DB_PASSWORD=")
    .replace(/APP_URL=.*/, `APP_URL=http://localhost:${PORT}`)
    .replace(/APP_ENV=.*/, "APP_ENV=production")
    .replace(/APP_DEBUG=.*/, "APP_DEBUG=false")
    .replace(/FILESYSTEM_DISK=.*/, "FILESYSTEM_DISK=local");

  // ── [BARU] Inject path storage gambar ke .env ──────────
  // Path pakai forward slash agar Laravel bisa baca di Windows
  const imagesPathForEnv = STORAGE_IMAGES_PATH.replace(/\\/g, "/");
  const backupPathForEnv = STORAGE_BACKUP_PATH.replace(/\\/g, "/");

  // Kalau sudah ada variabelnya → replace, kalau belum → append di bawah
  if (env.includes("STORAGE_IMAGES_PATH=")) {
    env = env.replace(
      /STORAGE_IMAGES_PATH=.*/,
      `STORAGE_IMAGES_PATH=${imagesPathForEnv}`,
    );
  } else {
    env += `\nSTORAGE_IMAGES_PATH=${imagesPathForEnv}`;
  }

  if (env.includes("STORAGE_BACKUP_PATH=")) {
    env = env.replace(
      /STORAGE_BACKUP_PATH=.*/,
      `STORAGE_BACKUP_PATH=${backupPathForEnv}`,
    );
  } else {
    env += `\nSTORAGE_BACKUP_PATH=${backupPathForEnv}`;
  }

  if (!env.includes("APP_KEY=base64:")) {
    const key = require("crypto").randomBytes(32).toString("base64");
    env = env.replace(/APP_KEY=.*/, `APP_KEY=base64:${key}`);
    console.log("[ENV] APP_KEY generated");
  }
  fs.writeFileSync(envFile, env);
  console.log("[ENV] .env updated");
  console.log("[ENV] STORAGE_IMAGES_PATH =", imagesPathForEnv); 
}

function fixPhpIni() {
  const phpIni = path.join(BASE, "runtime", "php", "php.ini");
  if (!fs.existsSync(phpIni)) return;

  let ini = fs.readFileSync(phpIni, "utf8");
  ini = ini.replace(/^extension_dir\s*=.*/m, 'extension_dir = "ext"');

  fs.writeFileSync(phpIni, ini);
  console.log("[PHP] php.ini fixed");
}

// ── Tulis nginx.conf ──────────────────────────────────────
function writeNginxConf() {
  const publicDir = path.join(WWW, "public").replace(/\\/g, "/");
  const userDataPath = app.getPath("userData");
  const logsDir = path.join(userDataPath, "nginx-logs").replace(/\\/g, "/");
  const tempDir = path.join(userDataPath, "nginx-temp").replace(/\\/g, "/");

  if (!fs.existsSync(logsDir)) fs.mkdirSync(logsDir, { recursive: true });
  if (!fs.existsSync(tempDir)) fs.mkdirSync(tempDir, { recursive: true });
  ["client_body", "fastcgi", "proxy", "uwsgi", "scgi"].forEach((d) => {
    const p = path.join(userDataPath, "nginx-temp", d);
    if (!fs.existsSync(p)) fs.mkdirSync(p, { recursive: true });
  });

  const conf = `worker_processes 4;
error_log  "${logsDir}/error.log";
pid        "${logsDir}/nginx.pid";

events {
    worker_connections 1024;
}

http {
    include       mime.types;
    default_type  application/octet-stream;
    sendfile      on;
    keepalive_timeout 65;
    client_max_body_size 100M;

    client_body_temp_path "${tempDir}/client_body";
    proxy_temp_path       "${tempDir}/proxy";
    fastcgi_temp_path     "${tempDir}/fastcgi";
    uwsgi_temp_path       "${tempDir}/uwsgi";
    scgi_temp_path        "${tempDir}/scgi";

    server {
        listen       ${PORT};
        server_name  localhost;
        root         "${publicDir}";
        index        index.php;

        # Gambar komponen disajikan oleh Laravel (bukan file statis)
        location ^~ /images/komponen/ {
            try_files $uri /index.php?$query_string;
        }

        location ~* \\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
            expires max;
            add_header Cache-Control "public, immutable";
            try_files $uri =404;
        }

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \\.php$ {
            fastcgi_pass   127.0.0.1:${FPM_PORT};
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME "${publicDir}$fastcgi_script_name";
            include        fastcgi_params;
        }
    }
}`;

  fs.writeFileSync(path.join(NGINX_DIR, "conf", "nginx.conf"), conf);
  console.log("[NGINX] nginx.conf written");
}

// ── MySQL ─────────────────────────────────────────────────
async function startMySQL() {
  if (await isPortBusy(MYSQL_PORT)) {
    console.log("[MySQL] sudah jalan");
    return;
  }

  if (!fs.existsSync(MYSQL_DATA)) fs.mkdirSync(MYSQL_DATA, { recursive: true });

  if (!fs.existsSync(path.join(MYSQL_DATA, "mysql"))) {
    console.log("[MySQL] inisialisasi data directory...");
    try {
      execSync(
        `"${MYSQL_BIN}" --initialize-insecure --datadir="${MYSQL_DATA}" --console`,
        { timeout: 60000, stdio: "pipe" },
      );
    } catch (e) {
      console.log("[MySQL] init:", e.message.substring(0, 100));
    }
  }

  const myIni = path.join(BASE, "runtime", "mysql", "my.ini");
  fs.writeFileSync(
    myIni,
    [
      "[mysqld]",
      `port=${MYSQL_PORT}`,
      `datadir=${MYSQL_DATA.replace(/\\/g, "/")}`,
      "bind-address=127.0.0.1",
      "character-set-server=utf8mb4",
      "collation-server=utf8mb4_unicode_ci",
      "skip-log-bin",
      "[client]",
      `port=${MYSQL_PORT}`,
    ].join("\r\n"),
  );

  console.log("[MySQL] menjalankan...");
  mysqlProcess = spawn(MYSQL_BIN, [`--defaults-file=${myIni}`, "--console"], {
    detached: false,
    stdio: ["ignore", "pipe", "pipe"],
  });
  mysqlProcess.stdout.on("data", (d) =>
    process.stdout.write("[MySQL] " + d.toString()),
  );
  mysqlProcess.stderr.on("data", (d) =>
    process.stdout.write("[MySQL] " + d.toString()),
  );
  mysqlProcess.on("error", (e) => console.error("[MySQL error]", e.message));

  await waitForPort(MYSQL_PORT, 60000);
  console.log("[MySQL] siap!");

  try {
    execSync(
      `"${MYSQL_CLI}" -u root --port=${MYSQL_PORT} -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"`,
      { timeout: 10000 },
    );
    console.log("[MySQL] database siap");
  } catch (e) {
    console.log("[MySQL] db:", e.message.substring(0, 100));
  }
}

// ── First time setup ──────────────────────────────────────
async function firstTimeSetup() {
  const setupFlag = path.join(app.getPath("userData"), ".setup_done");
  if (fs.existsSync(setupFlag)) {
    console.log("[Setup] skip");
    return;
  }

  console.log("[Setup] first time setup...");

  // ── [BARU] Buat folder storage gambar & backup di AppData ──
  // Harus dibuat sebelum Laravel jalan agar tidak error saat upload
  if (!fs.existsSync(STORAGE_IMAGES_PATH)) {
    fs.mkdirSync(STORAGE_IMAGES_PATH, { recursive: true });
    console.log("[Setup] images dir created:", STORAGE_IMAGES_PATH);
  }
  if (!fs.existsSync(STORAGE_BACKUP_PATH)) {
    fs.mkdirSync(STORAGE_BACKUP_PATH, { recursive: true });
    console.log("[Setup] backup dir created:", STORAGE_BACKUP_PATH);
  }
  // ── [END BARU] ─────────────────────────────────────────

  // Cek public/build — harus sudah ada sebelum build .exe
  // (jalankan 'npm run build' di folder www sebelum packaging)
  const buildDir = path.join(WWW, "public", "build");
  if (!fs.existsSync(buildDir)) {
    throw new Error(
      "Folder public/build tidak ditemukan!\n" +
        'Jalankan "npm run build" di folder www sebelum build .exe.',
    );
  }
  console.log("[Setup] public/build OK");

  // Migrate
  console.log("[Setup] migrate...");
  const migrate = spawnSync(PHP, ["artisan", "migrate", "--force"], {
    cwd: WWW,
    timeout: 60000,
    stdio: "pipe",
  });
  console.log("[Setup] migrate:", migrate.stdout?.toString().substring(0, 200));

  // Cache
  console.log("[Setup] caching...");
  spawnSync(PHP, ["artisan", "config:cache"], {
    cwd: WWW,
    timeout: 30000,
    stdio: "pipe",
  });
  spawnSync(PHP, ["artisan", "route:cache"], {
    cwd: WWW,
    timeout: 30000,
    stdio: "pipe",
  });
  spawnSync(PHP, ["artisan", "view:cache"], {
    cwd: WWW,
    timeout: 30000,
    stdio: "pipe",
  });

  // Buat symlink storage agar gambar/file upload bisa diakses
  console.log("[Setup] storage:link...");
  const storagePath = path.join(WWW, "public", "storage");
  const storageTarget = path.join(WWW, "storage", "app", "public");
  if (!fs.existsSync(storageTarget))
    fs.mkdirSync(storageTarget, { recursive: true });
  if (!fs.existsSync(storagePath)) {
    try {
      fs.symlinkSync(storageTarget, storagePath, "junction");
      console.log("[Setup] storage symlink OK");
    } catch (e) {
      spawnSync(PHP, ["artisan", "storage:link"], {
        cwd: WWW,
        timeout: 15000,
        stdio: "pipe",
      });
      console.log("[Setup] storage:link via artisan");
    }
  }

  fs.writeFileSync(setupFlag, new Date().toISOString());
  console.log("[Setup] selesai!");
}

// ── PHP-FPM ───────────────────────────────────────────────
async function startFPM() {
  if (await isPortBusy(FPM_PORT)) {
    console.log("[FPM] sudah jalan");
    return;
  }

  console.log("[FPM] menjalankan...");
  fpmProcess = spawn(PHP_CGI, ["-b", `127.0.0.1:${FPM_PORT}`], {
    cwd: path.dirname(PHP_CGI), // penting: agar .dll PHP terbaca
    detached: false,
    stdio: ["ignore", "pipe", "pipe"],
    env: {
      ...process.env,
      PHP_FCGI_CHILDREN: "4",
      PHP_FCGI_MAX_REQUESTS: "1000",
      PHPRC: path.dirname(PHP_CGI),
    },
  });
  fpmProcess.stdout.on("data", (d) =>
    console.log("[FPM]", d.toString().trim()),
  );
  fpmProcess.stderr.on("data", (d) =>
    console.log("[FPM]", d.toString().trim()),
  );
  fpmProcess.on("error", (e) => console.error("[FPM error]", e.message));

  await waitForPort(FPM_PORT, 30000);
  console.log("[FPM] siap!");
}

// ── Nginx ─────────────────────────────────────────────────
async function startNginx() {
  if (await isPortBusy(PORT)) {
    console.log("[Nginx] sudah jalan");
    return;
  }

  writeNginxConf();

  console.log("[Nginx] menjalankan...");
  nginxProcess = spawn(NGINX_BIN, [], {
    cwd: NGINX_DIR,
    detached: false,
    stdio: ["ignore", "pipe", "pipe"],
  });
  nginxProcess.stdout.on("data", (d) =>
    console.log("[Nginx]", d.toString().trim()),
  );
  nginxProcess.stderr.on("data", (d) =>
    console.log("[Nginx]", d.toString().trim()),
  );
  nginxProcess.on("error", (e) => console.error("[Nginx error]", e.message));

  await waitForPort(PORT, 30000);
  console.log("[Nginx] siap!");
}

// ── Windows ───────────────────────────────────────────────
function createSplash() {
  splashWindow = new BrowserWindow({
    width: 400,
    height: 300,
    frame: false,
    transparent: true,
    alwaysOnTop: true,
    resizable: false,
    skipTaskbar: true,
    webPreferences: { nodeIntegration: true, contextIsolation: false },
  });
  splashWindow.loadFile(path.join(__dirname, "splash.html"));
  splashWindow.center();
}

function createMainWindow() {
  mainWindow = new BrowserWindow({
    width: 1280,
    height: 800,
    minWidth: 800,
    minHeight: 600,
    show: false,
    title: "Sistem Komponen",
    icon: path.join(__dirname, "assets", "icon.ico"),
    webPreferences: {
      preload: path.join(__dirname, "preload.js"),
      nodeIntegration: false,
      contextIsolation: true,
    },
  });
  mainWindow.loadURL(`http://localhost:${PORT}`);
  mainWindow.setMenuBarVisibility(false);
  mainWindow.once("ready-to-show", () => {
    if (splashWindow && !splashWindow.isDestroyed()) splashWindow.close();
    mainWindow.maximize();
    mainWindow.show();
  });

  // Fallback: paksa show setelah 10 detik kalau ready-to-show tidak fired
  setTimeout(() => {
    if (!mainWindow.isVisible()) {
      console.log("[Window] fallback show");
      if (splashWindow && !splashWindow.isDestroyed()) splashWindow.close();
      mainWindow.maximize();
      mainWindow.show();
    }
  }, 10000);
  mainWindow.on("close", () => {
    app.isQuiting = true;
    app.quit();
  });
}

// ── App lifecycle ─────────────────────────────────────────
app.whenReady().then(async () => {
  createSplash();
  try {
    checkBinaries();
    setupEnv();
    await startMySQL();
    await firstTimeSetup();
    await startFPM();
    await startNginx();
  } catch (err) {
    console.error("[FATAL]", err.message);
    dialog.showErrorBox("Gagal Memulai", err.message);
    app.quit();
    return;
  }
  createMainWindow();
});

app.on("before-quit", () => {
  try {
    spawnSync(NGINX_BIN, ["-s", "stop"], { cwd: NGINX_DIR, timeout: 3000 });
  } catch (e) {}
  if (nginxProcess) {
    try {
      nginxProcess.kill();
    } catch (e) {}
  }
  if (fpmProcess) {
    try {
      fpmProcess.kill();
    } catch (e) {}
  }
  if (mysqlProcess) {
    try {
      mysqlProcess.kill();
    } catch (e) {}
  }
});

app.on("window-all-closed", () => {
  app.quit();
});

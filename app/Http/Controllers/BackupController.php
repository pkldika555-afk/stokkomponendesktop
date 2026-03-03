<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\MasterKomponen;
use App\Models\MutasiBarang;
use App\Exports\LaporanExport;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use ZanySoft\Zip\Zip;

class BackupController extends Controller
{
    public function index()
    {
        $stats = [
            'departemen' => Departemen::count(),
            'komponen' => MasterKomponen::count(),
            'mutasi' => MutasiBarang::count(),
            'users' => User::count(),
            'last_backup' => session('last_backup'),
        ];
        return view('backup.index', compact('stats'));
    }

    public function backup()
    {
        $data = [
            'meta' => [
                'app' => config('app.name'),
                'version' => '1.0',
                'created_at' => now()->toISOString(),
                'total' => [
                    'departemen' => Departemen::count(),
                    'komponen' => MasterKomponen::count(),
                    'mutasi' => MutasiBarang::count(),
                    'users' => User::count(),
                ],
            ],
            'users' => User::all()->makeVisible(['password', 'remember_token'])->toArray(),
            'departemen' => Departemen::all()->toArray(),
            'komponen' => MasterKomponen::all()->toArray(),
            'mutasi' => MutasiBarang::all()->toArray(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = 'backup-' . now()->format('Ymd-His') . '.json';

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json|max:20480',
        ]);

        $result = $this->processRestore($request->file('backup_file'));

        if ($result['error']) {
            return back()->withErrors(['backup_file' => $result['error']]);
        }

        // Re-login user yang sedang aktif (karena data users di-replace)
        auth()->loginUsingId(auth()->id());

        return back()->with('success', $result['message']);
    }

    public function restoreAwalForm()
    {
        return view('backup.restore-awal');
    }

    public function restoreAwal(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json|max:20480',
        ]);

        $result = $this->processRestore($request->file('backup_file'));

        if ($result['error']) {
            return back()->withErrors(['backup_file' => $result['error']]);
        }

        return redirect('/restore-awal')->with('success', 'Restore berhasil! Silakan login dengan akun dari backup.');
    }

    private function processRestore($file): array
    {
        $content = file_get_contents($file->getRealPath());
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['meta'])) {
            return ['error' => 'File backup tidak valid atau rusak.', 'message' => null];
        }

        foreach (['departemen', 'komponen', 'mutasi', 'users'] as $key) {
            if (!array_key_exists($key, $data)) {
                return ['error' => "File backup tidak lengkap: '{$key}' tidak ditemukan.", 'message' => null];
            }
        }

        $clean = function (array $row): array {
            foreach (['created_at', 'updated_at'] as $col) {
                if (!array_key_exists($col, $row))
                    continue;
                $val = $row[$col];
                if ($val === null || $val === '' || $val === 'null') {
                    $row[$col] = null;
                    continue;
                }
                try {
                    $row[$col] = \Carbon\Carbon::parse($val)->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $row[$col] = null;
                }
            }
            if (isset($row['tanggal']) && !empty($row['tanggal'])) {
                try {
                    $row['tanggal'] = \Carbon\Carbon::parse($row['tanggal'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $row['tanggal'] = null;
                }
            }
            return $row;
        };

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            DB::beginTransaction();

            MutasiBarang::query()->delete();
            MasterKomponen::query()->delete();
            Departemen::query()->delete();
            User::query()->delete();

            foreach ($data['users'] as $row) {
                User::insert($clean($row));
                if (empty($row['password'])) {
                    $row['password'] = bcrypt('password123');
                }
            }
            foreach ($data['departemen'] as $row) {
                Departemen::insert($clean($row));
            }
            foreach ($data['komponen'] as $row) {
                MasterKomponen::insert($clean($row));
            }
            foreach ($data['mutasi'] as $row) {
                MutasiBarang::insert($clean($row));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            return ['error' => 'Restore gagal: ' . $e->getMessage(), 'message' => null];
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        foreach ([
            ['users', 'id'],
            ['departemen', 'id'],
            ['master_komponen', 'id'],
            ['mutasi_barang', 'id'],
        ] as [$tbl, $pk]) {
            $max = DB::table($tbl)->max($pk) ?? 0;
            DB::statement("ALTER TABLE `{$tbl}` AUTO_INCREMENT = " . ($max + 1));
        }

        $message = "Restore berhasil! " .
            count($data['users']) . " users, " .
            count($data['departemen']) . " departemen, " .
            count($data['komponen']) . " komponen, " .
            count($data['mutasi']) . " mutasi dipulihkan.";

        return ['error' => null, 'message' => $message];
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:' . now()->year,
        ]);

        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;
        $namaBulan = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F_Y');
        $filename = "Laporan_Gudang_{$namaBulan}.xlsx";

        return Excel::download(
            new LaporanExport($bulan, $tahun),
            $filename,
            \Maatwebsite\Excel\Excel::XLSX,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }
    public function export()
    {
        $backupDir = storage_path('app/app_data/backup');
        $imagesDir = storage_path('app/app_data/images');
        File::ensureDirectoryExists($backupDir);

        $data = [
            'meta' => ['created_at' => now()],
            'komponen' => MasterKomponen::all()->toArray(),
        ];
        $jsonPath = $backupDir . '/komponen-' . now()->format('Ymd-His') . '.json';
        file_put_contents($jsonPath, json_encode($data, JSON_PRETTY_PRINT));

        $zipPath = $backupDir . '/backup-' . now()->format('Ymd-His') . '.zip';

        $zip = new Zip();          // ✅ fix
        $zip->create($zipPath);
        $zip->add($jsonPath);

        if (File::isDirectory($imagesDir)) {
            foreach (File::files($imagesDir) as $file) {
                $zip->add($file->getPathname());
            }
        }
        $zip->close();

        File::delete($jsonPath);

        return response()->download($zipPath, 'backup.zip')
            ->deleteFileAfterSend(true);
    }
    // export
    public function exportImages()
    {
        $imagesDir = storage_path('app/app_data/images');
        $backupDir = storage_path('app/app_data/backup');

        File::ensureDirectoryExists($backupDir);

        if (!File::isDirectory($imagesDir) || empty(File::files($imagesDir))) {
            return back()->with('error', 'Tidak ada gambar untuk di-export.');
        }

        $zipPath = $backupDir . '/images-' . now()->format('Ymd-His') . '.zip';

        $zip = new Zip();
        $zip->create($zipPath);
        foreach (File::files($imagesDir) as $file) {
            $zip->add($file->getPathname());
        }
        $zip->close();

        return response()->download($zipPath, 'images-backup.zip')
            ->deleteFileAfterSend(true);
    }

    // import
    public function importImages(Request $request)
    {
        $request->validate(['zip_file' => 'required|mimes:zip|max:51200']);

        $imagesDir = storage_path('app/app_data/images');
        File::ensureDirectoryExists($imagesDir);

        $zip = new Zip();
        $zip->open($request->file('zip_file')->getPathname());
        $zip->extract($imagesDir);
        $zip->close();

        // Pindahkan dari subfolder jika ada
        $subFolder = $imagesDir . DIRECTORY_SEPARATOR . 'images';
        if (File::isDirectory($subFolder)) {
            foreach (File::files($subFolder) as $file) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    File::move(
                        $file->getPathname(),
                        $imagesDir . DIRECTORY_SEPARATOR . $file->getFilename()
                    );
                }
            }
            File::deleteDirectory($subFolder);
        }

        $count = count(File::files($imagesDir));
        return back()->with('success', "{$count} gambar berhasil diimport!");
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\MasterKomponen;
use App\Models\MutasiBarang;
use App\Exports\LaporanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BackupController extends Controller
{
    // ── Halaman utama ────────────────────────────────────────
    public function index()
    {
        $stats = [
            'departemen' => Departemen::count(),
            'komponen'   => MasterKomponen::count(),
            'mutasi'     => MutasiBarang::count(),
            'last_backup'=> session('last_backup'),
        ];
        return view('backup.index', compact('stats'));
    }

    // ── BACKUP → download .json ──────────────────────────────
    public function backup()
    {
        $data = [
            'meta' => [
                'app'        => config('app.name'),
                'version'    => '1.0',
                'created_at' => now()->toISOString(),
                'total' => [
                    'departemen' => Departemen::count(),
                    'komponen'   => MasterKomponen::count(),
                    'mutasi'     => MutasiBarang::count(),
                ],
            ],
            'departemen' => Departemen::all()->toArray(),
            'komponen'   => MasterKomponen::all()->toArray(),
            'mutasi'     => MutasiBarang::all()->toArray(),
        ];

        $json     = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = 'backup-' . now()->format('Ymd-His') . '.json';

        return response($json, 200, [
            'Content-Type'        => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    // ── RESTORE ← upload .json ───────────────────────────────
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json|max:20480',
        ]);

        $content = file_get_contents($request->file('backup_file')->getRealPath());
        $data    = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['meta'])) {
            return back()->withErrors(['backup_file' => 'File backup tidak valid atau rusak.']);
        }

        // Validasi struktur
        $required = ['departemen', 'komponen', 'mutasi'];
        foreach ($required as $key) {
            if (!array_key_exists($key, $data)) {
                return back()->withErrors(['backup_file' => "File backup tidak lengkap: koleksi '{$key}' tidak ditemukan."]);
            }
        }

        // Helper inline: konversi format datetime ISO ke MySQL
        $clean = function (array $row): array {
            foreach (['created_at', 'updated_at'] as $col) {
                if (!array_key_exists($col, $row)) continue;
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
            // Kolom tanggal (mutasi_barang)
            if (!empty($row['tanggal'])) {
                try {
                    $row['tanggal'] = \Carbon\Carbon::parse($row['tanggal'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $row['tanggal'] = null;
                }
            }
            return $row;
        };

        try {
            DB::transaction(function () use ($data, $clean) {
                // Nonaktifkan FK sementara
                DB::statement('SET FOREIGN_KEY_CHECKS=0');

                MutasiBarang::query()->delete();
                MasterKomponen::query()->delete();
                Departemen::query()->delete();

                foreach ($data['departemen'] as $row) {
                    Departemen::insert($clean($row));
                }
                foreach ($data['komponen'] as $row) {
                    MasterKomponen::insert($clean($row));
                }
                foreach ($data['mutasi'] as $row) {
                    MutasiBarang::insert($clean($row));
                }

                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                foreach ([['departemen','id'],['master_komponen','id'],['mutasi_barang','id']] as [$tbl,$pk]) {
                    $max = DB::table($tbl)->max($pk) ?? 0;
                    DB::statement("ALTER TABLE `{$tbl}` AUTO_INCREMENT = " . ($max + 1));
                }
            });

            return back()->with('success',
                "Restore berhasil! " .
                count($data['departemen']) . " departemen, " .
                count($data['komponen'])   . " komponen, " .
                count($data['mutasi'])     . " mutasi dipulihkan."
            );

        } catch (\Exception $e) {
            return back()->withErrors(['backup_file' => 'Restore gagal: ' . $e->getMessage()]);
        }
    }
    public function exportExcel()
    {
        $filename = 'laporan-' . now()->format('Ymd-His') . '.xlsx';
        return Excel::download(new LaporanExport(), $filename);
    }

}
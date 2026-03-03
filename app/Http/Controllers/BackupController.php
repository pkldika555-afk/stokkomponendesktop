<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\MasterKomponen;
use App\Models\MutasiBarang;
use App\Exports\LaporanExport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
            'departemen' => Departemen::all()->toArray(),
            'komponen' => MasterKomponen::all()->toArray(),
            'mutasi' => MutasiBarang::all()->toArray(),
            'users' => User::all()->toArray(),
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

        $content = file_get_contents($request->file('backup_file')->getRealPath());
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['meta'])) {
            return back()->withErrors(['backup_file' => 'File backup tidak valid atau rusak.']);
        }

        foreach (['departemen', 'komponen', 'mutasi', 'users'] as $key) {
            if (!array_key_exists($key, $data)) {
                return back()->withErrors(['backup_file' => "File backup tidak lengkap: '{$key}' tidak ditemukan."]);
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

            foreach ($data['departemen'] as $row) {
                Departemen::insert($clean($row));
            }
            foreach ($data['komponen'] as $row) {
                MasterKomponen::insert($clean($row));
            }
            foreach ($data['mutasi'] as $row) {
                MutasiBarang::insert($clean($row));
            }
            foreach ($data['users'] as $row) {
                User::insert($clean($row));
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            return back()->withErrors(['backup_file' => 'Restore gagal: ' . $e->getMessage()]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        foreach ([['departemen', 'id'], ['master_komponen', 'id'], ['mutasi_barang', 'id']] as [$tbl, $pk]) {
            $max = DB::table($tbl)->max($pk) ?? 0;
            DB::statement("ALTER TABLE `{$tbl}` AUTO_INCREMENT = " . ($max + 1));
        }

        return back()->with(
            'success',
            "Restore berhasil! " .
            count($data['departemen']) . " departemen, " .
            count($data['komponen']) . " komponen, " .
            count($data['mutasi']) . " mutasi dipulihkan." .
            count($data['users']) . " users dipulihkan."
        );
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

        return redirect('/login')->with('success', 'Restore berhasil! Silakan login dengan akun dari backup.');
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
}
<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\MasterKomponen;
use App\Models\MutasiBarang;
use DB;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'departemen' => Departemen::count(),
            'komponen' => MasterKomponen::count(),
            'mutasi' => MutasiBarang::count(),
            'last_backup' => session('last_backup')
        ];
        return view('backup.index', compact('stats'));
    }
    public function backup()
    {
        $data = [
            'meta' => [
                'app'   =>  config('app.name'),
                'version'   =>  '1.0',
                'created_at'    =>  now()->toDateString(),
                'total' =>  [
                    'departemen'    => Departemen::count(),
                    'komponen'  => MasterKomponen::count(),
                    'mutasi'    => MutasiBarang::count(),
                ], 
            ],
            'departemen'    =>  Departemen::all()->toArray(),
            'komponen'  => MasterKomponen::all()->toArray(),
            'mutasi'    => MutasiBarang::all()->toArray(),
        ];g

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = 'backup_' . now()->format('Ymd_His') . '.json';
        return  response($json, 200, [
            'Content-Type'  =>  'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json',
        ]);
        $content = file_get_contents($request->file('backup_file')->getRealPath());
        $data   =   json_decode($content, true);

        $required = ['departemen', 'komponen', 'mutasi'];
        foreach ($required as $key){
            if (json_last_error() !== JSON_ERROR_NONE || !isset($data['neta'])){
                return back()->withErrors(['backup_file' => "File backup tidak lengkap: koleksi '{$key}' tidak ditemukan"]);
            }
        }
        try{
            DB::transaction(function () use ($data) {
                MutasiBarang::query()->delete();
                MasterKomponen::query()->delete();
                MutasiBarang::query()->delete();

                foreach ($data['departemen'] as $row){
                    Departemen::insert($this->cleanTimestamps($row));
                }
                foreach ($data['komponen'] as $row){
                    MasterKomponen::insert($this->cleanTimestamps($row));
                }
                foreach ($data['mutasi'] as $row){
                    MutasiBarang::insert($this->cleanTimestamps($row));
                }
                $this->resetAutoIncrement('departemen');
                $this->resetAutoIncrement('master_komponen');
                $this->resetAutoIncrement('mutasi_barang', 'id_mutasi');
            });
                    return back()->with('success', 
                        "Restore berhasil! " .
                        count($data['departemen']) . " departemen, " .
                        count($data['komponen']) . " komponen, " .
                        count($data['mutasi']) . " mutasi dipulihkan."
                    );
                } catch(\Exception $e){
                    return back()->withErrors(['backup_file' => "Restore gagal: " . $e->getMessage()]);
                }
            }
}


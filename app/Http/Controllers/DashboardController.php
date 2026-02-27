<?php

namespace App\Http\Controllers;

use App\Models\MasterKomponen;
use App\Models\MutasiBarang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $komponen = MasterKomponen::with(['departemen'])->withCount('mutasi')->orderBy('nama_komponen')->get()->map(function ($k) {
                $k->stok_sekarang = $k->stok;
                return $k;
            });
            
        $totalKomponen = $komponen->count();
        $stokRendah = $komponen->filter(fn($k) => $k->isStokRendah())->count();
        $totalMasuk = MutasiBarang::whereIn('jenis', MutasiBarang::JENIS_MASUK)->whereMonth('tanggal', now()->month)->sum('jumlah');
        $totalKeluar = MutasiBarang::whereIn('jenis', MutasiBarang::JENIS_KELUAR)->whereMonth('tanggal', now()->month)->sum('jumlah');

        return view('dashboard', compact('komponen','totalKomponen','stokRendah','totalMasuk','totalKeluar'
        ));
    }
}

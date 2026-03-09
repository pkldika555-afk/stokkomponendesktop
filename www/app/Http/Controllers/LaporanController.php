<?php

namespace App\Http\Controllers;

use App\Models\MasterKomponen;
use App\Models\MutasiBarang;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = MutasiBarang::with('komponen');
        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('tanggal', [
                $request->tanggal_awal, $request->tanggal_akhir
            ]);
        if ($request->komponen){
            $query->where('id_komponen', $request->komponen);
        }
        $transaksi = $query->latest()->get();
        $totalMasuk = $transaksi->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = $transaksi->where('jenis', 'keluar')->sum('jumlah');

        $komponen = MasterKomponen::all();
        return view('laporan.index', compact('transaksi', 'totalMasuk', 'totalKeluar', 'komponen'));
    }
}
}
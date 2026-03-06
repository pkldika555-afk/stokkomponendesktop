<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MasterKomponen;
use App\Models\Departemen;
use App\Models\MutasiBarang;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat Cards ─────────────────────────────────────────────
        $totalKomponen   = MasterKomponen::count();
        $totalDepartemen = Departemen::count();

        $mutasiBulanIni  = MutasiBarang::whereMonth('tanggal', now()->month)
                                        ->whereYear('tanggal',  now()->year)
                                        ->count();

        // Komponen yang stoknya di bawah stok_minimal
        $stokRendah      = MasterKomponen::whereColumn('stok', '<', 'stok_minimal')->count();

        // ── Chart: Mutasi 6 Bulan Terakhir ─────────────────────────
        $chartMasuk  = [];
        $chartKeluar = [];
        $chartLabels = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $chartLabels[] = $bulan->translatedFormat('F');   // nama bulan (id)

            $chartMasuk[] = MutasiBarang::where('jenis', 'masuk')
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal',  $bulan->year)
                ->sum('jumlah');

            $chartKeluar[] = MutasiBarang::where('jenis', 'keluar')
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal',  $bulan->year)
                ->sum('jumlah');
        }

        $topRaw = MutasiBarang::select(
                        'id_komponen',
                        DB::raw('SUM(jumlah) as total')
                    )
                    ->with('komponen:id,nama_komponen,kode_komponen')
                    ->groupBy('id_komponen')
                    ->orderByDesc(DB::raw('SUM(jumlah)'))
                    ->limit(5)
                    ->get();

        $maxCount = $topRaw->max('total') ?: 1;

        $topKomponen = $topRaw->map(fn($m) => [
            'name'  => $m->komponen->nama_komponen ?? '-',
            'count' => $m->total,
            'pct'   => round(($m->total / $maxCount) * 100),
        ]);

        // ── Mutasi Terbaru ──────────────────────────────────────────
        $recentMutasi = MutasiBarang::with([
                            'komponen:id,kode_komponen,nama_komponen', 
                            'departemenAsal:id,nama_departemen',
                            'departemenTujuan:id,nama_departemen',
                        ])
                        ->orderByDesc('tanggal')
                        ->orderByDesc('created_at')
                        ->limit(10)
                        ->get();

        return view('dashboard', compact(
            'totalKomponen',
            'totalDepartemen',
            'mutasiBulanIni',
            'stokRendah',
            'chartMasuk',
            'chartKeluar',
            'chartLabels',
            'topKomponen',
            'recentMutasi',
        ));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\MasterKomponen;
use App\Models\MutasiBarang;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        $query = MutasiBarang::with(['komponen', 'departemenAsal', 'departemenTujuan'])->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc');

        if ($request->filled('jenis')) {
            $query->where(
                'jenis',
                $request->jenis
            );
        }
        if ($request->filled('id_komponen')) {
            $query->where(
                'id_komponen',
                $request->id_komponen
            );
        }
        if ($request->filled('dari')) {
            $query->whereDate(
                'tanggal',
                '>=',
                $request->tanggal
            );
        }
        if ($request->filled('sampai')) {
            $query->whereDate(
                'tanggal',
                '<=',
                $request->tanggal
            );
        }
        $mutasi = $query->paginate(10);
        $komponen = MasterKomponen::orderBy('nama_komponen')->get();
        return view("mutasi.index", compact("komponen", "mutasi"));
    }
    public function create()
    {
        $komponen = MasterKomponen::all();
        $departemen = Departemen::all();
        return view('mutasi.create', compact('komponen', 'departemen'));
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'id_komponen' => 'required|exists:master_komponen,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'id_departemen_asal' => 'required|exists:departemen,id',
            'id_departemen_tujuan' => 'required|exists:departemen,id',
            'jenis' => 'required|in:masuk,keluar',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $komponen = MasterKomponen::findOrFail($validate['id_komponen']);
        // hanya cek stok jika mutasi keluar
        if ($validate['jenis'] === 'keluar' && $komponen->stok < $validate['jumlah']) {
            return back()->withInput()->withErrors(['jumlah' => "Stok tidak cukup. Stok tersedia: {$komponen->stok} {$komponen->satuan}"]);
        }
        // $komponen->update([
        //     'departemen_id' => $validate['id_departemen_tujuan']
        // ]); 
        MutasiBarang::create($validate);
        return redirect()->route('mutasi.index')->with('success', 'Mutasi berhasil ditambahkan');
    }
    public function show($id)
    {
        $mutasi = MutasiBarang::with('komponen', 'departemenAsal', 'departemenTujuan')->findOrFail($id);

        return view('mutasi.show', compact('mutasi'));
    }
    public function rekap(Request $request)
    {
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $komponen = MasterKomponen::with('departemen')->orderBy('nama_komponen')->get();
        $totalKomponen = $komponen->count();

        $mutasiBulanIni = MutasiBarang::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $totalMasuk = $mutasiBulanIni->filter(fn($m) => in_array($m->jenis, MutasiBarang::JENIS_MASUK))->sum('jumlah');
        $totalKeluar = $mutasiBulanIni->reject(fn($m) => in_array($m->jenis, MutasiBarang::JENIS_MASUK))->sum('jumlah');

        $stokRendah = $komponen->filter(fn($k) => $k->stok_sekarang > 0 && $k->stok_sekarang <= $k->stok_minimal)->count();

        $statusNormal = $komponen->filter(fn($k) => $k->stok_sekarang > $k->stok_minimal)->count();
        $statusRendah = $komponen->filter(fn($k) => $k->stok_sekarang > 0 && $k->stok_sekarang <= $k->stok_minimal)->count();
        $statusHabis = $komponen->filter(fn($k) => $k->stok_sekarang <= 0)->count();

        $daysInMonth = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;

        $masukPerHari = $mutasiBulanIni->filter(fn($m) => in_array($m->jenis, MutasiBarang::JENIS_MASUK))
            ->groupBy(fn($m) => \Carbon\Carbon::parse($m->tanggal)->format('d'))
            ->map->sum('jumlah');

        $keluarPerHari = $mutasiBulanIni->reject(fn($m) => in_array($m->jenis, MutasiBarang::JENIS_MASUK))
            ->groupBy(fn($m) => \Carbon\Carbon::parse($m->tanggal)->format('d'))
            ->map->sum('jumlah');

        $mutasiHarian = collect(range(1, $daysInMonth))->map(function ($d) use ($masukPerHari, $keluarPerHari, $bulan, $tahun) {
            $key = str_pad($d, 2, '0', STR_PAD_LEFT);
            return [
                'tanggal' => $key . '/' . str_pad($bulan, 2, '0', STR_PAD_LEFT),
                'masuk' => $masukPerHari[$key] ?? 0,
                'keluar' => $keluarPerHari[$key] ?? 0,
            ];
        });

        return view('mutasi.rekap', compact('komponen','totalKomponen','totalMasuk','totalKeluar', 'stokRendah','statusNormal','statusRendah','statusHabis','mutasiHarian','bulan','tahun',));
    }
}

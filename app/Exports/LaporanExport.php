<?php

namespace App\Exports;

use App\Models\MasterKomponen;
use App\Models\MutasiBarang;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new LaporanKomponenSheet(),
            new LaporanMutasiSheet(),
            new LaporanRekapSheet(),
        ];
    }
}

class LaporanKomponenSheet implements
    \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithMapping,
    \Maatwebsite\Excel\Concerns\WithStyles,
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
{
    public function title(): string { return 'Master Komponen'; }

    public function collection()
    {
        return MasterKomponen::with('departemen')->orderBy('nama_komponen')->get();
    }

    public function headings(): array
    {
        return ['No','Kode','Nama Komponen','Tipe','Satuan','Stok','Min. Stok','Rak','Lot','Departemen','Dibuat'];
    }

    public function map($k): array
    {
        static $no = 0; $no++;
        return [
            $no, $k->kode_komponen, $k->nama_komponen,
            strtoupper($k->tipe), strtoupper($k->satuan),
            $k->stok, $k->stok_minimal, $k->rak, $k->lokasi,
            $k->departemen->nama_departemen ?? '-',
            $k->created_at?->format('d/m/Y') ?? '-',
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                      'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3730A3']]]];
    }
}

class LaporanMutasiSheet implements
    \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithMapping,
    \Maatwebsite\Excel\Concerns\WithStyles,
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
{
    public function title(): string { return 'Mutasi Barang'; }

    public function collection()
    {
        return MutasiBarang::with(['komponen','departemenAsal','departemenTujuan'])
            ->orderBy('tanggal','desc')->get();
    }

    public function headings(): array
    {
        return ['No','Tanggal','Kode','Nama Komponen','Jenis','Arah','Jumlah','Satuan','Dari','Ke','Keterangan'];
    }

    public function map($m): array
    {
        static $no = 0; $no++;
        $isMasuk = in_array($m->jenis, MutasiBarang::JENIS_MASUK);
        $label   = match($m->jenis) {
            'pembelian'      => 'Pembelian',
            'internal'       => 'Pemakaian Internal',
            'retur'          => 'Retur',
            'repair_kembali' => 'Repair Kembali',
            default          => $m->jenis,
        };
        return [
            $no, $m->tanggal,
            $m->komponen->kode_komponen ?? '-',
            $m->komponen->nama_komponen ?? '-',
            $label, $isMasuk ? 'Masuk' : 'Keluar', $m->jumlah,
            $m->komponen->satuan ?? 'unit',
            $m->departemenAsal->nama_departemen ?? '-',
            $m->departemenTujuan->nama_departemen ?? '-',
            $m->keterangan ?? '',
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                      'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '065F46']]]];
    }
}

class LaporanRekapSheet implements
    \Maatwebsite\Excel\Concerns\FromCollection,
    \Maatwebsite\Excel\Concerns\WithHeadings,
    \Maatwebsite\Excel\Concerns\WithMapping,
    \Maatwebsite\Excel\Concerns\WithStyles,
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
{
    public function title(): string { return 'Rekap Stok'; }

    public function collection()
    {
        return MasterKomponen::with('departemen')->orderBy('nama_komponen')->get();
    }

    public function headings(): array
    {
        return ['No','Kode','Nama Komponen','Stok','Min. Stok','Selisih','Status','Satuan'];
    }

    public function map($k): array
    {
        static $no = 0; $no++;
        $stok   = $k->stok;
        $status = $stok <= 0 ? 'HABIS' : ($stok <= $k->stok_minimal ? 'RENDAH' : 'NORMAL');
        return [$no, $k->kode_komponen, $k->nama_komponen, $stok, $k->stok_minimal, $stok - $k->stok_minimal, $status, strtoupper($k->satuan)];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                      'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '92400E']]]];
    }
}
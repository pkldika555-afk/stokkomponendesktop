<?php

namespace App\Exports;

use App\Models\MasterKomponen;
use App\Models\MutasiBarang;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanExport implements WithMultipleSheets
{
    public function __construct(
        protected int $bulan,
        protected int $tahun
    ) {}

    public function sheets(): array
    {
        return [
            new LaporanRingkasanSheet($this->bulan, $this->tahun),
            new LaporanKomponenSheet(),
            new LaporanMutasiSheet($this->bulan, $this->tahun),
            new LaporanRekapSheet(),
        ];
    }
}

// ─── Sheet 1: Ringkasan + Chart ──────────────────────────────────────────────

class LaporanRingkasanSheet implements
    \Maatwebsite\Excel\Concerns\FromArray,
    \Maatwebsite\Excel\Concerns\WithTitle,
    \Maatwebsite\Excel\Concerns\WithStyles,
    \Maatwebsite\Excel\Concerns\WithColumnWidths,
    \Maatwebsite\Excel\Concerns\WithCharts,
    \Maatwebsite\Excel\Concerns\ShouldAutoSize
{
    private array $masukPerHari  = [];
    private array $keluarPerHari = [];
    private array $rekapStatus   = ['NORMAL' => 0, 'RENDAH' => 0, 'HABIS' => 0];
    private int   $totalMasuk    = 0;
    private int   $totalKeluar   = 0;
    private int   $totalKomponen = 0;

    public function __construct(
        protected int $bulan,
        protected int $tahun
    ) {
        $this->prepare();
    }

    private function prepare(): void
    {
        $mutasi = MutasiBarang::whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->get();

        foreach ($mutasi as $m) {
            $day = \Carbon\Carbon::parse($m->tanggal)->format('d');
            $isMasuk = in_array($m->jenis, MutasiBarang::JENIS_MASUK);
            if ($isMasuk) {
                $this->masukPerHari[$day]  = ($this->masukPerHari[$day]  ?? 0) + $m->jumlah;
                $this->totalMasuk += $m->jumlah;
            } else {
                $this->keluarPerHari[$day] = ($this->keluarPerHari[$day] ?? 0) + $m->jumlah;
                $this->totalKeluar += $m->jumlah;
            }
        }

        $komponen = MasterKomponen::all();
        $this->totalKomponen = $komponen->count();
        foreach ($komponen as $k) {
            if ($k->stok <= 0)              $this->rekapStatus['HABIS']++;
            elseif ($k->stok <= $k->stok_minimal) $this->rekapStatus['RENDAH']++;
            else                            $this->rekapStatus['NORMAL']++;
        }
    }

    public function title(): string { return 'Ringkasan'; }

    public function array(): array
    {
        $namaBulan = \Carbon\Carbon::createFromDate($this->tahun, $this->bulan, 1)
                        ->translatedFormat('F Y');
        $daysInMonth = \Carbon\Carbon::createFromDate($this->tahun, $this->bulan, 1)->daysInMonth;

        $rows = [];

        // Title
        $rows[] = ["LAPORAN STOK GUDANG — {$namaBulan}"];
        $rows[] = ['Diekspor pada: ' . now()->format('d/m/Y H:i')];
        $rows[] = [];

        // KPI summary
        $rows[] = ['RINGKASAN'];
        $rows[] = ['Total Komponen', $this->totalKomponen, '', 'Mutasi Masuk',  $this->totalMasuk];
        $rows[] = ['Stok Normal',    $this->rekapStatus['NORMAL'], '', 'Mutasi Keluar', $this->totalKeluar];
        $rows[] = ['Stok Rendah',    $this->rekapStatus['RENDAH'], '', 'Selisih Net',   $this->totalMasuk - $this->totalKeluar];
        $rows[] = ['Stok Habis',     $this->rekapStatus['HABIS']];
        $rows[] = [];

        // Chart data header (row 10 = index 9 after 0-based)
        $rows[] = ['Tanggal', 'Masuk', 'Keluar'];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $key = str_pad($d, 2, '0', STR_PAD_LEFT);
            $rows[] = [
                \Carbon\Carbon::createFromDate($this->tahun, $this->bulan, $d)->format('d/m'),
                $this->masukPerHari[$key] ?? 0,
                $this->keluarPerHari[$key] ?? 0,
            ];
        }

        // Status pie data (placed to the right, cols E-F, starting row 10)
        // We'll add these into existing rows by merging arrays
        $rows[9][4] = 'Status';
        $rows[9][5] = 'Jumlah';
        foreach (['NORMAL', 'RENDAH', 'HABIS'] as $i => $s) {
            $rows[10 + $i][4] = $s;
            $rows[10 + $i][5] = $this->rekapStatus[$s];
        }

        return $rows;
    }

    public function columnWidths(): array
    {
        return ['A' => 14, 'B' => 12, 'C' => 3, 'D' => 16, 'E' => 12, 'F' => 10];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): array
    {
        // Title
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3730A3']],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // Section headers
        foreach (['A4', 'A10', 'E10'] as $cell) {
            $sheet->getStyle($cell)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1E1B4B']],
            ]);
        }

        // KPI labels bold
        $sheet->getStyle('A5:A8')->getFont()->setBold(true);
        $sheet->getStyle('D5:D8')->getFont()->setBold(true);

        // Chart data header bold
        $sheet->getStyle('A10:C10')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '065F46']],
        ]);
        $sheet->getStyle('E10:F10')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '92400E']],
        ]);

        return [];
    }

    public function charts(): array
    {
        $daysInMonth = \Carbon\Carbon::createFromDate($this->tahun, $this->bulan, 1)->daysInMonth;
        $dataRow     = 11; // row 11 = first data row (1-indexed)
        $lastRow     = 10 + $daysInMonth;

        // ── Bar chart: Masuk vs Keluar per hari ─────────────────────────────
        $labelSeries = new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(
            'String', "Ringkasan!\$A\${$dataRow}:\$A\${$lastRow}", null, $daysInMonth
        );
        $masukSeries = new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(
            'Number', "Ringkasan!\$B\${$dataRow}:\$B\${$lastRow}", null, $daysInMonth
        );
        $keluarSeries = new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(
            'Number', "Ringkasan!\$C\${$dataRow}:\$C\${$lastRow}", null, $daysInMonth
        );

        $barDataSeries = new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
            \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART,
            \PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_CLUSTERED,
            range(0, 1),
            [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Masuk']),
             new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Keluar'])],
            [$labelSeries],
            [$masukSeries, $keluarSeries]
        );
        $barDataSeries->setPlotDirection(\PhpOffice\PhpSpreadsheet\Chart\DataSeries::DIRECTION_COL);

        $barChart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
            'chart_mutasi',
            new \PhpOffice\PhpSpreadsheet\Chart\Title('Mutasi Harian'),
            new \PhpOffice\PhpSpreadsheet\Chart\Legend(),
            new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [$barDataSeries])
        );
        $barChart->setTopLeftPosition('A42');
        $barChart->setBottomRightPosition('F62');

        // ── Pie chart: Status Stok ────────────────────────────────────────────
        $pieLabel = new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(
            'String', 'Ringkasan!$E$11:$E$13', null, 3
        );
        $pieData = new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(
            'Number', 'Ringkasan!$F$11:$F$13', null, 3
        );

        $pieDataSeries = new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
            \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_PIECHART,
            null, [0], [], [$pieLabel], [$pieData]
        );

        $pieChart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
            'chart_status',
            new \PhpOffice\PhpSpreadsheet\Chart\Title('Status Stok'),
            new \PhpOffice\PhpSpreadsheet\Chart\Legend(),
            new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [$pieDataSeries])
        );
        $pieChart->setTopLeftPosition('H42');
        $pieChart->setBottomRightPosition('M62');

        return [$barChart, $pieChart];
    }
}

// ─── Sheet 2: Master Komponen ─────────────────────────────────────────────────

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
        return ['No', 'Kode', 'Nama Komponen', 'Tipe', 'Satuan', 'Stok', 'Min. Stok', 'Rak', 'Lot', 'Departemen', 'Dibuat'];
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
    public function __construct(
        protected int $bulan,
        protected int $tahun
    ) {}

    public function title(): string { return 'Mutasi Barang'; }

    public function collection()
    {
        return MutasiBarang::with(['komponen', 'departemenAsal', 'departemenTujuan'])
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Kode', 'Nama Komponen', 'Jenis', 'Arah', 'Jumlah', 'Satuan', 'Dari', 'Ke', 'Keterangan'];
    }

    public function map($m): array
    {
        static $no = 0; $no++;
        $isMasuk = in_array($m->jenis, MutasiBarang::JENIS_MASUK);
        $label = match($m->jenis) {
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
            $m->departemenAsal->nama_departemen  ?? '-',
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
        return ['No', 'Kode', 'Nama Komponen', 'Stok', 'Min. Stok', 'Selisih', 'Status', 'Satuan'];
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
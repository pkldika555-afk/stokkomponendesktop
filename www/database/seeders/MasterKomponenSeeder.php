<?php

namespace Database\Seeders;

use App\Models\MasterKomponen;
use Illuminate\Database\Seeder;

class MasterKomponenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $komponens = [
            [
                'kode_komponen' => 'ACC-001',
                'nama_komponen' => 'Accelerometer 3-Axis',
                'tipe' => 'sensor',
                'satuan' => 'pcs',
                'rak' => 1,
                'lokasi' => 101,
                'stok_minimal' => 10,
                'departemen_id' => 1,
            ],
            [
                'kode_komponen' => 'RES-001',
                'nama_komponen' => 'Resistor 1K 1/4W',
                'tipe' => 'passive',
                'satuan' => 'pcs',
                'rak' => 2,
                'lokasi' => 201,
                'stok_minimal' => 100,
                'departemen_id' => 4,
            ],
            [
                'kode_komponen' => 'CAP-001',
                'nama_komponen' => 'Kapasitor Elektrolit 100uF',
                'tipe' => 'passive',
                'satuan' => 'pcs',
                'rak' => 2,
                'lokasi' => 202,
                'stok_minimal' => 50,
                'departemen_id' => 4,
            ],
            [
                'kode_komponen' => 'IC-001',
                'nama_komponen' => 'IC Arduino Atmega328',
                'tipe' => 'active',
                'satuan' => 'pcs',
                'rak' => 3,
                'lokasi' => 301,
                'stok_minimal' => 5,
                'departemen_id' => 4,
            ],
            [
                'kode_komponen' => 'MOT-001',
                'nama_komponen' => 'Motor AC 3 Phase 5.5kW',
                'tipe' => 'consumable',
                'satuan' => 'unit',
                'rak' => 4,
                'lokasi' => 401,
                'stok_minimal' => 2,
                'departemen_id' => 3,
            ],
            [
                'kode_komponen' => 'BEL-001',
                'nama_komponen' => 'Ball Bearing 6205',
                'tipe' => 'consumable',
                'satuan' => 'pcs',
                'rak' => 5,
                'lokasi' => 501,
                'stok_minimal' => 20,
                'departemen_id' => 3,
            ],
            [
                'kode_komponen' => 'OIL-001',
                'nama_komponen' => 'Oli Mesin Shell Tellus',
                'tipe' => 'consumable',
                'satuan' => 'liter',
                'rak' => 6,
                'lokasi' => 601,
                'stok_minimal' => 50,
                'departemen_id' => 3,
            ],
            [
                'kode_komponen' => 'UPS-001',
                'nama_komponen' => 'UPS 1000VA',
                'tipe' => 'repairable',
                'satuan' => 'unit',
                'rak' => 7,
                'lokasi' => 701,
                'stok_minimal' => 1,
                'departemen_id' => 4,
            ],
        ];

        foreach ($komponens as $komponen) {
            MasterKomponen::create($komponen);
        }
    }
}

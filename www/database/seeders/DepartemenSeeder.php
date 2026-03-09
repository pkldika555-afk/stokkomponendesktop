<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Departemen::create([
            'nama_departemen' => 'Bagian Produksi',
        ]);

        Departemen::create([
            'nama_departemen' => 'Bagian Maintenance',
        ]);

        Departemen::create([
            'nama_departemen' => 'Bagian Mesin',
        ]);

        Departemen::create([
            'nama_departemen' => 'Bagian Listrik',
        ]);

        Departemen::create([
            'nama_departemen' => 'Bagian Gudang',
        ]);
    }
}

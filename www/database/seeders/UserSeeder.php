<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@stockkomponen.local',
            'nrp' => '12345678',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'dika',
            'email' => 'dika@stockkomponen.local',
            'nrp' => '662535',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Operator Gudang',
            'email' => 'operator@stockkomponen.local',
            'nrp' => '87654321',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Kepala Gudang',
            'email' => 'kepala@stockkomponen.local',
            'nrp' => '11223344',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@smartdrain.test'],
            [
                'name' => 'Admin SmartDrain',
                'password' => 'password',
                'role' => User::ROLE_ADMIN,
            ],
        );

        User::updateOrCreate(
            ['email' => 'warga@smartdrain.test'],
            [
                'name' => 'Warga Bandung',
                'password' => 'password',
                'role' => User::ROLE_WARGA,
            ],
        );

        User::updateOrCreate(
            ['email' => 'rizqi@smartdrain.test'],
            [
                'name' => 'Rizqi Raihan',
                'password' => 'password',
                'role' => User::ROLE_WARGA,
            ],
        );

        $this->call([
            \Modules\TitikDrainase\Database\Seeders\TitikDrainaseDatabaseSeeder::class,
            \Modules\Laporan\Database\Seeders\LaporanDatabaseSeeder::class,
        ]);
    }
}

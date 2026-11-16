<?php

namespace Modules\TitikDrainase\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TitikDrainase\Models\TitikDrainase;

class TitikDrainaseDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_titik' => 'Saluran Jalan Raya Soreang', 'kecamatan' => 'Soreang', 'deskripsi' => 'Saluran utama di depan pasar Soreang.', 'kedalaman_cm' => 90],
            ['nama_titik' => 'Drainase Margahayu Raya', 'kecamatan' => 'Margahayu', 'deskripsi' => 'Saluran perumahan Margahayu Raya blok C.', 'kedalaman_cm' => 70],
            ['nama_titik' => 'Saluran Dayeuhkolot', 'kecamatan' => 'Dayeuhkolot', 'deskripsi' => 'Saluran penghubung kawasan industri Dayeuhkolot.', 'kedalaman_cm' => 120],
            ['nama_titik' => 'Drainase Baleendah', 'kecamatan' => 'Baleendah', 'deskripsi' => 'Saluran pinggir jalan Baleendah dekat alun-alun.', 'kedalaman_cm' => 80],
            ['nama_titik' => 'Saluran Cileunyi', 'kecamatan' => 'Cileunyi', 'deskripsi' => 'Saluran di bawah flyover Cileunyi.', 'kedalaman_cm' => 110],
            ['nama_titik' => 'Drainase Banjaran', 'kecamatan' => 'Banjaran', 'deskripsi' => 'Saluran pasar Banjaran yang sering tersumbat.', 'kedalaman_cm' => 65],
            ['nama_titik' => 'Saluran Pameungpeuk', 'kecamatan' => 'Pameungpeuk', 'deskripsi' => 'Saluran perbatasan Pameungpeuk dan Banjaran.', 'kedalaman_cm' => 75],
            ['nama_titik' => 'Drainase Kutawaringin', 'kecamatan' => 'Kutawaringin', 'deskripsi' => 'Saluran jalan desa Kutawaringin.', 'kedalaman_cm' => 60],
        ];

        foreach ($data as $item) {
            TitikDrainase::updateOrCreate(['nama_titik' => $item['nama_titik']], $item);
        }
    }
}

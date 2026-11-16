<?php

namespace Modules\Laporan\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Laporan\Models\Laporan;
use Modules\TitikDrainase\Models\TitikDrainase;

class LaporanDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $warga = User::where('email', 'warga@smartdrain.test')->first();
        $rizqi = User::where('email', 'rizqi@smartdrain.test')->first();

        if (! $warga || ! $rizqi) {
            return;
        }

        $contoh = [
            ['titik' => 'Saluran Jalan Raya Soreang', 'user' => $warga, 'tinggi_air_cm' => 45, 'status_kondisi' => 'Meluap', 'deskripsi' => 'Air meluap ke badan jalan setinggi mata kaki setelah hujan deras.'],
            ['titik' => 'Drainase Margahayu Raya', 'user' => $warga, 'tinggi_air_cm' => 20, 'status_kondisi' => 'Tersumbat', 'deskripsi' => 'Banyak sampah plastik menutup mulut saluran sehingga aliran terhambat.'],
            ['titik' => 'Saluran Dayeuhkolot', 'user' => $rizqi, 'tinggi_air_cm' => 0, 'status_kondisi' => 'Normal', 'deskripsi' => 'Saluran kering dan tidak ada endapan sampah.'],
            ['titik' => 'Drainase Baleendah', 'user' => $rizqi, 'tinggi_air_cm' => 55, 'status_kondisi' => 'Meluap', 'deskripsi' => 'Air dari saluran meluap ke halaman warga di sekitar alun-alun.'],
            ['titik' => 'Saluran Cileunyi', 'user' => $warga, 'tinggi_air_cm' => 30, 'status_kondisi' => 'Tersumbat', 'deskripsi' => 'Sedimen pasir menumpuk di bawah flyover dan aliran melambat.'],
            ['titik' => 'Drainase Banjaran', 'user' => $rizqi, 'tinggi_air_cm' => 10, 'status_kondisi' => 'Normal', 'deskripsi' => 'Kondisi saluran bersih, hanya ada sedikit daun kering.'],
        ];

        foreach ($contoh as $item) {
            $titik = TitikDrainase::where('nama_titik', $item['titik'])->first();

            if (! $titik) {
                continue;
            }

            Laporan::updateOrCreate(
                [
                    'user_id' => $item['user']->id,
                    'titik_drainase_id' => $titik->id,
                    'deskripsi' => $item['deskripsi'],
                ],
                [
                    'tinggi_air_cm' => $item['tinggi_air_cm'],
                    'status_kondisi' => $item['status_kondisi'],
                ],
            );
        }
    }
}

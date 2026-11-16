<?php

namespace App\Http\Controllers;

use Modules\Laporan\Models\Laporan;
use Modules\TitikDrainase\Models\TitikDrainase;

class HomeController extends Controller
{
    public function index()
    {
        $titikDrainase = TitikDrainase::latest()->take(6)->get();

        return view('home', [
            'titikDrainase' => $titikDrainase,
            'totalTitik' => TitikDrainase::count(),
            'totalLaporan' => Laporan::count(),
        ]);
    }
}

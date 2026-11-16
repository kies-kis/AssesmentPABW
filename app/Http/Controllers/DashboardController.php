<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Laporan\Models\Laporan;
use Modules\TitikDrainase\Models\TitikDrainase;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user->isAdmin();

        $totalTitikDrainase = TitikDrainase::count();

        $base = Laporan::query()
            ->when(! $isAdmin, fn ($query) => $query->where('user_id', $user->id));

        $totalLaporan = (clone $base)->count();
        $totalMeluap = (clone $base)->where('status_kondisi', 'Meluap')->count();

        $ringkasan = (clone $base)
            ->select('status_kondisi', DB::raw('count(*) as jumlah'))
            ->groupBy('status_kondisi')
            ->pluck('jumlah', 'status_kondisi');

        $laporanTerbaru = (clone $base)
            ->with(['titikDrainase', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'isAdmin',
            'totalTitikDrainase',
            'totalLaporan',
            'totalMeluap',
            'ringkasan',
            'laporanTerbaru',
        ));
    }
}

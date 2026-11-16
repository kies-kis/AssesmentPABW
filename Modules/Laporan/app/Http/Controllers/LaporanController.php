<?php

namespace Modules\Laporan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Laporan\Http\Requests\LaporanRequest;
use Modules\Laporan\Models\Laporan;
use Modules\TitikDrainase\Models\TitikDrainase;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $base = Laporan::query()
            ->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id));

        $laporan = (clone $base)
            ->with(['titikDrainase', 'user'])
            ->when($request->filled('status'), fn ($query) => $query->where('status_kondisi', $request->query('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $ringkasan = (clone $base)
            ->select('status_kondisi', DB::raw('count(*) as jumlah'))
            ->groupBy('status_kondisi')
            ->pluck('jumlah', 'status_kondisi');

        $titikDrainase = TitikDrainase::orderBy('nama_titik')->get();

        return view('laporan::index', compact('laporan', 'titikDrainase', 'ringkasan'));
    }

    public function show(Request $request, Laporan $laporan)
    {
        $this->pastikanBolehMelihat($request, $laporan);

        return response()->json([
            'id' => $laporan->id,
            'titik_drainase_id' => $laporan->titik_drainase_id,
            'tinggi_air_cm' => $laporan->tinggi_air_cm,
            'status_kondisi' => $laporan->status_kondisi,
            'deskripsi' => $laporan->deskripsi,
            'foto_url' => $laporan->foto_bukti ? Storage::url($laporan->foto_bukti) : null,
        ]);
    }

    public function store(LaporanRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('foto_bukti')) {
            $data['foto_bukti'] = $request->file('foto_bukti')->store('laporan', 'public');
        }

        $laporan = Laporan::create($data)->load(['titikDrainase', 'user']);

        return response()->json([
            'message' => 'Laporan berhasil dikirim.',
            'html' => $this->barisHtml($laporan),
            'id' => $laporan->id,
        ], 201);
    }

    public function update(LaporanRequest $request, Laporan $laporan)
    {
        $this->pastikanBolehMengelola($request, $laporan);

        $data = $request->validated();

        if ($request->hasFile('foto_bukti')) {
            if ($laporan->foto_bukti) {
                Storage::disk('public')->delete($laporan->foto_bukti);
            }

            $data['foto_bukti'] = $request->file('foto_bukti')->store('laporan', 'public');
        }

        $laporan->update($data);
        $laporan->load(['titikDrainase', 'user']);

        return response()->json([
            'message' => 'Laporan berhasil diperbarui.',
            'html' => $this->barisHtml($laporan),
            'id' => $laporan->id,
        ]);
    }

    public function destroy(Request $request, Laporan $laporan)
    {
        $this->pastikanBolehMengelola($request, $laporan);

        if ($laporan->foto_bukti) {
            Storage::disk('public')->delete($laporan->foto_bukti);
        }

        $laporan->delete();

        return response()->json([
            'message' => 'Laporan berhasil dihapus.',
        ]);
    }

    private function barisHtml(Laporan $laporan): string
    {
        return view('laporan::partials.row', compact('laporan'))->render();
    }

    private function pastikanBolehMelihat(Request $request, Laporan $laporan): void
    {
        $user = $request->user();

        abort_unless($user->isAdmin() || $laporan->user_id === $user->id, 403, 'Anda tidak berhak mengakses laporan ini.');
    }

    private function pastikanBolehMengelola(Request $request, Laporan $laporan): void
    {
        $this->pastikanBolehMelihat($request, $laporan);
    }
}

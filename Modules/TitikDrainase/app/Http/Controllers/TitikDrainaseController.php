<?php

namespace Modules\TitikDrainase\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitikDrainase\Http\Requests\TitikDrainaseRequest;
use Modules\TitikDrainase\Models\TitikDrainase;

class TitikDrainaseController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));

        $titikDrainase = TitikDrainase::query()
            ->withCount('laporan')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($sub) use ($keyword) {
                    $sub->where('nama_titik', 'like', "%{$keyword}%")
                        ->orWhere('kecamatan', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('titikdrainase::index', compact('titikDrainase', 'keyword'));
    }

    public function create()
    {
        return view('titikdrainase::create');
    }

    public function store(TitikDrainaseRequest $request)
    {
        TitikDrainase::create($request->validated());

        return redirect()
            ->route('titik-drainase.index')
            ->with('success', 'Data titik drainase berhasil ditambahkan.');
    }

    public function show(TitikDrainase $titikDrainase)
    {
        $laporan = $titikDrainase->laporan()
            ->with('user')
            ->when(! auth()->user()->isAdmin(), fn ($query) => $query->where('user_id', auth()->id()))
            ->latest()
            ->get();

        return view('titikdrainase::show', compact('titikDrainase', 'laporan'));
    }

    public function edit(TitikDrainase $titikDrainase)
    {
        return view('titikdrainase::edit', compact('titikDrainase'));
    }

    public function update(TitikDrainaseRequest $request, TitikDrainase $titikDrainase)
    {
        $titikDrainase->update($request->validated());

        return redirect()
            ->route('titik-drainase.index')
            ->with('success', 'Data titik drainase berhasil diperbarui.');
    }

    public function destroy(TitikDrainase $titikDrainase)
    {
        if ($titikDrainase->laporan()->exists()) {
            return back()->with(
                'error',
                'Titik drainase tidak dapat dihapus karena sudah dirujuk oleh laporan warga.',
            );
        }

        $titikDrainase->delete();

        return redirect()
            ->route('titik-drainase.index')
            ->with('success', 'Data titik drainase berhasil dihapus.');
    }
}

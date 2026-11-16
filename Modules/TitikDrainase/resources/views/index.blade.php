@extends('layouts.app')

@section('title', 'Titik Drainase')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Data Titik Drainase</h1>
            <p class="text-muted mb-0 small">Daftar saluran drainase yang terdaftar di Kabupaten Bandung.</p>
        </div>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('titik-drainase.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Titik
            </a>
        @endif
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('titik-drainase.index') }}" class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label for="q" class="form-label">Cari titik drainase</label>
                    <input type="text" id="q" name="q" value="{{ $keyword }}" class="form-control"
                           placeholder="Nama titik atau kecamatan">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                    <a href="{{ route('titik-drainase.index') }}" class="btn btn-outline-secondary" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Titik</th>
                        <th>Kecamatan</th>
                        <th>Deskripsi</th>
                        <th>Kedalaman</th>
                        <th>Laporan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($titikDrainase as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->nama_titik }}</td>
                            <td>{{ $item->kecamatan }}</td>
                            <td class="text-muted small">{{ Str::limit($item->deskripsi, 60) ?: '-' }}</td>
                            <td>{{ $item->kedalaman_cm !== null ? $item->kedalaman_cm.' cm' : '-' }}</td>
                            <td><span class="badge text-bg-light border">{{ $item->laporan_count }}</span></td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('titik-drainase.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                    @if (auth()->user()->isAdmin())
                                        <a href="{{ route('titik-drainase.edit', $item) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('titik-drainase.destroy', $item) }}"
                                              onsubmit="return confirm('Hapus titik drainase {{ $item->nama_titik }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-search fs-2 d-block mb-2"></i>
                                    {{ $keyword !== '' ? 'Tidak ada titik drainase yang cocok dengan pencarian.' : 'Belum ada data titik drainase.' }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">
            {{ $titikDrainase->links() }}
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Titik Drainase')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('titik-drainase.index') }}">Titik Drainase</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $titikDrainase->nama_titik }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <div>
                    <h1 class="h4 fw-bold mb-1">{{ $titikDrainase->nama_titik }}</h1>
                    <p class="text-muted mb-0 small"><i class="bi bi-geo-alt me-1"></i>{{ $titikDrainase->kecamatan }}</p>
                </div>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('titik-drainase.edit', $titikDrainase) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                @endif
            </div>

            <dl class="row mb-0 small">
                <dt class="col-sm-4 text-muted fw-normal">Kedalaman saluran</dt>
                <dd class="col-sm-8">{{ $titikDrainase->kedalaman_cm !== null ? $titikDrainase->kedalaman_cm.' cm' : '-' }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">Deskripsi</dt>
                <dd class="col-sm-8">{{ $titikDrainase->deskripsi ?: '-' }}</dd>

                <dt class="col-sm-4 text-muted fw-normal">Ditambahkan</dt>
                <dd class="col-sm-8">{{ $titikDrainase->created_at->format('d F Y H:i') }}</dd>
            </dl>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <i class="bi bi-clock-history me-1"></i>
            {{ auth()->user()->isAdmin() ? 'Riwayat laporan titik ini' : 'Laporan saya untuk titik ini' }}
        </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            @if (auth()->user()->isAdmin())
                                <th>Pelapor</th>
                            @endif
                            <th>Tinggi Air</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporan as $item)
                            <tr>
                                @if (auth()->user()->isAdmin())
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                @endif
                                <td>{{ $item->tinggi_air_cm }} cm</td>
                                <td><span class="badge text-bg-{{ $item->badgeStatus() }}">{{ $item->status_kondisi }}</span></td>
                                <td class="text-muted small">{{ Str::limit($item->deskripsi, 60) }}</td>
                                <td class="text-muted small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}" class="text-center text-muted py-4">
                                    Belum ada laporan untuk titik ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </div>
@endsection

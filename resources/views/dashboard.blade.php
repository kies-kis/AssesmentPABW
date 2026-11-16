@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Halo, {{ auth()->user()->name }}</h1>
            <p class="text-muted mb-0 small">
                Anda masuk sebagai
                <span class="badge {{ auth()->user()->isAdmin() ? 'text-bg-warning' : 'text-bg-info' }}">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
                &middot; {{ $isAdmin ? 'Pantau seluruh data drainase.' : 'Kelola laporan kondisi saluran Anda.' }}
            </p>
        </div>
        <a href="{{ route('laporan.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Buat Laporan
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card stat-card h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">Total Titik Drainase</div>
                        <div class="stat-value">{{ $totalTitikDrainase }}</div>
                    </div>
                    <div class="stat-icon text-bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card stat-card h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">{{ $isAdmin ? 'Seluruh Laporan' : 'Laporan Saya' }}</div>
                        <div class="stat-value">{{ $totalLaporan }}</div>
                    </div>
                    <div class="stat-icon text-teal bg-success bg-opacity-10">
                        <i class="bi bi-megaphone"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card stat-card h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">Laporan Meluap</div>
                        <div class="stat-value">{{ $totalMeluap }}</div>
                    </div>
                    <div class="stat-icon text-danger bg-danger bg-opacity-10">
                        <i class="bi bi-water"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach (\Modules\Laporan\Models\Laporan::STATUS_KONDISI as $status => $label)
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Status {{ $label }}</div>
                            <div class="fw-bold fs-5">{{ $ringkasan->get($status, 0) }}</div>
                        </div>
                        <span class="badge text-bg-{{ match ($status) { 'Normal' => 'success', 'Tersumbat' => 'warning', default => 'danger' } }}">
                            {{ $label }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-check me-1"></i> {{ $isAdmin ? 'Laporan terbaru seluruh warga' : 'Laporan terbaru saya' }}</span>
            <a href="{{ route('laporan.index') }}" class="small text-decoration-none">Semua laporan</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Titik Drainase</th>
                        <th>Kecamatan</th>
                        @if ($isAdmin)
                            <th>Pelapor</th>
                        @endif
                        <th>Tinggi Air</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporanTerbaru as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->titikDrainase->nama_titik ?? '-' }}</td>
                            <td class="text-muted small">{{ $item->titikDrainase->kecamatan ?? '-' }}</td>
                            @if ($isAdmin)
                                <td>{{ $item->user->name ?? '-' }}</td>
                            @endif
                            <td>{{ $item->tinggi_air_cm }} cm</td>
                            <td><span class="badge text-bg-{{ $item->badgeStatus() }}">{{ $item->status_kondisi }}</span></td>
                            <td class="text-muted small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 6 : 5 }}">
                                <div class="empty-state py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada laporan.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

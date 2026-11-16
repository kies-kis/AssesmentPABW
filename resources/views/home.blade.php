@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="hero p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge mb-3">Sistem Pelaporan dan Pemantauan Drainase</span>
                <h1 class="fw-bold mb-3" style="letter-spacing:-.03em">Pantau dan laporkan kondisi saluran drainase Kabupaten Bandung.</h1>
                <p class="mb-4 text-white-50" style="max-width:54ch">
                    SmartDrain membantu petugas mengelola data titik drainase dan memudahkan warga
                    menyampaikan laporan kondisi saluran lengkap dengan tinggi muka air dan foto bukti.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @auth
                        <a href="{{ route('laporan.index') }}" class="btn btn-light fw-semibold px-3">
                            <i class="bi bi-megaphone me-1"></i> Buat Laporan
                        </a>
                        <a href="{{ route('titik-drainase.index') }}" class="btn btn-outline-light px-3">Lihat Titik Drainase</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light fw-semibold px-3">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light px-3">Daftar Warga</a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 rounded-3 h-100" style="background:rgba(255,255,255,.1)">
                            <div class="fs-4 fw-bold">{{ $totalTitik }}</div>
                            <div class="small text-white-50">Titik drainase terdata</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 h-100" style="background:rgba(255,255,255,.1)">
                            <div class="fs-4 fw-bold">{{ $totalLaporan }}</div>
                            <div class="small text-white-50">Laporan warga</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:rgba(255,255,255,.1)">
                            <i class="bi bi-shield-check fs-5"></i>
                            <div class="fw-semibold mt-1">Akses berbasis role</div>
                            <div class="small text-white-50">Admin mengelola data, warga mengirim dan mengelola laporan sendiri.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <h2 class="h5 fw-bold mb-1">Titik drainase terbaru</h2>
            <p class="text-muted mb-0 small">Data saluran yang terdaftar di SmartDrain.</p>
        </div>
        @auth
            <a href="{{ route('titik-drainase.index') }}" class="btn btn-sm btn-outline-primary">Lihat semua</a>
        @endauth
    </div>

    <div class="row g-3">
        @forelse ($titikDrainase as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-1">{{ $item->nama_titik }}</h3>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt me-1"></i>{{ $item->kecamatan }}
                        </p>
                        <p class="small mb-2">{{ Str::limit($item->deskripsi, 90) ?: 'Belum ada deskripsi.' }}</p>
                        <span class="badge text-bg-light border">
                            Kedalaman {{ $item->kedalaman_cm !== null ? $item->kedalaman_cm.' cm' : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="empty-state">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        Belum ada data titik drainase.
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection

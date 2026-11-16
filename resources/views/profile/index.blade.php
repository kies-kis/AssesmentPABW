@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header"><i class="bi bi-person me-1"></i> Profil Pengguna</div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="stat-icon text-bg-primary bg-opacity-10 text-primary" style="width:64px;height:64px;font-size:1.8rem">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div>
                            <h2 class="h5 fw-bold mb-1">{{ $user->name }}</h2>
                            <span class="badge {{ $user->isAdmin() ? 'text-bg-warning' : 'text-bg-info' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>

                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted fw-normal">Nama</dt>
                        <dd class="col-sm-8">{{ $user->name }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Email</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Role</dt>
                        <dd class="col-sm-8">{{ ucfirst($user->role) }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Terdaftar sejak</dt>
                        <dd class="col-sm-8">{{ $user->created_at->format('d F Y H:i') }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Jumlah laporan</dt>
                        <dd class="col-sm-8">{{ $user->laporan()->count() }}</dd>
                    </dl>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                        <a href="{{ route('laporan.index') }}" class="btn btn-primary">
                            <i class="bi bi-megaphone me-1"></i> Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Tambah Titik Drainase')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('titik-drainase.index') }}">Titik Drainase</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header">Form Tambah Titik Drainase</div>
        <div class="card-body">
            <form method="POST" action="{{ route('titik-drainase.store') }}">
                @csrf
                @include('titikdrainase::_form')

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('titik-drainase.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

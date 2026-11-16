@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="auth-wrap">
        <div class="card auth-card shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <i class="bi bi-water fs-1 text-teal"></i>
                    <h1 class="h4 fw-bold mt-2 mb-1">Masuk ke SmartDrain</h1>
                    <p class="text-muted small mb-0">Gunakan akun admin atau warga yang sudah terdaftar.</p>
                </div>

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="nama@lingkungan.ac.id" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold">Login</button>
                </form>

                <p class="text-center small text-muted mt-4 mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-decoration-none">Daftar sebagai warga</a>
                </p>
            </div>
        </div>
    </div>
@endsection

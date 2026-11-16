<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') &middot; Assesment_SmartDrain</title>
    <link href="{{ asset('assets/vendor/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons.css') }}" rel="stylesheet">
    <script>
        (function () {
            document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('sd-theme') || 'light');
        })();
    </script>
    <style>
        :root {
            --sd-navy: #14324f;
            --sd-navy-soft: #1d466b;
            --sd-teal: #0f766e;
            --sd-bg: #f4f7f9;
            --sd-line: #dfe7ee;
        }

        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--sd-bg);
            color: #1f2937;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main { flex: 1; }

        .sd-navbar { background: var(--sd-navy); }
        .sd-navbar .navbar-brand { font-weight: 700; letter-spacing: -.02em; }
        .sd-navbar .nav-link { color: rgba(255, 255, 255, .78); font-weight: 500; }
        .sd-navbar .nav-link:hover,
        .sd-navbar .nav-link.active { color: #fff; }

        .card { border: 1px solid var(--sd-line); border-radius: .8rem; }
        .card-header { background: transparent; border-bottom: 1px solid var(--sd-line); font-weight: 600; }

        .stat-card .stat-value { font-size: 1.9rem; font-weight: 700; line-height: 1.1; }
        .stat-card .stat-icon {
            width: 46px; height: 46px; display: grid; place-items: center;
            border-radius: .65rem; font-size: 1.25rem;
        }

        .table > :not(caption) > * > * { padding: .75rem .7rem; vertical-align: middle; }
        .table thead th { white-space: nowrap; font-size: .8rem; text-transform: uppercase; letter-spacing: .03em; }

        .form-label { font-weight: 600; font-size: .875rem; }

        .hero {
            background: linear-gradient(135deg, var(--sd-navy) 0%, var(--sd-navy-soft) 100%);
            color: #fff; border-radius: 1rem;
        }
        .hero .badge { background: rgba(255, 255, 255, .16); font-weight: 500; }

        .auth-wrap { min-height: calc(100vh - 180px); display: grid; place-items: center; }
        .auth-card { width: 100%; max-width: 440px; }

        .empty-state { padding: 2.5rem 1rem; text-align: center; color: #64748b; }

        footer.sd-footer { border-top: 1px solid var(--sd-line); color: #64748b; font-size: .875rem; }

        .btn-primary {
            --bs-btn-bg: var(--sd-navy);
            --bs-btn-border-color: var(--sd-navy);
            --bs-btn-hover-bg: var(--sd-navy-soft);
            --bs-btn-hover-border-color: var(--sd-navy-soft);
            --bs-btn-active-bg: var(--sd-navy-soft);
            --bs-btn-active-border-color: var(--sd-navy-soft);
        }
        .btn-outline-primary {
            --bs-btn-color: var(--sd-navy);
            --bs-btn-border-color: var(--sd-navy);
            --bs-btn-hover-bg: var(--sd-navy);
            --bs-btn-hover-border-color: var(--sd-navy);
        }
        .text-teal { color: var(--sd-teal) !important; }

        .bukti-thumb { width: 64px; height: 46px; object-fit: cover; border-radius: .35rem; }

        [data-bs-theme="dark"] body { background: #101820; color: #dbe4ec; }
        [data-bs-theme="dark"] .card { background: #17222d; border-color: #263442; }
        [data-bs-theme="dark"] .card-header { border-color: #263442; }
        [data-bs-theme="dark"] .table { --bs-table-bg: transparent; --bs-table-color: #dbe4ec; }
        [data-bs-theme="dark"] .table-light { --bs-table-bg: #1c2a36; --bs-table-color: #c7d3de; }
        [data-bs-theme="dark"] .empty-state { color: #93a4b3; }
        [data-bs-theme="dark"] .sd-footer { border-color: #263442; color: #93a4b3; }
    </style>
    @stack('styles')
</head>
<body>
    @include('partials.navbar')

    <main class="container py-4">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="sd-footer mt-4 py-3">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span>SmartDrain &mdash; Sistem Pelaporan dan Pemantauan Drainase Kabupaten Bandung</span>
            <span>PABW 2026</span>
        </div>
    </footer>

    <script src="{{ asset('assets/vendor/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.getElementById('theme-toggle')?.addEventListener('click', function () {
            const root = document.documentElement;
            const next = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-bs-theme', next);
            localStorage.setItem('sd-theme', next);
        });
    </script>
    @stack('scripts')
</body>
</html>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JobStock Account')</title>
    <meta name="theme-color" content="#f5f7fc">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/jobstock/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/jobstock/css/colors.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/jobstock-app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/jobstock-auth.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="jobstock-auth-body">
    <div class="auth-topbar">
        <div class="container auth-topbar-inner">
            <a class="jobstock-brand auth-brand" href="{{ route('home') }}" aria-label="JobStock home">
                <span class="brand-mark"><i class="bi bi-briefcase-fill" aria-hidden="true"></i></span>
                <span>JobStock<span class="brand-period">.</span></span>
            </a>
            <a class="auth-back-link" href="{{ route('home') }}"><i class="bi bi-arrow-left"></i> Back to website</a>
        </div>
    </div>
    <main class="auth-main">
        @yield('content')
    </main>
    <div class="auth-bottom-note">© {{ date('Y') }} JobStock. Built for better career connections.</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/jobstock-app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'JobStock'))</title>
    <meta name="description" content="@yield('meta_description', 'Discover opportunities and connect with great employers on JobStock.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('public/assets/jobstock/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/jobstock/css/colors.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/css/jobstock-app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @include('layouts.partials.header')
    <main>@yield('content')</main>
    @include('layouts.partials.footer')
    <button type="button" class="jobstock-backtop" aria-label="Back to top" data-backtop><i class="bi bi-arrow-up"></i></button>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('public/assets/js/jobstock-app.js') }}"></script>
    @stack('scripts')
</body>
</html>

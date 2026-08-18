<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ current_language()?->is_rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', __('Find your next job. Browse thousands of listings from companies around the world.'))">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / social previews --}}
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', __('Find your next job.'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    {{-- Structured data (JSON-LD) — helps both traditional search (SEO) and AI answer
         engines (AEO/GEO) understand page content precisely rather than guessing from
         rendered HTML. Page-specific schema (JobPosting, Organization, etc.) is yielded
         per-view below; this default covers the site itself. --}}
    @hasSection('structured_data')
        @yield('structured_data')
    @else
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => url('/'),
        ]) !!}
        </script>
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <nav class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">{{ config('app.name') }}</a>
            <div class="flex items-center gap-6 text-sm">
                <a href="{{ route('jobs.index') }}" class="hover:text-indigo-600">{{ __('Find Jobs') }}</a>
                <a href="{{ route('companies.index') }}" class="hover:text-indigo-600">{{ __('Companies') }}</a>

                @auth('web')
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">{{ __('Dashboard') }}</a>
                    <a href="{{ route('profile.edit') }}" class="hover:text-indigo-600">{{ __('My Profile') }}</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="hover:text-indigo-600">{{ __('Logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-indigo-600">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">{{ __('Sign Up') }}</a>
                @endauth

                <a href="{{ route('company.dashboard') }}" class="text-gray-500 hover:text-indigo-600">{{ __('For Employers') }}</a>

                @include('partials.language-currency-switcher')
            </div>
        </div>
    </nav>

    @if (session('status'))
        <div class="max-w-6xl mx-auto px-4 mt-4">
            <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-3 text-sm">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-6xl mx-auto px-4 mt-4">
            <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="border-t mt-16 py-8 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
    </footer>
</body>
</html>

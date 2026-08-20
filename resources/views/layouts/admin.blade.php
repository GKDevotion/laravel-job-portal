<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ current_language()?->is_rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-gray-900 text-gray-100 flex flex-col">
            <div class="p-4 border-b border-gray-800 font-bold">{{ config('app.name') }} Admin</div>
            <nav class="flex-1 p-4 space-y-1 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-800">{{ __('Dashboard') }}</a>
                <a href="{{ route('admin.companies.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-800">{{ __('Companies') }}</a>
                <a href="{{ route('admin.jobs.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-800">{{ __('Job Moderation') }}</a>
                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-800">{{ __('Job Seekers') }}</a>
                <a href="{{ route('admin.invoices.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-800">{{ __('Invoices') }}</a>
                <div class="pt-3 mt-3 border-t border-gray-800 text-xs text-gray-500 uppercase px-3">{{ __('Settings') }}</div>
                <a href="{{ route('admin.settings.languages') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-800">{{ __('Languages') }}</a>
                <a href="{{ route('admin.settings.currencies') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-800">{{ __('Currencies') }}</a>
                <a href="{{ route('admin.settings.translations') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-800">{{ __('Translations') }}</a>
            </nav>
            <div class="p-4 border-t border-gray-800 text-sm">
                <div class="font-medium">{{ auth('admin')->user()->name }}</div>
                <form method="POST" action="{{ route('admin.logout') }}" class="mt-2">@csrf
                    <button class="text-red-400 hover:underline">{{ __('Logout') }}</button>
                </form>
            </div>
        </aside>
        <div class="flex-1">
            <main class="max-w-5xl mx-auto px-6 py-8">
                @if (session('status'))
                    <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-3 text-sm mb-6">{{ session('status') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

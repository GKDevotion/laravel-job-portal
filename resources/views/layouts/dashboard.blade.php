<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employer Dashboard').' - '.{{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    @php
        $guard = auth('company')->check() ? 'company' : 'employee';
        $actor = auth($guard)->user();
        $prefix = $guard === 'company' ? 'company' : 'employee';
    @endphp

    <div class="flex min-h-screen">
        <aside class="w-64 bg-white border-r flex flex-col">
            <div class="p-4 border-b font-bold text-indigo-600">{{ config('app.name') }}</div>
            <nav class="flex-1 p-4 space-y-1 text-sm">
                <a href="{{ route($prefix.'.dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-indigo-50">{{ __('Dashboard') }}</a>
                <a href="{{ route($prefix.'.jobs.index') }}" class="block px-3 py-2 rounded-lg hover:bg-indigo-50">{{ __('Job Listings') }}</a>
                <a href="{{ route($prefix.'.applications.index') }}" class="block px-3 py-2 rounded-lg hover:bg-indigo-50">{{ __('Applicants') }}</a>
                @if ($guard === 'company')
                    <a href="{{ route('company.employees.index') }}" class="block px-3 py-2 rounded-lg hover:bg-indigo-50">{{ __('Team') }}</a>
                    @if (\Illuminate\Support\Facades\Route::has('company.invoices.index'))
                        <a href="{{ route('company.invoices.index') }}" class="block px-3 py-2 rounded-lg hover:bg-indigo-50">{{ __('Invoices') }}</a>
                    @else
                        <span class="block px-3 py-2 rounded-lg text-gray-400 cursor-not-allowed">{{ __('Invoices (coming in Phase 5)') }}</span>
                    @endif
                @endif
            </nav>
            <div class="p-4 border-t text-sm">
                <div class="font-medium">{{ $guard === 'company' ? $actor->company_name : $actor->name }}</div>
                <form method="POST" action="{{ route($prefix.'.logout') }}" class="mt-2">@csrf
                    <button class="text-red-600 hover:underline">{{ __('Logout') }}</button>
                </form>
            </div>
        </aside>

        <div class="flex-1">
            <main class="max-w-5xl mx-auto px-6 py-8">
                @if (session('status'))
                    <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-3 text-sm mb-6">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 text-sm mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Admin Login') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="max-w-sm w-full bg-white rounded-xl p-8">
        <h1 class="text-xl font-bold mb-6 text-center">{{ config('app.name') }} Admin</h1>
        @if ($errors->any())
            <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 text-sm mb-4">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">{{ __('Password') }}</label>
                <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2">
            </div>
            <button type="submit" class="w-full bg-gray-900 text-white rounded-lg py-2 hover:bg-black">{{ __('Login') }}</button>
        </form>
    </div>
</body>
</html>

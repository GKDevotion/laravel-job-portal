@extends('layouts.admin')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Job Seekers') }}</h1>
<form method="GET" class="mb-6"><input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Search by name or email...') }}" class="border rounded-lg px-3 py-2 w-full max-w-md text-sm"></form>
<div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr><th class="px-4 py-3">{{ __('Name') }}</th><th class="px-4 py-3">{{ __('Applications') }}</th><th class="px-4 py-3">{{ __('Status') }}</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($users as $user)
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <img src="{{ $user->avatarUrl() }}" class="w-7 h-7 rounded-full object-cover" alt="{{ $user->name }}">
                            <div>
                                <p class="font-medium">{{ $user->name }}</p>
                                <p class="text-gray-500 text-xs">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ $user->applications_count }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded-full {{ $user->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->status ? __('Active') : __('Suspended') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                            @csrf @method('PATCH')
                            <button class="text-indigo-600 hover:underline">{{ $user->status ? __('Suspend') : __('Reactivate') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">{{ __('No users found.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection

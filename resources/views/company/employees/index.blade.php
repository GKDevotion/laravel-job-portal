@extends('layouts.dashboard')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold">{{ __('Your Team') }}</h1>
    <a href="{{ route('company.employees.create') }}" class="bg-indigo-600 text-white rounded-lg px-4 py-2 text-sm hover:bg-indigo-700">+ {{ __('Invite Employee') }}</a>
</div>
<div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr><th class="px-4 py-3">{{ __('Name') }}</th><th class="px-4 py-3">{{ __('Role') }}</th><th class="px-4 py-3">{{ __('Status') }}</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($employees as $employee)
                <tr>
                    <td class="px-4 py-3">
                        <p class="font-medium">{{ $employee->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $employee->email }}</p>
                    </td>
                    <td class="px-4 py-3">{{ $employee->getRoleNames()->first() ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded-full {{ $employee->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $employee->status ? __('Active') : __('Suspended') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-3">
                        <form method="POST" action="{{ route('company.employees.toggle-status', $employee) }}" class="inline">
                            @csrf @method('PATCH')
                            <button class="text-indigo-600 hover:underline">{{ $employee->status ? __('Suspend') : __('Reactivate') }}</button>
                        </form>
                        <form method="POST" action="{{ route('company.employees.destroy', $employee) }}" class="inline" onsubmit="return confirm('{{ __('Remove this employee?') }}')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">{{ __('Remove') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">{{ __('No team members yet.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $employees->links() }}</div>
@endsection

@extends('layouts.app')
@section('title', __('My Applications'))
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('My Applications') }}</h1>
<div class="bg-white border rounded-xl divide-y">
    @forelse ($applications as $app)
        <div class="p-4 flex justify-between items-center text-sm">
            <div>
                <p class="font-medium">{{ $app->jobListing->title }}</p>
                <p class="text-gray-500">{{ $app->jobListing->company->company_name }} &middot; {{ $app->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs px-2 py-1 rounded-full bg-gray-100">{{ ucfirst($app->status) }}</span>
                @if (!in_array($app->status, ['hired', 'rejected', 'withdrawn']))
                    <form method="POST" action="{{ route('applications.withdraw', $app) }}" onsubmit="return confirm('{{ __('Withdraw this application?') }}')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">{{ __('Withdraw') }}</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p class="p-6 text-gray-500 text-sm text-center">{{ __("You haven't applied to any jobs yet.") }}</p>
    @endforelse
</div>
<div class="mt-4">{{ $applications->links() }}</div>
@endsection

@extends('layouts.admin')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Job Moderation') }}</h1>
<form method="GET" class="mb-6 text-sm">
    <select name="status" onchange="this.form.submit()" class="border rounded-lg px-3 py-2">
        @foreach (['pending_review' => 'Pending Review', 'published' => 'Published', 'rejected' => 'Rejected', 'closed' => 'Closed'] as $val => $label)
            <option value="{{ $val }}" @selected(request('status', 'pending_review') === $val)>{{ __($label) }}</option>
        @endforeach
    </select>
</form>
<div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr><th class="px-4 py-3">{{ __('Title') }}</th><th class="px-4 py-3">{{ __('Company') }}</th><th class="px-4 py-3">{{ __('Category') }}</th><th class="px-4 py-3">{{ __('Submitted') }}</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($jobs as $job)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $job->title }}</td>
                    <td class="px-4 py-3">{{ $job->company->company_name }}</td>
                    <td class="px-4 py-3">{{ $job->category->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $job->updated_at->diffForHumans() }}</td>
                    <td class="px-4 py-3 text-right"><a href="{{ route('admin.jobs.show', $job) }}" class="text-indigo-600 hover:underline">{{ __('Review') }}</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __('Nothing here.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $jobs->links() }}</div>
@endsection

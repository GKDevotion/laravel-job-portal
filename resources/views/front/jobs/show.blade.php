@extends('layouts.app')

@section('title', $jobListing->title.' at '.$jobListing->company->company_name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($jobListing->description), 160))

@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'JobPosting',
    'title' => $jobListing->title,
    'description' => $jobListing->description,
    'datePosted' => optional($jobListing->published_at)->toIso8601String(),
    'validThrough' => optional($jobListing->application_deadline)->toIso8601String(),
    'employmentType' => strtoupper(str_replace(' ', '_', $jobListing->type->name)),
    'hiringOrganization' => [
        '@type' => 'Organization',
        'name' => $jobListing->company->company_name,
        'sameAs' => $jobListing->company->website,
        'logo' => $jobListing->company->logoUrl(),
    ],
    'jobLocation' => [
        '@type' => 'Place',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $jobListing->city,
            'addressCountry' => $jobListing->country,
        ],
    ],
    'jobLocationType' => $jobListing->work_mode === 'remote' ? 'TELECOMMUTE' : null,
    'baseSalary' => $jobListing->salary_min ? [
        '@type' => 'MonetaryAmount',
        'currency' => $jobListing->currency?->code ?? 'USD',
        'value' => [
            '@type' => 'QuantitativeValue',
            'minValue' => (float) $jobListing->salary_min,
            'maxValue' => (float) $jobListing->salary_max,
            'unitText' => strtoupper(substr($jobListing->salary_period, 0, 1)),
        ],
    ] : null,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection

@section('content')
<div class="grid grid-cols-3 gap-8">
    <div class="col-span-2">
        <h1 class="text-2xl font-bold">{{ $jobListing->title }}</h1>
        <p class="text-gray-500 mt-1">
            <a href="{{ route('companies.show', $jobListing->company) }}" class="hover:text-indigo-600">{{ $jobListing->company->company_name }}</a>
            &middot; {{ $jobListing->city ? $jobListing->city.', '.$jobListing->country : ucfirst($jobListing->work_mode) }}
        </p>

        <div class="flex gap-2 mt-4">
            <span class="text-xs bg-gray-100 px-3 py-1 rounded-full">{{ $jobListing->type->name }}</span>
            <span class="text-xs bg-gray-100 px-3 py-1 rounded-full">{{ ucfirst($jobListing->work_mode) }}</span>
            @if ($jobListing->experience_level)
                <span class="text-xs bg-gray-100 px-3 py-1 rounded-full">{{ ucfirst($jobListing->experience_level) }}</span>
            @endif
        </div>

        <div class="prose max-w-none mt-8">
            <h2 class="text-lg font-semibold">{{ __('Description') }}</h2>
            <p class="whitespace-pre-line">{{ $jobListing->description }}</p>

            @if ($jobListing->requirements)
                <h2 class="text-lg font-semibold mt-6">{{ __('Requirements') }}</h2>
                <p class="whitespace-pre-line">{{ $jobListing->requirements }}</p>
            @endif

            @if ($jobListing->benefits)
                <h2 class="text-lg font-semibold mt-6">{{ __('Benefits') }}</h2>
                <p class="whitespace-pre-line">{{ $jobListing->benefits }}</p>
            @endif
        </div>

        @if ($jobListing->skills->isNotEmpty())
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach ($jobListing->skills as $skill)
                    <span class="text-xs bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full">{{ $skill->name }}</span>
                @endforeach
            </div>
        @endif
    </div>

    <div>
        <div class="bg-white border rounded-xl p-5 sticky top-4">
            @if ($jobListing->salary_min || $jobListing->salary_max)
                <p class="font-semibold text-lg">
                    {{ money($jobListing->salary_min) }} &ndash; {{ money($jobListing->salary_max) }}
                    <span class="text-sm font-normal text-gray-500">/ {{ $jobListing->salary_period }}</span>
                </p>
            @endif

            <p class="text-sm text-gray-500 mt-2">
                {{ __(':count applied', ['count' => $jobListing->applications_count]) }}
                @if ($jobListing->application_deadline)
                    &middot; {{ __('Deadline') }}: {{ $jobListing->application_deadline->format('M d, Y') }}
                @endif
            </p>

            @if (! $jobListing->isOpen())
                <p class="mt-4 text-sm bg-gray-100 rounded-lg px-3 py-2">{{ __('This job is no longer accepting applications.') }}</p>
            @elseif ($alreadyApplied)
                <p class="mt-4 text-sm bg-green-50 text-green-700 rounded-lg px-3 py-2">{{ __('You already applied to this job.') }}</p>
            @elseif (auth('web')->check())
                <form method="POST" action="{{ route('jobs.apply', $jobListing) }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                    @csrf
                    @if (auth('web')->user()->getFirstMedia('resume'))
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="use_profile_resume" value="1" checked>
                            {{ __('Use resume from my profile') }}
                        </label>
                    @endif
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Or upload a resume') }}</label>
                        <input type="file" name="resume" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Cover Letter (optional)') }}</label>
                        <textarea name="cover_letter" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 hover:bg-indigo-700">
                        {{ __('Apply Now') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-center mt-4 bg-indigo-600 text-white rounded-lg py-2 hover:bg-indigo-700">
                    {{ __('Login to Apply') }}
                </a>
            @endif

            @auth('web')
                <form method="POST" action="{{ route('jobs.save', $jobListing) }}" class="mt-3">
                    @csrf
                    <button class="w-full border rounded-lg py-2 text-sm hover:bg-gray-50">{{ __('Save Job') }}</button>
                </form>
            @endauth
        </div>
    </div>
</div>

@if ($relatedJobs->isNotEmpty())
    <div class="mt-12">
        <h2 class="text-lg font-semibold mb-4">{{ __('Similar Jobs') }}</h2>
        <div class="grid grid-cols-2 gap-4">
            @foreach ($relatedJobs as $related)
                <a href="{{ route('jobs.show', $related) }}" class="block bg-white border rounded-xl p-4 hover:shadow-md transition">
                    <p class="font-medium">{{ $related->title }}</p>
                    <p class="text-sm text-gray-500">{{ $related->company->company_name }}</p>
                </a>
            @endforeach
        </div>
    </div>
@endif
@endsection

@php $j = $job; @endphp

<div>
    <label class="block text-sm font-medium mb-1">{{ __('Job Title') }}</label>
    <input type="text" name="title[en]" value="{{ old('title.en', $j?->getTranslation('title', 'en')) }}" required
           class="w-full border rounded-lg px-3 py-2">
    @error('title.en')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Category') }}</label>
        <select name="job_category_id" required class="w-full border rounded-lg px-3 py-2">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('job_category_id', $j?->job_category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Job Type') }}</label>
        <select name="job_type_id" required class="w-full border rounded-lg px-3 py-2">
            @foreach ($types as $type)
                <option value="{{ $type->id }}" @selected(old('job_type_id', $j?->job_type_id) == $type->id)>
                    {{ $type->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <label class="block text-sm font-medium mb-1">{{ __('Description') }}</label>
    <textarea name="description[en]" rows="6" required class="w-full border rounded-lg px-3 py-2">{{ old('description.en', $j?->getTranslation('description', 'en')) }}</textarea>
</div>

<div>
    <label class="block text-sm font-medium mb-1">{{ __('Requirements') }}</label>
    <textarea name="requirements[en]" rows="4" class="w-full border rounded-lg px-3 py-2">{{ old('requirements.en', $j?->getTranslation('requirements', 'en')) }}</textarea>
</div>

<div>
    <label class="block text-sm font-medium mb-1">{{ __('Benefits') }}</label>
    <textarea name="benefits[en]" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('benefits.en', $j?->getTranslation('benefits', 'en')) }}</textarea>
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Min Salary') }}</label>
        <input type="number" step="0.01" name="salary_min" value="{{ old('salary_min', $j?->salary_min) }}" class="w-full border rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Max Salary') }}</label>
        <input type="number" step="0.01" name="salary_max" value="{{ old('salary_max', $j?->salary_max) }}" class="w-full border rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Period') }}</label>
        <select name="salary_period" class="w-full border rounded-lg px-3 py-2">
            @foreach (['hourly', 'daily', 'weekly', 'monthly', 'yearly'] as $period)
                <option value="{{ $period }}" @selected(old('salary_period', $j?->salary_period ?? 'monthly') === $period)>{{ ucfirst($period) }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Work Mode') }}</label>
        <select name="work_mode" class="w-full border rounded-lg px-3 py-2">
            @foreach (['onsite' => 'On-site', 'remote' => 'Remote', 'hybrid' => 'Hybrid'] as $val => $label)
                <option value="{{ $val }}" @selected(old('work_mode', $j?->work_mode ?? 'onsite') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('City') }}</label>
        <input type="text" name="city" value="{{ old('city', $j?->city) }}" class="w-full border rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Country') }}</label>
        <input type="text" name="country" value="{{ old('country', $j?->country) }}" class="w-full border rounded-lg px-3 py-2">
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Vacancies') }}</label>
        <input type="number" min="1" name="vacancies" value="{{ old('vacancies', $j?->vacancies ?? 1) }}" required class="w-full border rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">{{ __('Application Deadline') }}</label>
        <input type="date" name="application_deadline" value="{{ old('application_deadline', $j?->application_deadline?->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2">
    </div>
</div>

<div>
    <label class="block text-sm font-medium mb-1">{{ __('Skills') }}</label>
    <select name="skills[]" multiple class="w-full border rounded-lg px-3 py-2 h-32">
        @foreach ($skills as $skill)
            <option value="{{ $skill->id }}" @selected(collect(old('skills', $j?->skills->pluck('id')->toArray() ?? []))->contains($skill->id))>
                {{ $skill->name }}
            </option>
        @endforeach
    </select>
    <p class="text-xs text-gray-500 mt-1">{{ __('Hold Ctrl/Cmd to select multiple.') }}</p>
</div>

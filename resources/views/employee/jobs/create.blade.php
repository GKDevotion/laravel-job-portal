@extends('layouts.dashboard')
@section('content')
<h1 class="text-xl font-bold mb-6">{{ __('Post a New Job') }}</h1>
<form method="POST" action="{{ route('employee.jobs.store') }}" class="space-y-5 bg-white border rounded-xl p-6">
    @csrf
    @include('company.jobs._form', ['job' => null])
    <button type="submit" class="bg-indigo-600 text-white rounded-lg px-5 py-2 hover:bg-indigo-700">{{ __('Save Draft') }}</button>
</form>
@endsection

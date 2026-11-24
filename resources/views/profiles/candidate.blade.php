@extends('layouts.app')

@section('title', 'Candidate profile | JobStock')

@section('content')
<section class="container py-5">
    <div class="content-panel p-4 p-md-5">
        <h1 class="h3 fw-bold">Candidate profile</h1>
        <p class="text-secondary">Present your experience and skills to employers.</p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                Please review the highlighted fields and try again.
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('candidate.profile.update') }}"
            enctype="multipart/form-data"
            class="row g-3"
        >
            @csrf
            @method('PUT')

            <div class="col-md-6">
                <label class="form-label" for="name">Full name</label>
                <input
                    id="name"
                    class="form-control"
                    name="name"
                    value="{{ old('name', auth()->user()->name) }}"
                    required
                >
            </div>

            <div class="col-md-6">
                <label class="form-label" for="phone">Phone</label>
                <input
                    id="phone"
                    class="form-control"
                    name="phone"
                    value="{{ old('phone', $profile->phone) }}"
                >
            </div>

            <div class="col-12">
                <label class="form-label" for="headline">Professional headline</label>
                <input
                    id="headline"
                    class="form-control"
                    name="headline"
                    value="{{ old('headline', $profile->headline) }}"
                >
            </div>

            <div class="col-12">
                <label class="form-label" for="location">Location</label>
                <input
                    id="location"
                    class="form-control"
                    name="location"
                    value="{{ old('location', $profile->location) }}"
                >
            </div>

            <div class="col-12">
                <label class="form-label" for="bio">About you</label>
                <textarea
                    id="bio"
                    class="form-control"
                    rows="4"
                    name="bio"
                >{{ old('bio', $profile->bio) }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label" for="skills">Skills (comma separated)</label>
                <input
                    id="skills"
                    class="form-control"
                    name="skills"
                    value="{{ old('skills', implode(', ', $profile->skills ?? [])) }}"
                >
            </div>

            <div class="col-md-6">
                <label class="form-label" for="linkedin_url">LinkedIn URL</label>
                <input
                    id="linkedin_url"
                    class="form-control"
                    type="url"
                    name="linkedin_url"
                    value="{{ old('linkedin_url', $profile->linkedin_url) }}"
                >
            </div>

            <div class="col-md-6">
                <label class="form-label" for="portfolio_url">Portfolio URL</label>
                <input
                    id="portfolio_url"
                    class="form-control"
                    type="url"
                    name="portfolio_url"
                    value="{{ old('portfolio_url', $profile->portfolio_url) }}"
                >
            </div>

            <div class="col-md-6">
                <label class="form-label" for="resume">Resume (PDF/DOC/DOCX, max 5MB)</label>
                <input
                    id="resume"
                    class="form-control"
                    type="file"
                    name="resume"
                    accept=".pdf,.doc,.docx"
                >
            </div>

            <div class="col-md-6">
                <label class="form-label" for="avatar">Profile photo (max 2MB)</label>
                <input
                    id="avatar"
                    class="form-control"
                    type="file"
                    name="avatar"
                    accept="image/*"
                >
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Save profile</button>
                <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection

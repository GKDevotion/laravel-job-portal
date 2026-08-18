<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('seeker.profile.edit', ['user' => Auth::guard('web')->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'designation' => ['nullable', 'string', 'max:150'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'experience_level' => ['nullable', 'string', 'max:50'],
            'is_open_to_work' => ['boolean'],
        ]);

        $user->update($data);

        return back()->with('status', 'Profile updated.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'], // 2MB
        ]);

        // addMediaFromRequest + singleFile() on the collection automatically removes the
        // previous avatar (from whichever disk it lived on) — no manual cleanup needed here,
        // and this works identically whether MEDIA_DISK is "public" or "s3".
        Auth::guard('web')->user()
            ->addMediaFromRequest('avatar')
            ->toMediaCollection('avatar');

        return back()->with('status', 'Profile photo updated.');
    }

    public function updateResume(Request $request): RedirectResponse
    {
        $request->validate([
            'resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB
        ]);

        Auth::guard('web')->user()
            ->addMediaFromRequest('resume')
            ->toMediaCollection('resume');

        return back()->with('status', 'Resume updated. It will be used as your default when applying.');
    }
}

<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('company.profile.edit', ['company' => Auth::guard('company')->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'about' => ['nullable', 'string', 'max:3000'],
            'website' => ['nullable', 'url', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'team_size' => ['nullable', 'string', 'max:20'],
            'founded_year' => ['nullable', 'integer', 'min:1800', 'max:'.date('Y')],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
        ]);

        Auth::guard('company')->user()->update($data);

        return back()->with('status', 'Company profile updated.');
    }

    public function updateLogo(Request $request): RedirectResponse
    {
        $request->validate(['logo' => ['required', 'image', 'max:2048']]);

        Auth::guard('company')->user()
            ->addMediaFromRequest('logo')
            ->toMediaCollection('logo');

        return back()->with('status', 'Logo updated.');
    }

    public function updateCoverImage(Request $request): RedirectResponse
    {
        $request->validate(['cover_image' => ['required', 'image', 'max:4096']]);

        Auth::guard('company')->user()
            ->addMediaFromRequest('cover_image')
            ->toMediaCollection('cover_image');

        return back()->with('status', 'Cover image updated.');
    }
}

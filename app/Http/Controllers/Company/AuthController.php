<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\RegisterRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('company.auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $request->slugFor($data['company_name']);
        $data['approval_status'] = 'pending'; // admin must approve before the company can post jobs

        $company = Company::create($data);
        $company->assignRole('Owner');

        Auth::guard('company')->login($company);

        return redirect()->route('company.dashboard')
            ->with('status', 'Account created! Your company is pending admin approval before you can publish jobs.');
    }

    public function showLogin(): View
    {
        return view('company.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::guard('company')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        $company = Auth::guard('company')->user();

        if (! $company->status) {
            Auth::guard('company')->logout();
            throw ValidationException::withMessages(['email' => 'This account has been suspended.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('company.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('company')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}

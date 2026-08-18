<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('employee.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::guard('employee')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        $employee = Auth::guard('employee')->user();

        if (! $employee->status) {
            Auth::guard('employee')->logout();
            throw ValidationException::withMessages(['email' => 'Your access has been suspended by your employer.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('employee.dashboard'));
    }

    // Invited employees land here from their invite email to set a password for the first time
    public function showSetPassword(Request $request): View
    {
        return view('employee.auth.set-password', [
            'token' => $request->route('token'),
            'email' => $request->query('email'),
        ]);
    }

    public function setPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::broker('employees')->reset(
            $data,
            function ($employee, $password) {
                $employee->forceFill(['password' => $password])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => __($status)]);
        }

        return redirect()->route('employee.login')->with('status', 'Password set — you can now log in.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}

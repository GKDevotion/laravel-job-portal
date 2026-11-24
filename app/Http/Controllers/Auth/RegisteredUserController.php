<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\CandidateProfile;
use App\Models\Company;
use App\Models\EmployerProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
class RegisteredUserController extends Controller
{
 public function createCandidate(): View { return view('auth.register', ['accountType'=>'candidate']); }
 public function createEmployer(): View { return view('auth.register', ['accountType'=>'employer']); }
 public function store(Request $request, string $type): RedirectResponse
 {
  abort_unless(in_array($type, ['candidate','employer'], true), 404);
  $rules = ['name'=>['required','string','max:120'], 'email'=>['required','string','lowercase','email','max:180','unique:users,email'], 'password'=>['required','confirmed',Rules\Password::min(8)], 'terms'=>['accepted']];
  if ($type === 'employer') $rules['company_name'] = ['required','string','max:180'];
  $data = $request->validate($rules);
  $user = DB::transaction(function () use ($data, $type) {
   $user = User::create(['name'=>$data['name'], 'email'=>$data['email'], 'password'=>Hash::make($data['password']), 'role'=>$type, 'status'=>'active']);
   if ($type === 'candidate') CandidateProfile::create(['user_id'=>$user->id]);
   else {
    $companyName = trim($data['company_name']);
    $company = Company::create(['name'=>$companyName, 'slug'=>Str::slug($companyName).'-'.Str::lower(Str::random(6)), 'is_active'=>true]);
    EmployerProfile::create(['user_id'=>$user->id, 'company_id'=>$company->id]);
   }
   return $user;
  });
  event(new Registered($user));
  auth()->login($user);
  return redirect()->route('verification.notice')->with('status', 'Registration successful. Please verify your email address.');
 }
}

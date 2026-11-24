<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
class ProfileController extends Controller
{
 public function editCandidate(Request $request): View { $profile = $request->user()->candidateProfile()->firstOrCreate([]); return view('profiles.candidate', compact('profile')); }
 public function updateCandidate(Request $request): RedirectResponse
 {
  $data = $request->validate(['name'=>['required','string','max:120'],'phone'=>['nullable','string','max:30'],'headline'=>['nullable','string','max:180'],'location'=>['nullable','string','max:180'],'bio'=>['nullable','string','max:5000'],'skills'=>['nullable','string','max:1500'],'linkedin_url'=>['nullable','url','max:255'],'portfolio_url'=>['nullable','url','max:255'],'resume'=>['nullable','file','mimes:pdf,doc,docx','max:5120'],'avatar'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:2048']]);
  $user=$request->user(); $user->update(['name'=>$data['name']]); $profile=$user->candidateProfile()->firstOrCreate([]);
  foreach (['resume'=>'resume_path','avatar'=>'avatar_path'] as $field=>$column) if ($request->hasFile($field)) { if ($profile->$column) Storage::disk('public')->delete($profile->$column); $data[$column]=$request->file($field)->store('jobstock/'.$field.'s','public'); }
  $profile->update(['phone'=>$data['phone']??null,'headline'=>$data['headline']??null,'location'=>$data['location']??null,'bio'=>$data['bio']??null,'skills'=>isset($data['skills']) ? array_values(array_filter(array_map('trim',explode(',',$data['skills'])))) : null,'linkedin_url'=>$data['linkedin_url']??null,'portfolio_url'=>$data['portfolio_url']??null,'resume_path'=>$data['resume_path']??$profile->resume_path,'avatar_path'=>$data['avatar_path']??$profile->avatar_path]);
  return back()->with('status','Candidate profile updated.');
 }
 public function editEmployer(Request $request): View { $profile=$request->user()->employerProfile()->with('company')->firstOrCreate([]); return view('profiles.employer',compact('profile')); }
 public function updateEmployer(Request $request): RedirectResponse
 {
  $data=$request->validate(['name'=>['required','string','max:120'],'job_title'=>['nullable','string','max:120'],'phone'=>['nullable','string','max:30'],'location'=>['nullable','string','max:180'],'company_name'=>['required','string','max:180'],'website'=>['nullable','url','max:255'],'description'=>['nullable','string','max:5000']]);
  $user=$request->user(); $user->update(['name'=>$data['name']]); $profile=$user->employerProfile()->firstOrCreate([]); $company=$profile->company;
  if (!$company) { $company=\App\Models\Company::create(['name'=>$data['company_name'],'slug'=>\Illuminate\Support\Str::slug($data['company_name']).'-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6)),'is_active'=>true]); $profile->company_id=$company->id; }
  $company->update(['name'=>$data['company_name'],'website'=>$data['website']??null,'location'=>$data['location']??null,'description'=>$data['description']??null]); $profile->update(['job_title'=>$data['job_title']??null,'phone'=>$data['phone']??null,'location'=>$data['location']??null,'company_id'=>$company->id]);
  return back()->with('status','Employer profile updated.');
 }
}

<?php
namespace App\Http\Controllers\Employer;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
class JobManagementController extends Controller {
 private function company(Request $request): Company { $company=$request->user()->employerProfile?->company; abort_unless($company,403,'Complete your employer/company profile before managing jobs.'); return $company; }
 public function index(Request $request): View { $company=$this->company($request); $jobs=$company->jobs()->with('category')->latest()->paginate(12); return view('employer.jobs.index',compact('jobs','company')); }
 public function create(): View { return view('employer.jobs.form',['job'=>new Job(),'categories'=>JobCategory::where('is_active',true)->orderBy('name')->get(),'skills'=>Skill::where('is_active',true)->orderBy('name')->get()]); }
 public function store(Request $request): RedirectResponse { $company=$this->company($request); $data=$this->validated($request); $skills=$data['skills']??[]; unset($data['skills']); $data['company_id']=$company->id; $data['slug']=$this->uniqueSlug($data['title']); $data['status']='pending'; $job=Job::create($data); $job->skills()->sync($skills); return redirect()->route('employer.jobs.index')->with('status','Job submitted for administrator review.'); }
 public function edit(Request $request, Job $job): View { $this->authorizeCompany($request,$job); abort_if($job->status==='published',403,'Published jobs cannot be edited in this release.'); return view('employer.jobs.form',['job'=>$job,'categories'=>JobCategory::where('is_active',true)->orderBy('name')->get(),'skills'=>Skill::where('is_active',true)->orderBy('name')->get()]); }
 public function update(Request $request, Job $job): RedirectResponse { $this->authorizeCompany($request,$job); abort_if($job->status==='published',403); $data=$this->validated($request); $skills=$data['skills']??[]; unset($data['skills']); $data['slug']=$this->uniqueSlug($data['title'],$job->id); $data['status']='pending'; $data['published_at']=null; $job->update($data); $job->skills()->sync($skills); return redirect()->route('employer.jobs.index')->with('status','Job updated and resubmitted for review.'); }
 public function destroy(Request $request, Job $job): RedirectResponse { $this->authorizeCompany($request,$job); abort_if($job->status==='published',403,'Unpublish the job before deleting it.'); $job->delete(); return back()->with('status','Job deleted.'); }
 private function authorizeCompany(Request $request,Job $job): void { abort_unless($job->company_id===$this->company($request)->id,403); }
 private function validated(Request $request): array { return $request->validate(['title'=>['required','string','max:180'],'category_id'=>['nullable','exists:job_categories,id'],'location'=>['required','string','max:180'],'job_type'=>['required',Rule::in(['Full Time','Part Time','Contract','Freelance','Internship'])],'workplace_type'=>['required',Rule::in(['On-site','Hybrid','Remote'])],'experience_level'=>['nullable','string','max:40'],'salary_min'=>['nullable','numeric','min:0'],'salary_max'=>['nullable','numeric','gte:salary_min'],'salary_currency'=>['required','string','size:3'],'description'=>['required','string','min:30'],'requirements'=>['nullable','string'],'responsibilities'=>['nullable','string'],'expires_at'=>['nullable','date','after:today'],'skills'=>['nullable','array'],'skills.*'=>['integer','exists:skills,id']]); }
 private function uniqueSlug(string $title,?int $ignore=null): string { $base=Str::slug($title); $slug=$base; $i=1; while(Job::where('slug',$slug)->when($ignore,fn($q)=>$q->where('id','!=',$ignore))->exists()) $slug=$base.'-'.(++$i); return $slug; }
}

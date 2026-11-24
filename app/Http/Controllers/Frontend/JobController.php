<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\View\View;
class JobController extends Controller {
 public function index(Request $request): View {
  $filters=$request->validate(['keyword'=>['nullable','string','max:120'],'location'=>['nullable','string','max:120'],'job_type'=>['nullable','in:Full Time,Part Time,Contract,Freelance,Internship'],'workplace_type'=>['nullable','in:On-site,Hybrid,Remote'],'category'=>['nullable','string','max:140'],'experience_level'=>['nullable','string','max:40'],'salary_min'=>['nullable','numeric','min:0'],'sort'=>['nullable','in:latest,salary_high,salary_low']]);
  $jobs=Job::query()->published()->with(['company','category','skills'])
   ->when($request->filled('keyword'),function($q)use($request){$v=trim($request->input('keyword'));$q->where(fn($s)=>$s->where('title','like',"%{$v}%")->orWhere('description','like',"%{$v}%")->orWhereHas('company',fn($c)=>$c->where('name','like',"%{$v}%"))->orWhereHas('skills',fn($sk)=>$sk->where('name','like',"%{$v}%")));})
   ->when($request->filled('location'),fn($q)=>$q->where('location','like','%'.trim($request->input('location')).'%'))
   ->when($request->filled('job_type'),fn($q)=>$q->where('job_type',$request->input('job_type')))
   ->when($request->filled('workplace_type'),fn($q)=>$q->where('workplace_type',$request->input('workplace_type')))
   ->when($request->filled('category'),fn($q)=>$q->whereHas('category',fn($c)=>$c->where('slug',$request->input('category'))))
   ->when($request->filled('experience_level'),fn($q)=>$q->where('experience_level',$request->input('experience_level')))
   ->when($request->filled('salary_min'),fn($q)=>$q->where(function($s)use($request){$s->whereNull('salary_max')->orWhere('salary_max','>=',$request->input('salary_min'));}))
   ->when($request->input('sort')==='salary_high',fn($q)=>$q->orderByDesc('salary_max'))
   ->when($request->input('sort')==='salary_low',fn($q)=>$q->orderBy('salary_min'))
   ->when(!$request->filled('sort')||$request->input('sort')==='latest',fn($q)=>$q->latest('published_at'))
   ->paginate(12)->withQueryString();
  $categories=JobCategory::where('is_active',true)->orderBy('name')->get();
  return view('frontend.jobs.index',compact('jobs','categories','filters'));
 }
 public function show(string $slug): View { $job=Job::query()->published()->with(['company','category','skills'])->where('slug',$slug)->firstOrFail(); $relatedJobs=Job::query()->published()->with(['company','category'])->where('id','!=',$job->id)->where('category_id',$job->category_id)->latest('published_at')->limit(3)->get(); return view('frontend.jobs.show',compact('job','relatedJobs')); }
}

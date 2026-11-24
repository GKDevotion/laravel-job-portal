<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class JobReviewController extends Controller {
 public function index(Request $request): View { $jobs=Job::with(['company','category'])->when($request->filled('status'),fn($q)=>$q->where('status',$request->input('status')),fn($q)=>$q->whereIn('status',['pending','draft','rejected']))->latest()->paginate(15)->withQueryString(); return view('admin.jobs.index',compact('jobs')); }
 public function update(Request $request,Job $job): RedirectResponse { $data=$request->validate(['status'=>['required','in:published,rejected,draft'],'review_note'=>['nullable','string','max:1000']]); $job->status=$data['status']; $job->published_at=$data['status']==='published'?now():null; $job->save(); return back()->with('status',$data['status']==='published'?'Job approved and published.':'Job review status updated.'); }
}

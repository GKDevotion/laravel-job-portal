<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class JobCategoryController extends Controller {
 public function index(): View { $categories=JobCategory::withCount('jobs')->orderBy('sort_order')->orderBy('name')->paginate(20); return view('admin.categories.index',compact('categories')); }
 public function store(Request $request): RedirectResponse { $data=$request->validate(['name'=>['required','string','max:120','unique:job_categories,name'],'description'=>['nullable','string'],'icon'=>['nullable','string','max:100'],'sort_order'=>['nullable','integer','min:0']]); $data['slug']=$this->slug($data['name']); JobCategory::create($data); return back()->with('status','Category created.'); }
 public function update(Request $request,JobCategory $category): RedirectResponse { $data=$request->validate(['name'=>['required','string','max:120',Rule::unique('job_categories','name')->ignore($category->id)],'description'=>['nullable','string'],'icon'=>['nullable','string','max:100'],'sort_order'=>['nullable','integer','min:0'],'is_active'=>['nullable','boolean']]); $data['slug']=$this->slug($data['name'],$category->id); $data['is_active']=$request->boolean('is_active'); $category->update($data); return back()->with('status','Category updated.'); }
 public function destroy(JobCategory $category): RedirectResponse { abort_if($category->jobs()->exists(),409,'This category is assigned to jobs. Deactivate it instead.'); $category->delete(); return back()->with('status','Category deleted.'); }
 private function slug(string $name,?int $ignore=null): string { $base=Str::slug($name); $slug=$base; $i=1; while(JobCategory::where('slug',$slug)->when($ignore,fn($q)=>$q->where('id','!=',$ignore))->exists()) $slug=$base.'-'.(++$i); return $slug; }
}

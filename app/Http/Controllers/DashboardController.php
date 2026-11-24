<?php
namespace App\Http\Controllers;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
class DashboardController extends Controller
{
 public function __invoke(Request $request): View
 {
  $user = $request->user();
  $stats = match ($user->role) {
   User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN => ['Users'=>User::count(), 'Employers'=>User::where('role','employer')->count(), 'Candidates'=>User::where('role','candidate')->count(), 'Job listings'=>Job::count()],
   User::ROLE_EMPLOYER => $this->employerStats($user),
   default => ['Profile completion'=> $user->candidateProfile?->headline ? 'In progress' : 'Not completed', 'Saved jobs'=>0, 'Applications'=>0],
  };
  return view('dashboard.index', compact('user','stats'));
 }

 private function employerStats(User $user): array
 {
  $company = $user->employerProfile?->company;
  return ['Company jobs' => $company ? $company->jobs()->count() : 0, 'Published jobs' => $company ? $company->jobs()->where('status', 'published')->count() : 0];
 }
}

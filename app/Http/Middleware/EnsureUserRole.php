<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class EnsureUserRole
{
 public function handle(Request $request, Closure $next, string ...$roles): Response
 {
  $user = $request->user();
  abort_unless($user && in_array($user->role, $roles, true), 403);
  abort_if($user->status !== 'active', 403, 'Your account is not active. Please contact support.');
  return $next($request);
 }
}

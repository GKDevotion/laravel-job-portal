<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetActiveCurrency
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->filled('currency')) {
            $code = strtoupper($request->string('currency')->toString());

            if (Currency::where('code', $code)->where('status', true)->exists()) {
                session(['currency_code' => $code]);
            }
        }

        return $next($request);
    }
}

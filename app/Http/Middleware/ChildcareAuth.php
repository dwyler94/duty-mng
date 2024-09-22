<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChildcareAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // $token = $request->bearerToken();
        $user = auth('api')->user();
        if ($user) {
            $request->user = $user;
            return $next($request);
        }
        $child = auth('childcare')->user();
        if ($child) {
            $request->user = $child;
            return $next($request);
        }
        abort(401);
    }
}

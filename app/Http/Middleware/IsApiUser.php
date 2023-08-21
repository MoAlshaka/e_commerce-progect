<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsApiUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $bearerToken = $request->bearerToken();

        if ($bearerToken === null) {
            return response()->json('There is no access_token', 401);
        }

        $user = User::where('access_token', $bearerToken)->first();

        if ($user === null) {
            return response()->json('Invalid access_token', 401);
        }

        return $next($request);
    }
}

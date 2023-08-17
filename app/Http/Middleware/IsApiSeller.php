<?php

namespace App\Http\Middleware;

use App\Models\Seller;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsApiSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if ($request->bearerToken()) {
        //     if ($request->bearerToken() !== null) {
        //         $seller=Seller::where('access_token',$request->bearerToken())->first();
        //         if ($seller !== null) {
        //             return $next($request);
        //         } else {
        //             return response()->json('access_token is not correct');
        //         }

        //     } else {
        //         return response()->json('access_token is empty');
        //     }

        // } else {
        //     return response()->json('There is no access_token');
        // }


        $bearerToken = $request->bearerToken();

        if ($bearerToken === null) {
            return response()->json('There is no access_token', 401);
        }

        $seller = Seller::where('access_token', $bearerToken)->first();

        if ($seller === null) {
            return response()->json('Invalid access_token', 401);
        }

        return $next($request);
    }
}

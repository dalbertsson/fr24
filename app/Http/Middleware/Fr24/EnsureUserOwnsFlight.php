<?php

namespace App\Http\Middleware\Fr24;

use Closure;
use App\Models\Fr24\Flight;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserOwnsFlight
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        $flight = $request->route()->parameter('flight');

        if(gettype($flight) != "object")
            $flight = Flight::findOrFail((int) $request->route()->parameter('flight'));

        if(!$flight->isOwnedByCurrentUser())
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to view this resource'
            ], 401);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has an admin role
        if(Auth::check() && Auth::user()->role == 'super_admin'){
            return $next($request);
        }

        // Redirect or abort if user is not admin
        return redirect('/')->with('error', 'You are not authorized to access this section.');
    }
}

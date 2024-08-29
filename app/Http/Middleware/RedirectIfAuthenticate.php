<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class RedirectIfAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {  
          
            $userId = $request->session()->get('user_id');

            \Log::info('RedirectIfAuthenticated Middleware: Checking authentication');
            \Log::info('User ID from session: ' . $userId);
            \Log::info('User Authenticated: ' . Auth::id());

            if ($userId) {
                return redirect()->route('admin.dashboard'); // or any other route
            }
        

        return $next($request);
    }
}

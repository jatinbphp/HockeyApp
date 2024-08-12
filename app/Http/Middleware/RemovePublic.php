<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemovePublic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $url = $request->url();

        // Check if the URL contains 'public' and remove it
        if (strpos($url, 'public') !== false) {
            $newUrl = str_replace('/public', '', $url);
            return redirect($newUrl);
        }

        return $next($request);
    }
}

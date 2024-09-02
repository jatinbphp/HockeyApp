<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Child;

class CheckTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $authorizationHeader = $request->header('Authorization');

       if (!$authorizationHeader) {
           return response()->json(['error' => 'Token is required'], 401);
       }

       $token = substr($authorizationHeader, 7);

       if (!$this->isValidToken($token)) {
           return response()->json(['error' => 'Invalid token'], 401);
       }
       return $next($request);
    }

    protected function isValidToken($token)
    {
        $user = User::where('session_token', $token)->first();
        $child = Child::where('session_token', $token)->first();
        
        return $user !== null || $child !== null;
    }
}

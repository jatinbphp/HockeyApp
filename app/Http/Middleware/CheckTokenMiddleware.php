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

        // Check if Authorization header exists and contains 'Bearer' token
        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            return response()->json(['error' => 'Token is required or incorrectly formatted'], 401);
        }

       $token = substr($authorizationHeader, 7);

        // Validate token existence and authenticity
        if (empty($token) || !$this->isValidToken($token)) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

       return $next($request);
    }

    protected function isValidToken($token): bool
    {
        $userExists = User::where('session_token', $token)->exists();
        $childExists = Child::where('session_token', $token)->exists();

        return $userExists || $childExists;
    }
}

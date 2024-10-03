<?php

namespace App\Http\Middleware;

use App\Models\AuthToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || Auth::guest()) {
            return response()->json(['error' => 'Unauthenticated.'], 403);
        }

        $token = $request->bearerToken();

        $payload = JWTAuth::setToken($token)->getPayload();
        $authToken = $payload->get('token');

        if (!AuthToken::where('token', $authToken)->exists()) {
            return response()->json(['error' => 'Expired token.'], 403);
        }
        return $next($request);
    }
}

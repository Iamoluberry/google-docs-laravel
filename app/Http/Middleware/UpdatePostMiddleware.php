<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdatePostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $post = $request->route('post');

        foreach ($post->users as $user) {
            if($user->is(Auth::user()) || Auth::user()->roles->slug == 'admin') {
                return $next($request);
            }
        }

        return response()->json([
            'error' => 'Unauthorized.'
        ], Response::HTTP_UNAUTHORIZED);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCommentAlterationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $comment = $request->route('comment');

        $commentOwner = User::find($comment->user_id);

            if($commentOwner->is(Auth::user()) || Auth::user()->roles->slug == 'admin') {
                return $next($request);
            }

        return response()->json([
            'error' => 'Unauthorized',
            'message' => "You don't have access to this comment."
        ], Response::HTTP_UNAUTHORIZED);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = Auth::user();

        if ($user && $user->roles && $user->roles->permissions){
            $allPerms = $user->roles->permissions;

            foreach ($allPerms as $perms) {
                if ($perms->slug === $permission) {
                    return $next($request);
                }
            }
        }

        return response(['error'=>"unauthorized"], response::HTTP_UNAUTHORIZED);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Allow the request to continue only when the signed-in user has the required role.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if ($user === null || $user->role !== $role) {
            abort(Response::HTTP_FORBIDDEN, 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}

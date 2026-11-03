<?php

namespace App\Http\Middleware;

use App\Models\SupplementaryCycle;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentCycleIsOpen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (SupplementaryCycle::studentPortalIsOpenNow()) {
            return $next($request);
        }

        if ($request->user()?->isStudent()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('login')->with('cycle_notice', true);
    }
}

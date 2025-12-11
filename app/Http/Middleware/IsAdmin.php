<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle($request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return $next($request);
        }

        abort(403, 'Acceso no autorizado');
    }
}

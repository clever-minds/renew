<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For SaaS Admin panel, check if user has is_super_admin flag or specific role
        if (!auth()->check() || !auth()->user()->is_super_admin) {
            abort(403, 'Unauthorized. Super Admin access only.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBusinessAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super admin has access to all businesses
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Admin must have a business assigned
        if (!$user->business_id) {
            abort(403, 'No business assigned to your account.');
        }

        return $next($request);
    }
}

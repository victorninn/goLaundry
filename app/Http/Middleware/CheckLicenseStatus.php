<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLicenseStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super admin bypasses license check
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has a business
        if (!$user->business_id) {
            return $next($request);
        }

        $business = $user->business;

        // Check license status
        if (!$business->hasValidLicense()) {
            session(['license_expired' => true]);
        } else {
            session(['license_expired' => false]);
        }

        return $next($request);
    }
}
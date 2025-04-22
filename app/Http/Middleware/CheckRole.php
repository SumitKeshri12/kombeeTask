<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $roles = is_array($roles) ? $roles : explode(',', $roles[0]);
        $user = $request->user();
        
        // If no user or user doesn't have any of the required roles
        if (!$user || !$user->hasAnyRole($roles)) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this area.');
        }

        return $next($request);
    }
} 
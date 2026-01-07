<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  The required roles (e.g., 'admin', 'cashier')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in to access this area.');
        }

        $userRole = Auth::user()->role;

        // 2. Check if the authenticated user's role matches any of the allowed roles
        if (!in_array($userRole, $roles)) {
            // Redirect based on their actual role
            if ($userRole === 'admin') {
                return redirect()->route('system')->with('error', 'Access denied.');
            } elseif ($userRole === 'employee') {
                return redirect()->route('inventory.index')->with('error', 'Access denied.');
            } elseif ($userRole === 'cashier') {
                return redirect()->route('cashier.dashboard')->with('error', 'Access denied.');
            }
            
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        // If checks pass, allow the request to proceed
        return $next($request);
    }
}
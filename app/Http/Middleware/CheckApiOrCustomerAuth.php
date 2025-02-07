<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckApiOrCustomerAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated via the 'api' guard
        if (Auth::guard('api')->check()) {
            return $next($request);
        }

        // Check if the user is authenticated via the 'customer' guard
        if (Auth::guard('customer')->check()) {
            return $next($request);
        }

        // If neither guard authenticates the user, deny access
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}

<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if ($user && $user->hasAnyRole($roles)) {
            return $next($request);
        }
        return response()->json(['message' => 'Forbidden'], 403);
    }
}

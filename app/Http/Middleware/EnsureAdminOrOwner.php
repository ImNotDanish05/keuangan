<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdminOrOwner
{
    public function handle(Request $request, Closure $next)
    {
        $u = Auth::user();
        if (!$u || !$u->is_approved || !in_array($u->role, ['owner','admin'])) {
            abort(403, 'Tidak punya izin.');
        }
        return $next($request);
    }
}


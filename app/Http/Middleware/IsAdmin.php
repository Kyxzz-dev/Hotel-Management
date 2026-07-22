<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!in_array(Auth::user()->role, ['head_department', 'gm'])) {
            abort(403, 'Akses khusus Head Department dan GM.');
        }

        return $next($request);
    }
}

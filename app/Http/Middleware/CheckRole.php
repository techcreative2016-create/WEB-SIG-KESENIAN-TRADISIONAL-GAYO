<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        \Illuminate\Support\Facades\Log::info('CheckRole Debug: User Role = ' . optional($request->user())->role . ', Required Role = ' . $role);

        $user = $request->user();

        // Master diizinkan mengakses rute admin
        $isAllowed = $user && (
            $user->role === $role || 
            ($user->role === 'master' && $role === 'admin')
        );

        if (!$isAllowed) {
            abort(403, 'Akses tidak diizinkan. Anda tidak memiliki peran ini.');
        }

        if ($request->user()->status === 'suspended') {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['login' => 'Akun Anda telah ditangguhkan oleh Master Admin.']);
        }

        return $next($request);
    }
}

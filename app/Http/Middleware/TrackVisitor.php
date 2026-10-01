<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VisitorLog;
use Carbon\Carbon;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $date = Carbon::today()->toDateString();

        // Hanya catat jika IP ini belum mengunjungi web pada hari ini
        VisitorLog::firstOrCreate(
            ['ip_address' => $ip, 'visited_date' => $date],
            ['user_agent' => $request->header('User-Agent')]
        );

        return $next($request);
    }
}

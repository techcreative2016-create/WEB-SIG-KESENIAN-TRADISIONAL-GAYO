<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckInappropriateContent
{
    protected $bannedWords = [
        'slot', 'gacor', 'maxwin', 'zeus', 'rtp', 'sbobet', 'judol',
        'porno', 'bokep', 'xxx', 'sex', 'memek', 'kontol', 'ngentot',
        'bajingan', 'bangsat'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            if ($this->containsInappropriateContent($request->all())) {
                $user = auth()->user();
                if ($user) {
                    // 1. Suspend User
                    $user->update(['status' => 'suspended']);
                    
                    // 2. Log Activity
                    DB::table('activity_logs')->insert([
                        'user_id' => $user->id,
                        'activity' => '[CRITICAL] Auto-Suspend: Terdeteksi mengunggah konten terlarang.',
                        'ip_address' => $request->ip(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // 3. Force Logout
                    auth()->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->withErrors([
                        'email' => 'Akun Anda telah DITANGGUHKAN karena mencoba mengunggah konten terlarang.',
                    ]);
                }
            }
        }

        return $next($request);
    }

    protected function containsInappropriateContent(array $data): bool
    {
        foreach ($data as $value) {
            if (is_array($value)) {
                if ($this->containsInappropriateContent($value)) {
                    return true;
                }
            } elseif (is_string($value)) {
                $lowerValue = strtolower($value);
                foreach ($this->bannedWords as $word) {
                    if (preg_match("/\b{$word}\b/i", $lowerValue)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}

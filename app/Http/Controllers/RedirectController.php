<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RedirectController extends Controller
{
    public function show(Request $request, string $code)
    {
        $link = Link::where('short_code', $code)->first();

        if (! $link
            || $link->status !== 'active'
            || ($link->expires_at && $link->expires_at->isPast())) {
            abort(404);
        }

        $agent = (string) $request->userAgent();

        Click::create([
            'link_id' => $link->id,
            'ip_hash' => hash_hmac('sha256', (string) $request->ip(), config('app.key')),
            'device' => $this->device($agent),
            'browser' => $this->browser($agent),
            'referrer' => Str::limit((string) $request->headers->get('referer'), 1000, ''),
            'clicked_at' => now(),
        ]);

        // 302 (temporary): browser is link ko cache nahi karta, to har click gina jata hai
        return redirect()->away($link->original_url, 302);
    }

    private function device(string $agent): string
    {
        if (preg_match('/ipad|tablet/i', $agent)) {
            return 'Tablet';
        }

        return preg_match('/mobile|android|iphone/i', $agent) ? 'Mobile' : 'Desktop';
    }

    private function browser(string $agent): string
    {
        return match (true) {
            str_contains($agent, 'Edg/') => 'Edge',
            str_contains($agent, 'OPR/') => 'Opera',
            str_contains($agent, 'Firefox/') => 'Firefox',
            str_contains($agent, 'Chrome/') => 'Chrome',
            str_contains($agent, 'Safari/') => 'Safari',
            default => 'Other',
        };
    }
}

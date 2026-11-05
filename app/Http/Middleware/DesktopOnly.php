<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DesktopOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isMobile($request)) {
            return response()->view('errors.desktop-only', [], 403);
        }

        return $next($request);
    }

    private function isMobile(Request $request): bool
    {
        // Phone par Chrome/Edge ye hint "?1" bhejte hain (desktop mode mein "?0")
        if ($request->header('Sec-CH-UA-Mobile') === '?1') {
            return true;
        }

        // Baaki browsers ke liye User-Agent se pehchano
        return (bool) preg_match(
            '/Mobi|Android|iPhone|iPad|iPod|Tablet|Silk|Kindle|BlackBerry|Opera Mini|IEMobile|webOS/i',
            (string) $request->userAgent()
        );
    }
}

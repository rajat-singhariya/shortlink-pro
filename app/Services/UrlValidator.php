<?php

namespace App\Services;

class UrlValidator
{
    public function isAllowed(string $url): bool
    {
        $parts = parse_url($url);

        if (! $parts || empty($parts['scheme']) || empty($parts['host'])) {
            return false;
        }

        if (! in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            return false;
        }

        $host = strtolower($parts['host']);

        // Apna hi domain shorten na ho
        $own = strtolower(parse_url(config('app.url'), PHP_URL_HOST) ?? '');
        if ($own !== '' && $host === $own) {
            return false;
        }

        // localhost / local network ke naam block
        if ($host === 'localhost' || str_ends_with($host, '.localhost') || str_ends_with($host, '.local')) {
            return false;
        }

        // IP address ho to sirf public IP allow
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return (bool) filter_var(
                $host,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            );
        }

        // Asli website ke naam mein dot hota hai
        return str_contains($host, '.');
    }
}

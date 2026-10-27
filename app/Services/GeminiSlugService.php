<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiSlugService
{
    /**
     * Gemini se short link ke naam ke suggestions mangta hai.
     * Koi bhi dikkat aaye (key nahi, internet nahi, limit khatam) to khali list deta hai,
     * aur site normal options se chalti rehti hai.
     */
    public function suggest(string $url): array
    {
        $key = config('services.gemini.key');

        if (! $key) {
            return [];
        }

        $parts = parse_url($url);
        $host = $parts['host'] ?? '';

        // Privacy: query (?token=...) aur lambe/ajeeb path pieces Gemini ko nahi bhejte
        $segments = array_filter(
            explode('/', $parts['path'] ?? ''),
            fn ($s) => $s !== '' && strlen($s) <= 40 && ! preg_match('/[0-9a-f]{16,}/i', $s)
        );
        $path = '/' . implode('/', array_slice(array_values($segments), 0, 4));

        $prompt = <<<PROMPT
You create short, friendly URL slugs for a link shortener.

Website: {$host}
Page path: {$path}

Write 6 different slugs that describe what this link is about.
Rules:
- lowercase letters, digits and single hyphens only
- 5 to 20 characters, 1 to 3 words
- easy to read and remember
- no offensive words, no personal data, no login, bank or password words
- do not simply copy the whole domain name
Return only a JSON array of strings, nothing else.
PROMPT;

        try {
            $response = Http::connectTimeout(5)
                ->timeout(10)
                ->withHeaders(['x-goog-api-key' => $key])
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/' . config('services.gemini.model') . ':generateContent',
                    [
                        'contents' => [['parts' => [['text' => $prompt]]]],
                        'generationConfig' => [
                            'temperature' => 0.8,
                            'responseMimeType' => 'application/json',
                        ],
                    ]
                );

            if (! $response->successful()) {
                Log::warning('Gemini request failed', ['status' => $response->status()]);

                return [];
            }

            $text = (string) $response->json('candidates.0.content.parts.0.text');
        } catch (\Throwable $e) {
            Log::warning('Gemini error: ' . $e->getMessage());

            return [];
        }

        // Kabhi kabhi ```json ... ``` ke saath aata hai, use hatao
        $text = trim(preg_replace('/^```(?:json)?|```$/m', '', $text));
        $list = json_decode($text, true);

        return is_array($list) ? array_values(array_filter($list, 'is_string')) : [];
    }
}

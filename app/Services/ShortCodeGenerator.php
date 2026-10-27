<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Str;

class ShortCodeGenerator
{
    private const RESERVED = [
        'admin', 'api', 'login', 'logout', 'shorten',
        'processing', 'result', 'created', 'storage', 'build', 'vendor', 'up', 'images',
    ];

    // Sirf chhote letters + numbers (confusing wale hata diye: i, l, o, 0, 1)
    private const ALPHABET = 'abcdefghjkmnpqrstuvwxyz23456789';

    private const ADJECTIVES = ['blue', 'red', 'swift', 'calm', 'bright', 'lucky', 'brave', 'cool', 'happy', 'wild'];
    private const NOUNS = ['tiger', 'falcon', 'river', 'panda', 'comet', 'maple', 'otter', 'rocket', 'cloud', 'lion'];

    // Filter: ye words short link ke naam mein nahi chalenge (apni zaroorat se list badha sakte ho)
    private const BLOCKED_PARTS = ['fuck', 'shit', 'bitch', 'porn', 'bhosd', 'madarchod', 'behenchod', 'phishing'];
    private const BLOCKED_WORDS = [
        'sex', 'xxx', 'nude', 'rape', 'nazi', 'gand', 'chut', 'lund', 'randi',
        'scam', 'hack', 'crack', 'login', 'signin', 'verify', 'password', 'bank', 'paypal', 'otp', 'wallet',
    ];

    public function __construct(private GeminiSlugService $gemini)
    {
    }

    /** Normal (bina AI) 3 options: random, readable, word-based */
    public function options(string $url): array
    {
        return [
            ['type' => 'random',   'label' => 'Random',     'code' => $this->unique(fn () => $this->random())],
            ['type' => 'readable', 'label' => 'Readable',   'code' => $this->unique(fn () => $this->readable($url))],
            ['type' => 'word',     'label' => 'Word-based', 'code' => $this->unique(fn () => $this->words())],
        ];
    }

    /** AI wale options. Gemini se kuch kaam ka na mile to null deta hai. */
    public function smartOptions(string $url): ?array
    {
        $clean = [];

        foreach ($this->gemini->suggest($url) as $candidate) {
            $slug = $this->cleanSlug((string) $candidate);

            if ($slug !== null && $this->isAvailable($slug) && ! in_array($slug, $clean, true)) {
                $clean[] = $slug;
            }
        }

        if (! $clean) {
            return null;
        }

        $options = [];

        foreach (array_slice($clean, 0, 2) as $slug) {
            $options[] = ['type' => 'ai', 'label' => 'AI suggested', 'code' => $slug];
        }

        $options[] = ['type' => 'random', 'label' => 'Random', 'code' => $this->unique(fn () => $this->random())];

        if (count($options) < 3) {
            $options[] = ['type' => 'readable', 'label' => 'Readable', 'code' => $this->unique(fn () => $this->readable($url))];
        }

        return $options;
    }

    public function isAvailable(string $code): bool
    {
        return ! in_array($code, self::RESERVED, true)
            && ! $this->isBlocked($code)
            && ! Link::where('short_code', $code)->exists();
    }

    private function cleanSlug(string $raw): ?string
    {
        $slug = Str::lower(trim($raw));
        $slug = preg_replace('/[\s_]+/', '-', $slug);
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        $slug = trim(preg_replace('/-+/', '-', $slug), '-');

        if (strlen($slug) < 4 || strlen($slug) > 20) {
            return null;
        }

        if (ctype_digit(str_replace('-', '', $slug))) {
            return null;
        }

        return $slug;
    }

    private function isBlocked(string $slug): bool
    {
        foreach (self::BLOCKED_PARTS as $part) {
            if (str_contains($slug, $part)) {
                return true;
            }
        }

        return (bool) array_intersect(explode('-', $slug), self::BLOCKED_WORDS);
    }

    private function unique(callable $make): string
    {
        $code = $make();

        for ($i = 0; $i < 10; $i++) {
            if ($this->isAvailable($code)) {
                return $code;
            }
            $code = $make();
        }

        return $code . '-' . $this->random(3);
    }

    private function random(int $length = 6): string
    {
        $code = '';
        $max = strlen(self::ALPHABET) - 1;

        for ($i = 0; $i < $length; $i++) {
            $code .= self::ALPHABET[random_int(0, $max)];
        }

        return $code;
    }

    private function readable(string $url): string
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        $host = preg_replace('/^www\./', '', $host);
        $name = substr(Str::slug(explode('.', $host)[0]), 0, 12);

        if ($name === '') {
            $name = 'link';
        }

        return $name . '-' . $this->random(4);
    }

    private function words(): string
    {
        return self::ADJECTIVES[array_rand(self::ADJECTIVES)]
            . '-' . self::NOUNS[array_rand(self::NOUNS)];
    }
}

<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Notifications\Notification;
use Filament\Support\Components\Attributes\Exposed;
use Filament\Support\Components\Attributes\Unexposed;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';

    // Card ko chaudai do, taaki image aur form side by side aa sakein
    protected Width | string | null $maxWidth = Width::FiveExtraLarge;

    /**
     * Teen level ki lock: galat try badhte jate hain to lock lamba hota jata hai.
     * 'attempts' = kitne galat try, 'seconds' = kitne second ke andar.
     */
    private const LOCK_TIERS = [
        ['attempts' => 3,  'seconds' => 60],    // Level 1: 1 minute mein 3 galat try
        ['attempts' => 6,  'seconds' => 900],   // Level 2: 15 minute mein 6 galat try
        ['attempts' => 10, 'seconds' => 3600],  // Level 3: 1 ghante mein 10 galat try
    ];

    // Filament ka apna chhota logo aur "Sign in" heading band, hum apna dikhayenge
    #[Unexposed]
    public function hasLogo(): bool
    {
        return false;
    }

    #[Unexposed]
    public function getHeading(): string | Htmlable | null
    {
        return null;
    }

    #[Exposed]
    public function authenticate(): ?LoginResponse
    {
        // Pehle dekho ki koi level ka lock laga hai ya nahi
        if ($wait = $this->lockedForSeconds()) {
            $this->sendLockedNotification($wait);

            return null;
        }

        try {
            $response = parent::authenticate();
        } catch (ValidationException $exception) {
            // Galat email/password: har level mein ek try joda jata hai
            $this->recordFailure();

            throw $exception;
        }

        if ($response) {
            // Sahi login hua to ginti saaf
            $this->clearFailures();
        }

        return $response;
    }

    private function lockedForSeconds(): int
    {
        $wait = 0;

        foreach (self::LOCK_TIERS as $index => $tier) {
            $key = $this->limiterKey($index);

            if (RateLimiter::tooManyAttempts($key, $tier['attempts'])) {
                $wait = max($wait, RateLimiter::availableIn($key));
            }
        }

        return $wait;
    }

    private function recordFailure(): void
    {
        foreach (self::LOCK_TIERS as $index => $tier) {
            RateLimiter::hit($this->limiterKey($index), $tier['seconds']);
        }

        Log::warning('Admin login failed', ['ip' => request()->ip()]);
    }

    private function clearFailures(): void
    {
        foreach (array_keys(self::LOCK_TIERS) as $index) {
            RateLimiter::clear($this->limiterKey($index));
        }
    }

    // Ginti email + IP ke jodi par rakhi jati hai
    private function limiterKey(int $tier): string
    {
        $email = strtolower(trim((string) ($this->data['email'] ?? '')));

        return 'admin-login:' . $tier . ':' . sha1($email . '|' . request()->ip());
    }

    private function sendLockedNotification(int $seconds): void
    {
        $time = $seconds < 60
            ? $seconds . ' seconds'
            : (int) ceil($seconds / 60) . ' minutes';

        Notification::make()
            ->title('Too many failed attempts')
            ->body("For your security, sign-in is locked. Please try again in {$time}.")
            ->danger()
            ->send();
    }
}

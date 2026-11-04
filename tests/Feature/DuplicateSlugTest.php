<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\LinkDraft;
use App\Services\GeminiSlugService;
use App\Services\ShortCodeGenerator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DuplicateSlugTest extends TestCase
{
    use DatabaseTransactions; // test ke baad sab wapas, aapka asli data nahi mitega

    public function test_second_request_for_the_same_name_is_rejected_by_the_unique_index(): void
    {
        // Race condition banao: validation dono requests ko "naam khali hai" bolta hai
        $this->app->instance(ShortCodeGenerator::class, new class (app(GeminiSlugService::class)) extends ShortCodeGenerator {
            public function isAvailable(string $code): bool
            {
                return true;
            }
        });

        // Pehli request ne ye naam pehle hi le liya
        Link::create([
            'original_url' => 'https://example.com/first',
            'short_code' => 'race-test-name',
        ]);

        // Doosri request wahi naam chunti hai
        $draft = LinkDraft::create([
            'token' => 'race-test-' . uniqid(),
            'original_url' => 'https://example.com/second',
            'options' => [],
            'expires_at' => now()->addMinutes(30),
        ]);

        $response = $this->post(route('shorten.choose', $draft->token), [
            'option' => 'custom',
            'custom' => 'race-test-name',
        ]);

        $response->assertSessionHasErrors('custom');
        $this->assertSame(1, Link::where('short_code', 'race-test-name')->count());
    }
}

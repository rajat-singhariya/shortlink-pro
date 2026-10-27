<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkDraft;
use App\Services\ShortCodeGenerator;
use App\Services\UrlValidator;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortenController extends Controller
{
    public function store(Request $request, UrlValidator $validator, ShortCodeGenerator $generator)
    {
        // "google.com" jaisa link bhi chale: https:// khud jod do
        $raw = trim((string) $request->input('url'));
        if ($raw !== '' && ! preg_match('#^[a-z][a-z0-9+.-]*://#i', $raw)) {
            $raw = 'https://' . $raw;
        }
        $request->merge(['url' => $raw]);

        $data = $request->validate([
            'url' => ['required', 'string', 'max:2048', 'url:http,https'],
        ], [
            'url.required' => 'Please enter a link first.',
            'url.url' => "That doesn't look like a valid link. Please paste the full URL.",
            'url.max' => 'That link is too long (keep it under 2048 characters).',
        ]);

        $url = $data['url'];

        if (! $validator->isAllowed($url)) {
            return back()->withInput()->withErrors([
                'url' => 'This link cannot be shortened. Please enter a public website link.',
            ]);
        }

        $draft = LinkDraft::create([
            'token' => Str::lower(Str::random(32)),
            'original_url' => $url,
            'options' => $generator->options($url),
            'expires_at' => now()->addMinutes(30),
        ]);

        return redirect()->route('shorten.processing', $draft->token);
    }

    public function processing(string $token)
    {
        $draft = $this->findDraft($token);

        return view('loading', [
            'resultUrl' => route('shorten.result', $draft->token),
            'suggestUrl' => route('shorten.suggest', $draft->token),
        ]);
    }

    public function suggest(string $token, ShortCodeGenerator $generator)
    {
        $draft = $this->findDraft($token);

        $alreadyDone = collect($draft->options)->contains(fn($option) => ($option['type'] ?? '') === 'ai');

        if (! $alreadyDone && ($smart = $generator->smartOptions($draft->original_url))) {
            $draft->update(['options' => $smart]);
        }

        return response()->json(['ok' => true]);
    }

    public function result(string $token)
    {
        $draft = $this->findDraft($token);

        return view('result', compact('draft'));
    }

    public function choose(Request $request, string $token, ShortCodeGenerator $generator)
    {
        $draft = $this->findDraft($token);

        $request->validate([
            'option' => ['required', 'in:0,1,2,custom'],
        ]);

        $isCustom = $request->input('option') === 'custom';
        $errorKey = $isCustom ? 'custom' : 'option';

        if ($isCustom) {
            $custom = Str::lower(trim((string) $request->input('custom')));

            $check = validator(['custom' => $custom], [
                'custom' => ['required', 'min:3', 'max:30', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            ], [
                'custom.required' => 'Please type a custom name.',
                'custom.min' => 'The custom name must be at least 3 characters.',
                'custom.max' => 'The custom name can be at most 30 characters.',
                'custom.regex' => 'Use only letters, numbers and single hyphens (no spaces).',
            ]);

            if ($check->fails()) {
                return back()->withInput()->withErrors($check);
            }

            $code = $custom;
        } else {
            $code = $draft->options[(int) $request->input('option')]['code'];
        }

        if (! $generator->isAvailable($code)) {
            return back()->withInput()->withErrors([
                $errorKey => 'That link name is already taken or reserved. Please choose another.',
            ]);
        }

        try {
            $link = Link::create([
                'original_url' => $draft->original_url,
                'short_code' => $code,
                'creator_ip_hash' => hash_hmac('sha256', (string) $request->ip(), config('app.key')),
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return back()->withInput()->withErrors([
                $errorKey => 'That name was just taken. Please choose another.',
            ]);
        }

        $draft->delete();

        return redirect()->route('shorten.created', $link->short_code);
    }

    public function created(string $code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();

        return view('created', compact('link'));
    }

    private function findDraft(string $token): LinkDraft
    {
        return LinkDraft::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();
    }
}

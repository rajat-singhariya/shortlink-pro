@extends('layouts.app')

@section('title', 'Your short link is ready | ShortLink Pro')

@section('content')
{{-- Desktop (lg+) par ek screen, scroll nahi --}}
<div class="mx-auto flex min-h-dvh max-w-2xl flex-col px-4 sm:px-6 lg:h-dvh lg:min-h-0 lg:overflow-hidden" x-data="{ copied: false }">

    <header class="flex shrink-0 items-center justify-between py-3 sm:py-4">
        <a href="/" aria-label="Back to landing page"
           class="inline-flex h-11 items-center gap-2 rounded-full border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-white/10 dark:bg-white/5 dark:text-gray-200 dark:hover:bg-white/10">
            <x-heroicon-o-arrow-left class="h-5 w-5" />
            <span class="hidden sm:inline">Home</span>
        </a>
        <x-theme-toggle />
    </header>

    @php($shortUrl = url('/' . $link->short_code))

    <main class="flex min-h-0 flex-1 flex-col justify-center pb-4 lg:overflow-y-auto">
        <div class="reveal text-center" style="--i:0">
            <img src="{{ asset('images/success.webp') }}" alt="" width="707" height="782"
                 class="mx-auto h-32 w-auto sm:h-36 lg:h-40">
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Your short link is ready</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Copy it and share it anywhere.</p>
        </div>

        <div class="reveal mt-5 rounded-2xl border border-gray-200 bg-white p-5 text-center dark:border-white/10 dark:bg-white/5" style="--i:1">
            <div class="break-all text-xl font-bold text-indigo-600 sm:text-2xl dark:text-indigo-400">{{ $shortUrl }}</div>

            <button type="button" data-url="{{ $shortUrl }}"
                    @click="navigator.clipboard.writeText($el.dataset.url); copied = true; setTimeout(() => copied = false, 2000)"
                    class="mt-4 inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 font-semibold text-white transition hover:bg-indigo-500 active:scale-95">
                <x-heroicon-o-clipboard class="h-5 w-5" x-show="!copied" />
                <x-heroicon-o-check class="h-5 w-5" x-show="copied" x-cloak />
                <span x-text="copied ? 'Copied!' : 'Copy link'"></span>
            </button>
        </div>

        <p class="reveal mt-4 line-clamp-2 break-all text-center text-sm text-gray-500" style="--i:2" title="{{ $link->original_url }}">
            Original: {{ $link->original_url }}
        </p>

        <div class="reveal mt-4 text-center" style="--i:3">
            <a href="/" class="font-semibold text-indigo-600 hover:underline dark:text-indigo-400">Shorten another link</a>
        </div>
    </main>

    <x-footer />
</div>
@endsection

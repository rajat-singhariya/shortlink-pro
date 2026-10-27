@extends('layouts.app')

@section('title', 'Choose your short link | ShortLink Pro')

@section('content')
{{-- Mobile: normal scroll. Desktop (lg+): ek screen mein 2 columns, scroll nahi --}}
<div class="mx-auto flex min-h-dvh max-w-6xl flex-col px-4 sm:px-6 lg:h-dvh lg:min-h-0 lg:overflow-hidden">

    {{-- Top bar: back arrow (top-left) + theme toggle --}}
    <header class="flex shrink-0 items-center justify-between py-3 sm:py-4">
        <a href="/" aria-label="Back to landing page"
           class="inline-flex h-11 items-center gap-2 rounded-full border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-white/10 dark:bg-white/5 dark:text-gray-200 dark:hover:bg-white/10">
            <x-heroicon-o-arrow-left class="h-5 w-5" />
            <span class="hidden sm:inline">Back</span>
        </a>
        <x-theme-toggle />
    </header>

    <main class="flex min-h-0 flex-1 flex-col gap-6 pb-4 lg:grid lg:grid-cols-2 lg:items-center lg:gap-12 lg:overflow-y-auto">

        {{-- Left: illustration, heading, original link --}}
        <section class="text-center lg:text-left">
            <img src="{{ asset('images/success.webp') }}" alt="" width="707" height="782"
                 class="reveal mx-auto h-32 w-auto sm:h-40 lg:mx-0 lg:h-52 xl:h-60" style="--i:0">

            <h1 class="reveal mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl" style="--i:1">Your short links are ready</h1>
            <p class="reveal mt-2 text-gray-600 dark:text-gray-400" style="--i:2">Pick the one you like, or create your own custom name.</p>

            <div class="reveal mt-5 flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm dark:border-white/10 dark:bg-white/5"
                 style="--i:3" title="{{ $draft->original_url }}">
                <x-heroicon-o-link class="h-5 w-5 shrink-0 text-gray-400" />
                <span class="line-clamp-2 break-all text-left text-gray-600 dark:text-gray-400">{{ $draft->original_url }}</span>
            </div>
        </section>

        {{-- Right: options --}}
        <section>
            @error('option')
                <p class="mb-3 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            <div class="space-y-3">
                @foreach ($draft->options as $i => $option)
                    <form method="POST" action="{{ route('shorten.choose', $draft->token) }}"
                          class="reveal flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-4 sm:flex-row sm:items-center sm:justify-between dark:border-white/10 dark:bg-white/5"
                          style="--i:{{ $i + 4 }}">
                        @csrf
                        <input type="hidden" name="option" value="{{ $i }}">
                        <div class="min-w-0">
                            <div class="break-all text-lg font-semibold">{{ url('/' . $option['code']) }}</div>
                            <div class="mt-0.5 flex items-center gap-1 text-sm text-gray-500">
                                @if (($option['type'] ?? '') === 'ai')
                                    <x-heroicon-o-sparkles class="h-4 w-4 text-indigo-500" />
                                @endif
                                {{ $option['label'] }}
                            </div>
                        </div>
                        <button type="submit"
                                class="h-11 shrink-0 rounded-xl bg-indigo-600 px-5 font-semibold text-white transition hover:bg-indigo-500 active:scale-95">
                            Use this link
                        </button>
                    </form>
                @endforeach

                {{-- Custom name --}}
                <form method="POST" action="{{ route('shorten.choose', $draft->token) }}"
                      class="reveal rounded-2xl border border-dashed border-amber-400/70 bg-amber-50 p-4 dark:border-amber-300/30 dark:bg-amber-300/5"
                      style="--i:{{ count($draft->options) + 4 }}">
                    @csrf
                    <input type="hidden" name="option" value="custom">
                    <div class="text-sm font-medium text-amber-700 dark:text-amber-300">Or create your own custom name</div>

                    <div class="mt-2 flex flex-col gap-3 sm:flex-row">
                        <div class="flex h-12 min-w-0 flex-1 items-center overflow-hidden rounded-xl border border-gray-300 bg-white dark:border-white/10 dark:bg-white/5">
                            <span class="shrink-0 pl-4 text-sm text-gray-500">{{ preg_replace('#^https?://#', '', url('/')) }}/</span>
                            <input type="text" name="custom" value="{{ old('custom') }}" maxlength="30"
                                   placeholder="my-link" autocomplete="off"
                                   class="h-full min-w-0 flex-1 bg-transparent px-1 text-base outline-none placeholder-gray-400">
                        </div>
                        <button type="submit"
                                class="h-12 shrink-0 rounded-xl bg-amber-500 px-6 font-semibold text-white transition hover:bg-amber-400 active:scale-95">
                            Use custom name
                        </button>
                    </div>

                    @error('custom')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        </section>
    </main>

    <x-footer />
</div>
@endsection

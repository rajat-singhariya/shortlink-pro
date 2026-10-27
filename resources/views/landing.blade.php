@extends('layouts.app')

@section('content')
{{-- Poora page ek screen mein: koi scroll nahi --}}
<div class="mx-auto flex h-dvh max-w-6xl flex-col overflow-hidden px-4 sm:px-6">

    {{-- Top bar --}}
    <header class="flex shrink-0 items-center justify-between gap-3 py-3 sm:py-4">
        <a href="/" class="flex items-center gap-2 text-base font-bold tracking-tight sm:text-lg">
            <img src="{{ asset('logo.png') }}" alt="" class="h-9 w-9">
            <span>ShortLink <span class="text-indigo-600 dark:text-indigo-400">Pro</span></span>
        </a>
        <div class="flex items-center gap-2">
           {{-- <x-admin-pill /> --}} {{-- V2 mein admin banne par wapas chalu karenge --}}
            <x-theme-toggle />
        </div>
    </header>

    {{-- Hero --}}
    <main class="flex min-h-0 flex-1 flex-col items-center justify-center gap-4 text-center lg:flex-row lg:gap-12 lg:text-left">

        <div class="w-full max-w-xl shrink-0 lg:flex-1">

            {{-- Typewriter line: koi box nahi. Text type hota hai, poora hone par ek saath hat jata hai, fir agla type hota hai --}}
            <div x-data="{
                    items: ['No sign-up needed', 'Instant results', 'Open source'],
                    i: 0,
                    shown: '',
                    async run() {
                        const wait = (ms) => new Promise((r) => setTimeout(r, ms));
                        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                        while (true) {
                            const text = this.items[this.i];
                            if (reduce) {
                                this.shown = text;
                            } else {
                                for (let n = 1; n <= text.length; n++) {
                                    this.shown = text.slice(0, n);
                                    await wait(50);
                                }
                            }
                            await wait(1500);
                            this.shown = '';
                            await wait(400);
                            this.i = (this.i + 1) % this.items.length;
                        }
                    }
                }"
                 x-init="run()"
                 class="flex h-9 items-center justify-center gap-2 text-xl font-semibold text-gray-900 sm:text-2xl lg:justify-start dark:text-white">

                {{-- Icon: text hatne par chhup jata hai (jagah bani rehti hai, layout hilta nahi) --}}
                <span class="inline-flex shrink-0 text-indigo-600 dark:text-indigo-400" :class="shown === '' && 'invisible'">
                    <x-heroicon-o-check-circle class="h-6 w-6" x-show="i === 0" x-cloak />
                    <x-heroicon-o-bolt class="h-6 w-6" x-show="i === 1" x-cloak />
                    <x-heroicon-o-code-bracket class="h-6 w-6" x-show="i === 2" x-cloak />
                </span>

                {{-- Chhupa hua poora text jagah rokta hai, taaki type hote waqt text idhar-udhar na hile --}}
                <span class="inline-grid">
                    <span class="invisible col-start-1 row-start-1 whitespace-nowrap" aria-hidden="true" x-text="items[i] + '|'"></span>
                    <span class="col-start-1 row-start-1 whitespace-nowrap">
                        <span x-text="shown"></span><span class="animate-pulse font-light text-indigo-500">|</span>
                    </span>
                </span>
            </div>

            <h1 class="mt-3 text-3xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:mt-4 xl:text-6xl">
                Shorten any link
                <span class="bg-linear-to-r from-indigo-600 to-cyan-500 bg-clip-text text-transparent dark:from-indigo-400 dark:to-cyan-300">
                    in seconds.
                </span>
            </h1>

            <p class="mt-3 text-base leading-relaxed text-gray-600 sm:text-lg dark:text-gray-400 [@media(max-height:680px)]:hidden">
                Paste your long URL and choose from smart short links, or create a custom name of your own.
            </p>

            <form action="{{ route('shorten.store') }}" method="POST" class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-start">
                @csrf
                {{-- Link jaise jaise lamba hota hai, box bhi bada hota jata hai (full link dikhta hai) --}}
                <textarea name="url" rows="1" autocomplete="off" spellcheck="false" inputmode="url"
                          placeholder="Paste your long link here..."
                          x-data="{ grow(el) { el.style.height = 'auto'; el.style.height = Math.min(el.scrollHeight + 2, 160) + 'px'; } }"
                          x-init="grow($el)"
                          @input="grow($el)"
                          @keydown.enter.prevent="$el.form.requestSubmit()"
                          class="block min-h-12 w-full resize-none overflow-y-auto break-all rounded-xl border border-gray-300 bg-white px-4 py-3 text-left text-base leading-6 text-gray-900 placeholder-gray-400 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 sm:flex-1 dark:border-white/10 dark:bg-white/5 dark:text-gray-100 dark:placeholder-gray-500">{{ old('url') }}</textarea>

                <button type="submit"
                        class="inline-flex h-12 shrink-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 text-base font-semibold text-white shadow-lg shadow-indigo-600/25 transition hover:bg-indigo-500 active:scale-95">
                    Shorten link
                    <x-heroicon-o-arrow-right class="h-5 w-5" />
                </button>
            </form>

            @error('url')
                <p class="mt-2 text-left text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Hero image: bachi hui jagah mein apne aap fit hoti hai, scroll nahi aata --}}
        <div class="relative min-h-0 w-full flex-1 lg:h-full lg:max-w-lg lg:self-stretch">
            <img src="{{ asset('images/hero-link.webp') }}"
                 alt="Illustration of a person shortening a link on a computer"
                 width="648" height="642"
                 class="absolute inset-0 h-full w-full object-contain">
        </div>
    </main>

    <x-footer />
</div>
@endsection

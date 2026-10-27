@extends('layouts.app')

@section('title', 'Page not found | ShortLink Pro')

@section('content')
<div class="mx-auto flex h-dvh max-w-3xl flex-col overflow-hidden px-4 sm:px-6">

    <header class="flex shrink-0 items-center justify-between py-3 sm:py-4">
        <a href="/" class="flex items-center gap-2 text-base font-bold tracking-tight sm:text-lg">
            <img src="{{ asset('logo.png') }}" alt="" class="h-9 w-9">
            <span>ShortLink <span class="text-indigo-600 dark:text-indigo-400">Pro</span></span>
        </a>
        <x-theme-toggle />
    </header>

    <main class="flex min-h-0 flex-1 flex-col items-center justify-center py-4 text-center">
        <img src="{{ asset('images/not-found-404.webp') }}" alt="" width="1000" height="348"
             class="max-h-[40dvh] w-full max-w-lg object-contain">

        <h1 class="mt-6 text-3xl font-extrabold tracking-tight sm:text-4xl">This link doesn't exist</h1>
        <p class="mt-2 max-w-md text-gray-600 dark:text-gray-400">
            The short link may have been mistyped, expired or removed. Check the link and try again.
        </p>

        <a href="/"
           class="mt-6 inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 font-semibold text-white shadow-lg shadow-indigo-600/25 transition hover:bg-indigo-500 active:scale-95">
            <x-heroicon-o-arrow-left class="h-5 w-5" />
            Back to home
        </a>
    </main>

    <x-footer />
</div>
@endsection

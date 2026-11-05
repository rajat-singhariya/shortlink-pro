@extends('layouts.app')

@section('title', 'Desktop only | ShortLink Pro')

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
        <img src="{{ asset('images/blocked.png') }}" alt="" class="max-h-[38dvh] w-full max-w-sm object-contain">

        <h1 class="mt-6 text-3xl font-extrabold tracking-tight sm:text-4xl">Admin is desktop only</h1>
        <p class="mt-2 max-w-md text-gray-600 dark:text-gray-400">
            The admin panel can only be opened on a desktop or laptop computer.
            Please open this page on your computer.
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

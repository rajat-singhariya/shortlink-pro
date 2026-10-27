{{-- href="#" abhi placeholder hai, V3 mein "/admin" karenge --}}
<a href="#" aria-label="Admin login"
   class="group relative inline-flex h-11 w-11 items-center justify-center overflow-hidden rounded-full border border-teal-500/40 bg-teal-50 text-teal-700 transition hover:bg-teal-100 sm:w-40 dark:border-teal-400/30 dark:bg-teal-400/10 dark:text-teal-200 dark:hover:bg-teal-400/20">

    {{-- Mobile: sirf lock icon --}}
    <x-heroicon-o-lock-closed class="h-5 w-5 sm:hidden" />

    {{-- Laptop: "Only for admin" --}}
    <span class="hidden items-center gap-1.5 text-sm font-medium transition duration-300 group-hover:-translate-y-8 group-hover:opacity-0 sm:flex">
        <x-heroicon-o-lock-closed class="h-4 w-4" /> Only for admin
    </span>

    {{-- Laptop: hover par "Hey admin!" --}}
    <span class="absolute hidden translate-y-8 items-center gap-1.5 text-sm font-medium opacity-0 transition duration-300 group-hover:translate-y-0 group-hover:opacity-100 sm:flex">
        <x-heroicon-o-lock-open class="h-4 w-4" /> Hey admin!
    </span>
</a>

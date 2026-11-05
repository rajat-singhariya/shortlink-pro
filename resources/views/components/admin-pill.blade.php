{{-- Admin sirf desktop par: choti screen par ye button dikhta hi nahi --}}
<a href="{{ url('/admin') }}" aria-label="Admin login"
   class="group relative hidden h-11 w-40 items-center justify-center overflow-hidden rounded-full border border-teal-500/40 bg-teal-50 text-teal-700 transition hover:bg-teal-100 lg:inline-flex dark:border-teal-400/30 dark:bg-teal-400/10 dark:text-teal-200 dark:hover:bg-teal-400/20">

    {{-- Normal: "Only for admin" --}}
    <span class="flex items-center gap-1.5 text-sm font-medium transition duration-300 group-hover:-translate-y-8 group-hover:opacity-0">
        <x-heroicon-o-lock-closed class="h-4 w-4" /> Only for admin
    </span>

    {{-- Hover par: "Hey admin!" --}}
    <span class="absolute flex translate-y-8 items-center gap-1.5 text-sm font-medium opacity-0 transition duration-300 group-hover:translate-y-0 group-hover:opacity-100">
        <x-heroicon-o-lock-open class="h-4 w-4" /> Hey admin!
    </span>
</a>

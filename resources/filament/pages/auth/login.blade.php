
<x-filament-panels::page.simple>
    <div class="grid min-h-[75vh] items-center gap-8 lg:grid-cols-2">

        {{-- Left: Admin illustration --}}
        <div class="hidden items-center justify-center lg:flex">
            <img
                src="{{ asset('images/admin-login.png') }}"
                alt="ShortLink Pro Admin"
                class="h-auto w-full max-w-2xl object-contain"
            />
        </div>

        {{-- Right: Original Filament login form --}}
        <div class="mx-auto w-full max-w-md">
            {{ $this->content }}
        </div>

    </div>
</x-filament-panels::page.simple>

<button type="button"
        x-data="{ dark: document.documentElement.classList.contains('dark') }"
        @click="dark = !dark; document.documentElement.classList.toggle('dark', dark); try { localStorage.setItem('theme', dark ? 'dark' : 'light') } catch (e) {}"
        aria-label="Light ya dark mode badlo"
        class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-700 transition hover:bg-gray-100 dark:border-white/10 dark:bg-white/5 dark:text-gray-200 dark:hover:bg-white/10">
    <x-heroicon-o-moon class="h-5 w-5" x-show="!dark" x-cloak />
    <x-heroicon-o-sun class="h-5 w-5" x-show="dark" x-cloak />
</button>

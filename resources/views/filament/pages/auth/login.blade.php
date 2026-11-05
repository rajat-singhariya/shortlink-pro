<x-filament-panels::page.simple>

    {{-- Upar ki patti: landing page par wapas jaane ka button + light/dark toggle --}}
    <div class="sl-login-top">
        <a href="{{ url('/') }}" class="sl-pill">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to home
        </a>

        <button type="button" class="sl-pill sl-pill-icon" aria-label="Switch between light and dark mode"
                x-data="{ dark: document.documentElement.classList.contains('dark') }"
                x-on:click="dark = !dark; document.documentElement.classList.toggle('dark', dark); try { localStorage.setItem('filament:theme:mode', dark ? 'dark' : 'light'); localStorage.setItem('theme', dark ? 'dark' : 'light') } catch (e) {}; window.dispatchEvent(new CustomEvent('theme-changed', { detail: dark ? 'dark' : 'light' }))">
            <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
            </svg>
            <svg x-show="dark" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="20" height="20" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
            </svg>
        </button>
    </div>

    <div class="sl-login">

        <div class="sl-login-art" aria-hidden="true">
            <img src="{{ asset('images/admin-login.png') }}" alt="">
        </div>

        <div class="sl-login-form">
            <div class="sl-login-brand">
                <img src="{{ asset('logo.png') }}" alt="" width="40" height="40">
                <span>ShortLink <span class="sl-accent">Pro</span></span>
            </div>

            <h1 class="sl-login-title">Admin sign in</h1>
            <p class="sl-login-sub">Sign in to manage links, clicks and settings.</p>

            {{ $this->content }}

            <p class="sl-login-note">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="16" height="16" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                Too many failed attempts will temporarily lock sign-in.
            </p>
        </div>

    </div>

    <style>
        .fi-simple-main {
            margin-block: 1.5rem;
            padding-block: 2rem;
        }

        .sl-login-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .sl-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            height: 2.5rem;
            padding-inline: 1rem;
            border-radius: 9999px;
            border: 1px solid rgb(229 231 235);
            background: rgb(255 255 255);
            color: rgb(55 65 81);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }
        .sl-pill:hover { background: rgb(243 244 246); }
        .sl-pill-icon { width: 2.5rem; padding-inline: 0; }

        .dark .sl-pill {
            border-color: rgb(255 255 255 / 0.1);
            background: rgb(255 255 255 / 0.05);
            color: rgb(229 231 235);
        }
        .dark .sl-pill:hover { background: rgb(255 255 255 / 0.1); }

        .sl-login {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr);
            align-items: center;
            gap: 3rem;
        }

        .sl-login-art img {
            display: block;
            width: 100%;
            max-width: 440px;
            height: auto;
            margin-inline: auto;
        }

        .sl-login-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 1.125rem;
            font-weight: 700;
        }

        .sl-login-brand img {
            width: 2.25rem;
            height: 2.25rem;
        }

        .sl-accent { color: rgb(79 70 229); }
        .dark .sl-accent { color: rgb(129 140 248); }

        .sl-login-title {
            margin-top: 1.5rem;
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .sl-login-sub {
            margin-top: 0.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            color: rgb(107 114 128);
        }
        .dark .sl-login-sub { color: rgb(156 163 175); }

        .sl-login-note {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 1.25rem;
            font-size: 0.8rem;
            color: rgb(107 114 128);
        }
        .dark .sl-login-note { color: rgb(156 163 175); }

        @media (max-width: 767px) {
            .sl-login {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .sl-login-art { order: -1; }
            .sl-login-art img { max-width: 200px; }
        }
    </style>
</x-filament-panels::page.simple>

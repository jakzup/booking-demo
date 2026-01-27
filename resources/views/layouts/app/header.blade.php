@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
                    <span class="text-xl font-bold uppercase">{{ __('Booking Manager') }}</span>
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            @auth
                <flux:navbar class="-mb-px max-lg:hidden">
                    <flux:navbar.item icon="calendar" :href="route('reservations.index')" :current="request()->routeIs('reservations.index*')" wire:navigate>
                        {{ __('My Reservations') }}
                    </flux:navbar.item>
                </flux:navbar>
            @endauth

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <x-language-selector />
            </flux:navbar>

            @auth
                <x-desktop-user-menu />
            @endauth
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')">
                    <flux:sidebar.item :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
                        <span class="text-xl font-bold uppercase">{{ __('Booking Manager') }}</span>
                    </flux:sidebar.item>
                    @auth
                        <flux:sidebar.item icon="calendar" :href="route('reservations.index')" :current="request()->routeIs('reservations.index*')" wire:navigate>
                            {{ __('My Reservations') }}
                        </flux:sidebar.item>
                    @endauth
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>

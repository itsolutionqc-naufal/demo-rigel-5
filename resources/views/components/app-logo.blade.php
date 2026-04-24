@props([
    'sidebar' => false,
    'href' => null,
])

@php
    $appLogoHref = $href ?? (auth()->check() ? route(auth()->user()->homeRouteName()) : route('home'));
@endphp

@if($sidebar)
    <a href="{{ $appLogoHref }}" wire:navigate class="flex items-center justify-center py-3 px-4 hover:opacity-80 transition-opacity">
        <img src="{{ asset('images/logo.png') }}" alt="Rigel Agency Logo" class="h-12 w-auto dark:brightness-0 dark:invert" />
    </a>
@else
    <flux:brand href="{{ $appLogoHref }}" wire:navigate class="flex justify-center items-center">
        <x-slot name="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="block dark:invert w-auto h-12 object-contain" />
        </x-slot>
    </flux:brand>
@endif

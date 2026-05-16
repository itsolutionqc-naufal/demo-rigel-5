<?php

use Livewire\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Appearance Settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <div x-data x-init="localStorage.setItem('flux.appearance', 'dark'); $flux.appearance = 'dark'">
            <flux:radio.group variant="segmented" x-model="$flux.appearance">
                <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            </flux:radio.group>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-3">
                Aplikasi ini terkunci pada Mode Gelap (Dark Mode).
            </p>
        </div>
    </x-pages::settings.layout>
</section>

@props([
    'sidebar' => false,
])

@if ($sidebar)
    <flux:sidebar.brand name="Soklenn" {{ $attributes }}>

        <x-slot name="logo">
            <img src="{{ asset('logo-soklenn-bg.png') }}" alt="Soklenn" class="h-14 w-auto">
        </x-slot>

    </flux:sidebar.brand>
@else
    <flux:brand name="Soklenn" {{ $attributes }}>

        <x-slot name="logo">
            <img src="{{ asset('logo-soklenn-bg.png') }}" alt="Soklenn" class="h-14 w-auto">
        </x-slot>

    </flux:brand>
@endif

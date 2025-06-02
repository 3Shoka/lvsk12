@props(['disabled' => false])

<div wire:ignore>
    <select {{ $attributes->merge(['class' => 'w-full rounded-lg border block']) }}>
        {{ $slot }}
    </select>
</div>
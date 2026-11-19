@props([
    'variant' => 'white',
    'hover' => false,
    'padding' => 'p-6',
    'rounded' => 'rounded-panel',
    'as' => 'div',
])

@php
    $variantClass = match($variant) {
        'teal' => 'bg-brand-teal text-brand-cream',
        default => 'border border-brand-line bg-white shadow-card',
    };
@endphp

<{{ $as }} {{ $attributes->merge([
    'class' => "$variantClass $rounded $padding " .
               ($hover ? 'transition duration-200 hover:-translate-y-0.5 hover:shadow-float ' : '')
]) }}>
    {{ $slot }}
</{{ $as }}>

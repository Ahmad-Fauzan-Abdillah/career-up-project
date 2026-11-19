@props([
    'as' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'loading' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 active:translate-y-px disabled:pointer-events-none disabled:opacity-55';
    $sizeClasses = [
        'sm' => 'px-3.5 py-2 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3.5 text-base',
    ][$size] ?? 'px-5 py-2.5 text-sm';
    $variantClasses = [
        'primary' => 'bg-brand-teal text-white shadow-sm hover:bg-brand-teal-dark focus-visible:outline-brand-teal',
        'teal' => 'bg-brand-teal text-white shadow-sm hover:bg-brand-teal-dark focus-visible:outline-brand-teal',
        'secondary' => 'border border-slate-300 bg-white text-brand-teal hover:border-brand-teal hover:bg-brand-teal-soft focus-visible:outline-brand-teal',
        'outline' => 'border border-slate-300 bg-white text-brand-teal hover:border-brand-teal hover:bg-brand-teal-soft focus-visible:outline-brand-teal',
        'cream' => 'bg-brand-cream text-brand-teal hover:bg-white focus-visible:outline-brand-teal',
        'peach' => 'bg-brand-peach text-brand-teal-dark shadow-sm hover:bg-amber-300 focus-visible:outline-brand-teal',
        'ghost' => 'text-slate-700 hover:bg-slate-100 hover:text-brand-teal focus-visible:outline-brand-teal',
        'danger' => 'bg-rose-700 text-white shadow-sm hover:bg-rose-800 focus-visible:outline-rose-700',
        'outline-white' => 'border border-white/40 text-white hover:bg-white/10 focus-visible:outline-brand-peach',
    ][$variant] ?? 'bg-brand-teal text-white hover:bg-brand-teal-dark';
    $classes = "$baseClasses $sizeClasses $variantClasses";
@endphp

@if($as === 'a' || $href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        @disabled($loading || $attributes->has('disabled'))
        @if($loading) aria-busy="true" @endif
        {{ $attributes->merge(['class' => $classes . ($loading ? ' is-loading' : '')]) }}>
        {{ $slot }}
    </button>
@endif

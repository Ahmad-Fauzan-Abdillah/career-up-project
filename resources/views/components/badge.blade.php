@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
    $baseClasses = 'inline-flex items-center gap-1.5 rounded-full border font-semibold transition-colors';

    $sizeClasses = [
        'sm' => 'text-[11px] px-2 py-0.5',
        'md' => 'text-xs px-3 py-1 font-semibold',
        'lg' => 'text-sm px-3.5 py-1.5 font-semibold',
    ][$size] ?? 'text-xs px-3 py-1 font-semibold';

    $variantClasses = [
        'default' => 'bg-slate-100 text-slate-700 border border-slate-200',
        'teal' => 'border-brand-teal bg-brand-teal text-white',
        'teal-light' => 'border-teal-200 bg-brand-teal-soft text-brand-teal',
        'peach' => 'border-brand-peach bg-brand-peach text-brand-teal-dark',
        'peach-subtle' => 'border-orange-200 bg-orange-50 text-orange-900',
        'lowongan' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'fulltime' => 'border-teal-200 bg-teal-50 text-teal-900',
        'magang' => 'border-amber-200 bg-amber-50 text-amber-900',
        'gratis' => 'border-sky-200 bg-sky-50 text-sky-900',
        'premium' => 'border-violet-200 bg-violet-50 text-violet-900',
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-900',
        'danger' => 'border-rose-200 bg-rose-50 text-rose-900',
    ][$variant] ?? 'bg-slate-100 text-slate-700 border border-slate-200';
@endphp

<span {{ $attributes->merge(['class' => "$baseClasses $sizeClasses $variantClasses"]) }}>
    {{ $slot }}
</span>

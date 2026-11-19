@props([
    'variant' => 'light', // light (white text for dark bg) or dark (dark text for light bg)
    'size' => 'md',
])

@php
    $textClasses = $variant === 'light' ? 'text-white' : 'text-[#124143]';
    $circleSize = $size === 'lg' ? 'w-12 h-12' : ($size === 'sm' ? 'w-8 h-8' : 'w-10 h-10');
    $textSize = $size === 'lg' ? 'text-3xl' : ($size === 'sm' ? 'text-xl' : 'text-2xl');
@endphp

<div class="inline-flex items-center gap-3 group">
    <!-- Emblem -->
    <div class="{{ $circleSize }} rounded-full bg-[#F5F3EE] flex items-center justify-center shadow-xs shrink-0 p-1.5 ring-1 ring-black/5">
        <svg viewBox="0 0 100 100" class="w-full h-full" fill="none">
            <!-- Head dot -->
            <circle cx="50" cy="28" r="12" fill="#124143" />
            <!-- Body swoosh -->
            <path d="M35 52 C25 68, 30 82, 50 82 C70 82, 75 65, 72 48" stroke="#124143" stroke-width="12" stroke-linecap="round" fill="none" />
            <!-- Ascending arrow tip -->
            <path d="M64 48 L72 48 L72 56" stroke="#124143" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
    <!-- Brand Wordmark -->
    <span class="font-serif {{ $textSize }} font-bold tracking-tight {{ $textClasses }}">
        CareerUp
    </span>
</div>

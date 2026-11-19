@props(['title', 'description' => null, 'eyebrow' => null, 'level' => 1])

<header class="mb-6 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-end sm:justify-between">
    <div>
        @if($eyebrow)
            <p class="mb-2 text-xs font-bold uppercase tracking-[.14em] text-brand-peach-dark">{{ $eyebrow }}</p>
        @endif
        @if((int) $level === 2)
            <h2 class="font-serif text-3xl font-bold tracking-tight text-brand-teal sm:text-4xl">{{ $title }}</h2>
        @else
            <h1 class="font-serif text-3xl font-bold tracking-tight text-brand-teal sm:text-4xl">{{ $title }}</h1>
        @endif
        @if($description)
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">{{ $description }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex shrink-0 flex-wrap gap-3">{{ $actions }}</div>
    @endif
</header>

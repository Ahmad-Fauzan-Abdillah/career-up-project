@props(['title', 'description' => null, 'eyebrow' => null, 'level' => 1])

<header class="x-page-header flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="min-w-0">
        @if($eyebrow)
            <p class="mb-1 text-xs font-bold uppercase tracking-[.14em] text-brand-peach-dark">{{ $eyebrow }}</p>
        @endif
        <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
        @if((int) $level === 2)
            <h2 class="font-heading text-2xl font-bold tracking-tight text-brand-teal sm:text-3xl">{{ $title }}</h2>
        @else
            <h1 class="font-heading text-2xl font-bold tracking-tight text-brand-teal sm:text-3xl">{{ $title }}</h1>
        @endif
        @if($description)
            <p class="max-w-2xl text-sm leading-6 text-slate-600">{{ $description }}</p>
        @endif
        </div>
    </div>
    @if(isset($actions))
        <div class="flex shrink-0 flex-wrap gap-2">{{ $actions }}</div>
    @endif
</header>

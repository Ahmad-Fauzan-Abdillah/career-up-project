@props(['paginator'])

@if($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $startPage = max(1, $currentPage - 1);
        $endPage = min($lastPage, $startPage + 2);
        $startPage = max(1, $endPage - 2);
    @endphp
    <nav aria-label="Navigasi halaman" class="mt-5 flex flex-col gap-3 border-t border-slate-200 pt-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-600">
            Menampilkan {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} data
        </p>
        <div class="flex items-center gap-1">
            @if($paginator->onFirstPage())
                <span aria-disabled="true" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg border border-slate-200 px-2.5 text-sm text-slate-400">
                    <svg class="h-4 w-4 sm:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" /></svg>
                    <span class="sr-only sm:not-sr-only">Sebelumnya</span>
                </span>
            @else
                <a rel="prev" href="{{ $paginator->previousPageUrl() }}" aria-label="Sebelumnya" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-2.5 text-sm font-semibold text-brand-teal hover:bg-brand-teal-soft">
                    <svg class="h-4 w-4 sm:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" /></svg>
                    <span class="sr-only sm:not-sr-only">Sebelumnya</span>
                </a>
            @endif
            @if($startPage > 1)
                <a href="{{ $paginator->url(1) }}" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg px-2 text-sm font-semibold text-brand-teal hover:bg-brand-teal-soft" aria-label="Halaman 1">1</a>
                @if($startPage > 2)<span class="px-1 text-slate-500" aria-hidden="true">…</span>@endif
            @endif
            @for($page = $startPage; $page <= $endPage; $page++)
                @if($page === $currentPage)
                    <span aria-current="page" aria-label="Halaman {{ $page }}" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg bg-brand-teal px-2 text-sm font-bold text-white">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" aria-label="Halaman {{ $page }}" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg px-2 text-sm font-semibold text-brand-teal hover:bg-brand-teal-soft">{{ $page }}</a>
                @endif
            @endfor
            @if($endPage < $lastPage)
                @if($endPage < $lastPage - 1)<span class="px-1 text-slate-500" aria-hidden="true">…</span>@endif
                <a href="{{ $paginator->url($lastPage) }}" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg px-2 text-sm font-semibold text-brand-teal hover:bg-brand-teal-soft" aria-label="Halaman {{ $lastPage }}">{{ $lastPage }}</a>
            @endif
            @if($paginator->hasMorePages())
                <a rel="next" href="{{ $paginator->nextPageUrl() }}" aria-label="Berikutnya" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-2.5 text-sm font-semibold text-brand-teal hover:bg-brand-teal-soft">
                    <span class="sr-only sm:not-sr-only">Berikutnya</span>
                    <svg class="h-4 w-4 sm:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" /></svg>
                </a>
            @else
                <span aria-disabled="true" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg border border-slate-200 px-2.5 text-sm text-slate-400">
                    <span class="sr-only sm:not-sr-only">Berikutnya</span>
                    <svg class="h-4 w-4 sm:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" /></svg>
                </span>
            @endif
        </div>
    </nav>
@endif

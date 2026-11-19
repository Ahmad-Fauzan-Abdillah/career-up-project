@props(['paginator'])

@if($paginator->hasPages())
    <nav aria-label="Navigasi halaman" class="mt-6 flex flex-col gap-3 border-t border-slate-200 pt-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-600">
            Menampilkan {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} data
        </p>
        <div class="flex items-center gap-2">
            @if($paginator->onFirstPage())
                <span aria-disabled="true" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-400">Sebelumnya</span>
            @else
                <a rel="prev" href="{{ $paginator->previousPageUrl() }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-brand-teal hover:bg-brand-teal-soft">Sebelumnya</a>
            @endif
            <span aria-current="page" class="rounded-lg bg-brand-teal px-3 py-2 text-sm font-bold text-white">{{ $paginator->currentPage() }}</span>
            @if($paginator->hasMorePages())
                <a rel="next" href="{{ $paginator->nextPageUrl() }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-brand-teal hover:bg-brand-teal-soft">Berikutnya</a>
            @else
                <span aria-disabled="true" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-400">Berikutnya</span>
            @endif
        </div>
    </nav>
@endif

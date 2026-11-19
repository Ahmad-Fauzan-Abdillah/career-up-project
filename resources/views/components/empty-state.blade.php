@props([])

<div role="status" {{ $attributes->merge(['class' => 'rounded-panel border border-brand-line bg-white px-6 py-10 text-center text-slate-600 shadow-card']) }}>
    <svg class="mx-auto mb-3 h-12 w-12 text-slate-400" viewBox="0 0 48 48" fill="none" aria-hidden="true">
        <path d="M8 16h32v24H8zM16 16V9h16v7M8 24h32M20 24v4h8v-4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
    <div class="mx-auto max-w-xl text-sm leading-6">{{ $slot }}</div>
    @if(isset($action))
        <div class="mt-5">{{ $action }}</div>
    @endif
</div>

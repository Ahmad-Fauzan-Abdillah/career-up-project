@props(['label'])

<div class="overflow-x-auto rounded-panel border border-brand-line bg-white shadow-card" role="region" aria-label="{{ $label }}" tabindex="0">
    <table {{ $attributes->merge(['class' => 'cu-table']) }}>
        {{ $slot }}
    </table>
</div>

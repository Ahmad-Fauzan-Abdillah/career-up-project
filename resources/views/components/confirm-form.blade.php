@props([
    'action',
    'method' => 'DELETE',
    'label' => 'Hapus',
    'message' => 'Tindakan ini tidak dapat dibatalkan.',
    'variant' => 'danger',
])

<form method="POST" action="{{ $action }}" x-data>
    @csrf
    @if(strtoupper($method) !== 'POST')
        @method($method)
    @endif
    <button type="button" class="rounded-lg px-3 py-2 text-sm font-semibold transition hover:underline {{ $variant === 'danger' ? 'text-rose-800' : 'text-brand-teal' }}" @click="$refs.confirm.showModal()">
        {{ $slot->isEmpty() ? $label : $slot }}
    </button>
    <dialog
        x-ref="confirm"
        @click.self="$event.target.close()"
        @keydown.escape.window="$refs.confirm.close()"
        class="w-[calc(100%-2rem)] max-w-md rounded-panel border border-brand-line bg-white p-0 text-slate-800 shadow-float backdrop:bg-slate-950/50">
        <div class="p-6">
            <h2 class="font-heading text-xl font-bold text-brand-teal">{{ $label }}?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $message }}</p>
            <div class="mt-6 flex justify-end gap-3">
                <x-button type="button" variant="secondary" @click="$refs.confirm.close()">Batal</x-button>
                <x-button type="submit" :variant="$variant">{{ $label }}</x-button>
            </div>
        </div>
    </dialog>
</form>

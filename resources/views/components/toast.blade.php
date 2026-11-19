@php
    $message = session('error') ?? session('success') ?? session('status');
    $isError = session()->has('error');
@endphp

@if($message)
    <div
        x-data="{ visible: true }"
        x-show="visible"
        x-transition.opacity.duration.180ms
        x-init="setTimeout(() => visible = false, 6000)"
        role="{{ $isError ? 'alert' : 'status' }}"
        class="cu-toast fixed right-4 top-4 z-[100] flex max-w-sm items-start gap-3 rounded-xl border px-4 py-3 shadow-float {{ $isError ? 'border-rose-200 bg-rose-50 text-rose-950' : 'border-emerald-200 bg-emerald-50 text-emerald-950' }}">
        <span class="flex-1 text-sm font-medium">{{ $message }}</span>
        <button type="button" @click="visible = false" class="rounded p-1 text-current hover:bg-black/5" aria-label="Tutup pesan">×</button>
    </div>
@endif

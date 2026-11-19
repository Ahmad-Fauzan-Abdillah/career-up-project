@extends('layouts.app')

@section('title', 'Langganan Aktif')

@section('content')
<div class="cu-card mx-auto max-w-2xl p-6 text-center sm:p-10">
    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
        <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
    </span>
    <h1 class="mt-5 font-serif text-2xl font-bold text-brand-teal">Langganan premium aktif</h1>
    <p class="mt-3 text-sm leading-relaxed text-slate-600">
        Anda sudah memiliki akses ke semua pelatihan premium sampai
        <strong>{{ $activeSubscription->valid_until->locale('id')->translatedFormat('d F Y') }}</strong>.
    </p>
    <x-button as="a" :href="route('pelatihan.index')" variant="primary" size="lg" class="mt-7 w-full">
        Jelajahi pelatihan
    </x-button>
</div>
@endsection

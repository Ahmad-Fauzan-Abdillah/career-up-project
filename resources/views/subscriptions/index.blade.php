@extends('layouts.app')

@section('title', 'Berlangganan Pelatihan')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <x-page-header title="Berlangganan pelatihan" description="Pilih paket untuk mendapatkan akses ke pelatihan premium." eyebrow="Langganan">
        <x-slot:actions><x-button as="a" :href="route('pelatihan.show', $training)" variant="secondary">Kembali ke pelatihan</x-button></x-slot:actions>
    </x-page-header>
    <p class="text-sm font-semibold text-slate-800">{{ $training->title }}</p>

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
            {{ $errors->first() }}
        </div>
    @endif

    @php
        $benefits = [
            'Akses semua pelatihan premium',
            'Sertifikat digital eksklusif',
            'Materi terbaru setiap minggu',
            'Prioritas informasi pelatihan',
            'Undangan webinar eksklusif',
            'Tanya jawab dengan mentor',
        ];
    @endphp

    <form method="POST" action="{{ route('subscriptions.store') }}" class="space-y-7">
        @csrf
        <input type="hidden" name="training_id" value="{{ $training->id }}">

        <div class="grid gap-5 md:grid-cols-2">
            @foreach([
                'bulanan' => ['name' => 'PAKET BULANAN', 'price' => 'Rp 49.000', 'period' => '/ bulan'],
                'tahunan' => ['name' => 'PAKET TAHUNAN', 'price' => 'Rp 490.000', 'period' => '/ tahun'],
            ] as $plan => $details)
                <label @class([
                    'relative block cursor-pointer rounded-panel border-2 bg-white p-6 shadow-card transition hover:shadow-float',
                    'border-brand-teal ring-2 ring-brand-teal/10' => old('plan_type', 'tahunan') === $plan,
                    'border-slate-200' => old('plan_type', 'tahunan') !== $plan,
                ])>
                    <input type="radio" name="plan_type" value="{{ $plan }}" @checked(old('plan_type', 'tahunan') === $plan)
                           class="absolute right-5 top-5 h-5 w-5 accent-[#124143]" aria-label="Pilih {{ strtolower($details['name']) }}">
                    @if(old('plan_type', 'tahunan') === $plan)
                        <span class="absolute right-12 top-5 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white" aria-label="Paket terpilih">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.414l-7.2 7.2a1 1 0 01-1.414 0l-3.6-3.6a1 1 0 011.414-1.414l2.893 2.893 6.493-6.493a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                    <span class="block text-xs font-bold tracking-widest text-[#124143]">{{ $details['name'] }}</span>
                    <span class="mt-4 block font-heading text-3xl font-bold text-slate-900">{{ $details['price'] }}</span>
                    <span class="text-sm text-slate-500">{{ $details['period'] }}</span>
                    <span class="mt-5 block space-y-3">
                        @foreach($benefits as $benefit)
                            <span class="flex items-start gap-2 text-sm text-slate-600">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.414l-7.2 7.2a1 1 0 01-1.414 0l-3.6-3.6a1 1 0 011.414-1.414l2.893 2.893 6.493-6.493a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                {{ $benefit }}
                            </span>
                        @endforeach
                    </span>
                </label>
            @endforeach
        </div>

        <section class="cu-card p-5 sm:p-6">
            <h2 class="font-heading text-xl font-bold text-[#124143]">Metode Pembayaran</h2>
            <label class="mt-4 flex cursor-pointer items-center gap-4 rounded-xl border border-[#124143] bg-[#124143]/5 p-4">
                <input type="radio" name="payment_method" value="QRIS" checked disabled class="h-5 w-5 accent-[#124143]">
                <span class="flex h-10 w-16 items-center justify-center rounded bg-white text-lg font-black italic text-[#124143]">QRIS</span>
                <span class="text-sm font-semibold text-slate-700">QRIS</span>
            </label>
        </section>

        <x-button type="submit" variant="peach" size="lg" class="w-full">Lanjutkan Pembayaran</x-button>
    </form>
</div>
@endsection

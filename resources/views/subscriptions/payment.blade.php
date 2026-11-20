@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <x-page-header title="Pembayaran" description="Lakukan pembayaran sesuai nominal, lalu unggah bukti untuk diverifikasi." eyebrow="Langganan">
        <x-slot:actions><x-button as="a" :href="route('subscriptions.index', ['training' => $training->id])" variant="secondary">Kembali ke paket</x-button></x-slot:actions>
    </x-page-header>

    <div class="grid items-start gap-6 lg:grid-cols-2">
        <section class="cu-card p-6">
            <h2 class="font-heading text-xl font-bold text-[#124143]">Ringkasan Pembayaran</h2>
            <dl class="mt-6 space-y-4 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-500">Pelatihan</dt>
                    <dd class="text-right font-semibold text-slate-800">{{ $training->title }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-500">Paket</dt>
                    <dd class="font-semibold text-slate-800">{{ $checkout['plan_type'] === 'bulanan' ? 'Paket Bulanan' : 'Paket Tahunan' }}</dd>
                </div>
                <div class="flex justify-between gap-4 border-t border-slate-100 pt-4">
                    <dt class="text-slate-500">Harga</dt>
                    <dd class="font-semibold text-slate-800">Rp {{ number_format($price, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-500">Kode unik</dt>
                    <dd class="font-semibold text-slate-800">{{ $uniqueCode }}</dd>
                </div>
                <div class="flex justify-between gap-4 border-t border-slate-200 pt-4 text-base">
                    <dt class="font-bold text-slate-900">Total</dt>
                    <dd class="font-bold text-[#124143]">Rp {{ number_format($amount, 0, ',', '.') }}</dd>
                </div>
            </dl>
        </section>

        <section class="cu-card p-6 text-center">
            <h2 class="font-heading text-xl font-bold text-[#124143]">Pembayaran QRIS</h2>
            <div class="mx-auto mt-5 flex max-w-sm flex-col items-center rounded-2xl bg-white p-5">
                <img src="{{ asset($payment['image']) }}" alt="Kode QRIS CareerUp" class="h-56 w-56 object-contain">
                <p class="mt-3 text-sm font-bold tracking-wide text-slate-800">Rp {{ number_format($amount, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-600">{{ $payment['instructions'] }}</p>
            </div>
        </section>
    </div>

    <form method="POST" action="{{ route('subscriptions.pay') }}" enctype="multipart/form-data" class="cu-card space-y-4 p-5" x-data="{ fileName: '' }">
        @csrf
        <label for="payment_proof" class="cu-label">Unggah bukti pembayaran (JPG, PNG, atau PDF; maksimal 2 MB)</label>
        <input id="payment_proof" name="payment_proof" type="file" accept=".jpg,.jpeg,.png,.pdf" required class="cu-field file:mr-3 file:rounded-lg file:border-0 file:bg-brand-teal file:px-3 file:py-2 file:font-semibold file:text-white" @change="fileName = $event.target.files?.[0]?.name || ''" aria-describedby="payment-proof-help">
        <p id="payment-proof-help" class="text-sm text-slate-600" x-text="fileName || 'Pilih bukti pembayaran yang jelas.'"></p>
        @error('payment_proof')<p class="text-sm text-rose-800" role="alert">{{ $message }}</p>@enderror
        <x-button type="submit" variant="primary" size="lg" class="w-full">Kirim untuk verifikasi</x-button>
    </form>
</div>
@endsection

@extends('layouts.subscription-success')

@section('title', match($subscription->status) {
    'aktif' => 'Langganan Aktif',
    'ditolak' => 'Pembayaran Ditolak',
    'kedaluwarsa' => 'Langganan Kedaluwarsa',
    default => 'Menunggu Verifikasi',
})

@section('content')
<div class="rounded-3xl border border-slate-200 bg-white p-6 text-center shadow-lg sm:p-10">
    <span class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-amber-100 text-amber-700">
        <svg class="h-14 w-14" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
            <circle cx="12" cy="12" r="9" stroke-width="2" /><path stroke-linecap="round" stroke-width="2" d="M12 7v5l3 2" />
        </svg>
    </span>
    <h1 class="mt-5 font-serif text-3xl font-bold text-[#124143]">{{ match($subscription->status) {
        'aktif' => 'Langganan Aktif',
        'ditolak' => 'Pembayaran Ditolak',
        'kedaluwarsa' => 'Langganan Kedaluwarsa',
        default => 'Menunggu Verifikasi',
    } }}</h1>
    <p class="mt-2 text-sm text-slate-600">
        @if($subscription->status === 'aktif')
            Pembayaran Anda sudah dikonfirmasi. Paket Premium aktif sampai {{ $subscription->valid_until?->locale('id')->translatedFormat('d F Y') }}.
        @elseif($subscription->status === 'ditolak')
            Pembayaran Anda ditolak. Alasan: {{ $subscription->rejection_reason }}
        @elseif($subscription->status === 'kedaluwarsa')
            Masa aktif langganan Anda telah berakhir. Pilih paket kembali untuk mengakses pelatihan premium.
        @else
            Bukti pembayaran Anda sudah diterima dan sedang menunggu verifikasi admin. Akses Premium aktif setelah pembayaran dikonfirmasi.
        @endif
    </p>

    <section class="mt-8 rounded-2xl bg-[#F5F3EE] p-5 text-left sm:p-6">
        <h2 class="font-serif text-xl font-bold text-[#124143]">Detail Langganan</h2>
        <dl class="mt-5 space-y-4 text-sm">
            <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Pelatihan</dt>
                <dd class="text-right font-semibold text-slate-800">{{ $subscription->training?->title ?? 'Pelatihan Premium' }}</dd>
            </div>
            <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Paket</dt>
                <dd class="font-semibold text-slate-800">{{ $subscription->plan_type === 'bulanan' ? 'Paket Bulanan' : 'Paket Tahunan' }}</dd>
            </div>
            <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Harga</dt>
                <dd class="font-semibold text-slate-800">Rp {{ number_format($subscription->price, 0, ',', '.') }}</dd>
            </div>
            <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Status</dt>
                <dd><x-badge :variant="$subscription->status === 'aktif' ? 'success' : (in_array($subscription->status, ['ditolak', 'kedaluwarsa'], true) ? 'danger' : 'warning')">{{ ucfirst($subscription->status) }}</x-badge></dd>
            </div>
            <div class="flex justify-between gap-4 border-t border-slate-200 pt-4">
                <dt class="text-slate-500">Kode unik</dt>
                <dd class="font-semibold text-slate-800">{{ $subscription->unique_code }}</dd>
            </div>
            @if($subscription->valid_until)
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-500">Berlaku sampai</dt>
                    <dd class="font-semibold text-slate-800">{{ $subscription->valid_until->locale('id')->translatedFormat('d F Y') }}</dd>
                </div>
            @endif
        </dl>
    </section>

    <x-button as="a" :href="route('pelatihan.index')" variant="teal" size="lg" class="mt-6 w-full">
        Kembali ke Pelatihan
    </x-button>
</div>
@endsection

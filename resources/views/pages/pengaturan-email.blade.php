@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <x-page-header title="Verifikasi email" description="Pastikan alamat emailmu dapat menerima pemberitahuan penting." eyebrow="Keamanan akun">
        <x-slot:actions><x-button as="a" :href="route('pengaturan.index')" variant="secondary">Pengaturan</x-button></x-slot:actions>
    </x-page-header>
    <section class="rounded-2xl border border-slate-200 bg-white px-6 py-10 text-center shadow-sm sm:px-10">
        @if($user->email_verified_at)
            <svg class="mx-auto h-14 w-14 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m8 12 2 2 4-4" />
            </svg>
            <h2 class="mt-4 font-heading text-2xl font-bold text-emerald-800">Email Sudah Terverifikasi!</h2>
            <p class="mt-2 text-sm text-slate-600">Email Anda ({{ $user->email }}) telah terverifikasi</p>
        @else
            <svg class="mx-auto h-14 w-14 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
            </svg>
            <h2 class="mt-4 font-heading text-2xl font-bold text-brand-teal">Belum terverifikasi</h2>
            <p class="mt-2 text-sm text-slate-600">Email Anda ({{ $user->email }}) belum terverifikasi.</p>
            <form method="POST" action="{{ route('verification.send') }}" class="mt-5">
                @csrf
                <x-button type="submit" variant="teal">Kirim ulang link verifikasi</x-button>
            </form>
        @endif
    </section>
</div>
@endsection

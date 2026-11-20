@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="space-y-6">
    <x-page-header
        title="Beranda"
        :description="'Selamat datang, '.$user->name.'.'"
        eyebrow="Beranda">
        <x-slot:actions>
            <x-button as="a" :href="route('profil.edit')" variant="primary">Lengkapi profil</x-button>
        </x-slot:actions>
    </x-page-header>

    @unless($user->hasVerifiedEmail())
        <div class="flex flex-col gap-3 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-950 sm:flex-row sm:items-center sm:justify-between">
            <p>Email Anda belum terverifikasi. Verifikasi diperlukan sebelum kamu dapat mengirim lamaran.</p>
            <a href="{{ route('pengaturan.email') }}" class="shrink-0 font-bold underline underline-offset-2">Verifikasi email</a>
        </div>
    @endunless

    <section class="grid grid-cols-3 gap-2 sm:gap-4">
        <a href="{{ route('profil.edit') }}" class="x-card flex min-w-0 items-center justify-between gap-2 p-3 transition-colors hover:border-teal-300 sm:p-4">
            <span class="text-xs font-semibold leading-5 text-brand-teal sm:text-sm">Kelengkapan profil</span>
            <span class="font-heading text-xl font-bold text-brand-teal">{{ $profileCompleteness }}%</span>
        </a>
        <a href="{{ route('riwayat.index') }}" class="x-card flex min-w-0 items-center justify-between gap-2 p-3 transition-colors hover:border-teal-300 sm:p-4">
            <span class="text-xs font-semibold leading-5 text-brand-teal sm:text-sm">Lamaran aktif</span>
            <span class="font-heading text-xl font-bold text-brand-teal">{{ number_format($activeApplicationsCount) }}</span>
        </a>
        <a href="{{ route('notifikasi.index') }}" class="x-card flex min-w-0 items-center justify-between gap-2 p-3 transition-colors hover:border-teal-300 sm:p-4">
            <span class="text-xs font-semibold leading-5 text-brand-teal sm:text-sm">Notifikasi baru</span>
            <span class="font-heading text-xl font-bold text-brand-teal">{{ number_format($unreadNotificationsCount) }}</span>
        </a>
    </section>

    <section>
        <div class="mb-3 flex items-center justify-between gap-3">
            <h2 class="font-heading text-xl font-bold text-brand-teal">Lowongan terbaru</h2>
            <div class="flex items-center gap-2">
                @if($latestJobs->isNotEmpty())
                <button type="button" class="hidden h-10 w-10 items-center justify-center rounded-full border border-brand-line bg-white text-brand-teal hover:bg-brand-teal-soft lg:inline-flex" aria-label="Geser lowongan ke kiri" @click="$refs.latestJobs.scrollBy({ left: -320, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" /></svg>
                </button>
                <button type="button" class="hidden h-10 w-10 items-center justify-center rounded-full border border-brand-line bg-white text-brand-teal hover:bg-brand-teal-soft lg:inline-flex" aria-label="Geser lowongan ke kanan" @click="$refs.latestJobs.scrollBy({ left: 320, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" /></svg>
                </button>
                @endif
                <a href="{{ route('lowongan.index') }}" class="inline-flex min-h-10 items-center text-sm font-semibold text-brand-teal hover:underline">Lihat semua</a>
            </div>
        </div>
        @if($latestJobs->isNotEmpty())
            <div x-ref="latestJobs" role="region" aria-label="Lowongan terbaru" tabindex="0" class="x-scroll-row">
                @foreach($latestJobs as $job)
                    <x-job-card :job="$job" layout="beranda" />
                @endforeach
            </div>
        @else
            <p class="rounded-xl border border-brand-line bg-white px-4 py-3 text-sm text-slate-600">Belum ada lowongan.</p>
        @endif
    </section>

    <section>
        <div class="mb-3 flex items-center justify-between gap-3">
            <h2 class="font-heading text-xl font-bold text-brand-teal">Program magang</h2>
            <div class="flex items-center gap-2">
                @if($internships->isNotEmpty())
                <button type="button" class="hidden h-10 w-10 items-center justify-center rounded-full border border-brand-line bg-white text-brand-teal hover:bg-brand-teal-soft lg:inline-flex" aria-label="Geser program magang ke kiri" @click="$refs.internships.scrollBy({ left: -320, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" /></svg>
                </button>
                <button type="button" class="hidden h-10 w-10 items-center justify-center rounded-full border border-brand-line bg-white text-brand-teal hover:bg-brand-teal-soft lg:inline-flex" aria-label="Geser program magang ke kanan" @click="$refs.internships.scrollBy({ left: 320, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" /></svg>
                </button>
                @endif
                <a href="{{ route('magang.index') }}" class="inline-flex min-h-10 items-center text-sm font-semibold text-brand-teal hover:underline">Lihat semua</a>
            </div>
        </div>
        @if($internships->isNotEmpty())
            <div x-ref="internships" role="region" aria-label="Program magang" tabindex="0" class="x-scroll-row">
                @foreach($internships as $internship)
                    <x-job-card :job="$internship" layout="beranda" />
                @endforeach
            </div>
        @else
            <p class="rounded-xl border border-brand-line bg-white px-4 py-3 text-sm text-slate-600">Belum ada program magang.</p>
        @endif
    </section>

    <section>
        <div class="mb-3 flex items-center justify-between gap-3">
            <h2 class="font-heading text-xl font-bold text-brand-teal">Pelatihan mendatang</h2>
            <div class="flex items-center gap-2">
                @if($popularTrainings->isNotEmpty())
                <button type="button" class="hidden h-10 w-10 items-center justify-center rounded-full border border-brand-line bg-white text-brand-teal hover:bg-brand-teal-soft lg:inline-flex" aria-label="Geser pelatihan ke kiri" @click="$refs.trainings.scrollBy({ left: -320, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" /></svg>
                </button>
                <button type="button" class="hidden h-10 w-10 items-center justify-center rounded-full border border-brand-line bg-white text-brand-teal hover:bg-brand-teal-soft lg:inline-flex" aria-label="Geser pelatihan ke kanan" @click="$refs.trainings.scrollBy({ left: 320, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" /></svg>
                </button>
                @endif
                <a href="{{ route('pelatihan.index') }}" class="inline-flex min-h-10 items-center text-sm font-semibold text-brand-teal hover:underline">Lihat semua</a>
            </div>
        </div>
        @if($popularTrainings->isNotEmpty())
            <div x-ref="trainings" role="region" aria-label="Pelatihan mendatang" tabindex="0" class="x-scroll-row">
                @foreach($popularTrainings as $training)
                    <div class="x-scroll-card"><x-training-card :training="$training" /></div>
                @endforeach
            </div>
        @else
            <p class="rounded-xl border border-brand-line bg-white px-4 py-3 text-sm text-slate-600">Belum ada pelatihan mendatang.</p>
        @endif
    </section>
</div>
@endsection

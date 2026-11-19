@extends('layouts.app')

@section('title', 'Lamar '.$job->title)

@section('content')
<div class="mx-auto max-w-3xl">
    <x-breadcrumb :items="[
        ['label' => $job->type === 'magang' ? 'Magang' : 'Lowongan', 'url' => $job->type === 'magang' ? route('magang.index') : route('lowongan.index')],
        ['label' => $job->title, 'url' => $job->type === 'magang' ? route('magang.show', $job) : route('lowongan.show', $job)],
        ['label' => 'Kirim lamaran'],
    ]" />

    <x-page-header
        title="Kirim lamaran"
        :description="'Lengkapi informasi di bawah ini untuk melamar '.($job->type === 'magang' ? 'magang' : 'pekerjaan').'.'"
        eyebrow="Form lamaran" />

    <ol aria-label="Langkah lamaran" class="mb-6 grid grid-cols-3 gap-2">
        <li aria-current="step" class="rounded-xl border border-brand-teal bg-brand-teal p-3 text-center text-xs font-bold text-white sm:text-sm">1. Data diri</li>
        <li class="rounded-xl border border-teal-200 bg-brand-teal-soft p-3 text-center text-xs font-semibold text-brand-teal sm:text-sm">2. CV</li>
        <li class="rounded-xl border border-slate-200 bg-white p-3 text-center text-xs font-semibold text-slate-600 sm:text-sm">3. Kirim</li>
    </ol>

    <form method="POST" action="{{ route('jobs.apply.store', $job) }}" enctype="multipart/form-data" class="cu-card space-y-5 p-5 sm:p-8">
        @csrf
        <x-input name="full_name" label="Nama lengkap" :value="auth()->user()->name" autocomplete="name" required />
        <div class="grid gap-5 sm:grid-cols-2">
            <x-input name="phone" label="Nomor telepon" type="tel" :value="auth()->user()->phone" autocomplete="tel" required />
            <x-input name="email" label="Email" type="email" :value="auth()->user()->email" autocomplete="email" required />
        </div>
        <x-input name="domicile" label="Lokasi domisili" :value="auth()->user()->location" autocomplete="address-level2" required />

        <div x-data="{ fileName: '' }">
            <label for="cv" class="cu-label">CV (PDF, maksimal 2 MB)</label>
            <label for="cv" class="flex min-h-36 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-6 text-center transition hover:border-brand-teal hover:bg-brand-teal-soft">
                <svg class="mb-3 h-8 w-8 text-brand-teal" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 0L7 9m5-5l5 5M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3" />
                </svg>
                <span class="text-sm font-semibold text-slate-800" x-text="fileName || 'Pilih file CV'">Pilih file CV</span>
                <span class="mt-1 text-xs text-slate-600">Format PDF, ukuran maksimal 2 MB</span>
                <input id="cv" name="cv" type="file" accept=".pdf,application/pdf" required class="sr-only" aria-describedby="cv-help" @change="fileName = $event.target.files?.[0]?.name || ''">
            </label>
            <p id="cv-help" class="mt-2 text-xs text-slate-600">Dokumen disimpan dengan aman dan hanya dapat diakses oleh Anda dan admin.</p>
            @error('cv') <p class="mt-1.5 text-sm text-rose-800" role="alert">{{ $message }}</p> @enderror
        </div>

        @error('job_listing_id') <p class="text-sm text-rose-800" role="alert">{{ $message }}</p> @enderror
        <x-button type="submit" variant="primary" size="lg" class="w-full">Kirim lamaran</x-button>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Status Lamaran')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <x-page-header title="Status lamaran" :description="$application->jobListing->title.' · '.$application->jobListing->company_name" eyebrow="Riwayat pendaftaran">
        <x-slot:actions>
            <x-button as="a" :href="route('riwayat.index')" variant="secondary">Semua lamaran</x-button>
        </x-slot:actions>
    </x-page-header>

    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-start gap-4">
            @if($application->status === 'Ditolak')
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </span>
                <div>
                    <x-badge variant="danger" size="lg">Ditolak</x-badge>
                    <p class="mt-2 text-sm text-slate-600">Pembaruan {{ $application->updated_at->locale('id')->translatedFormat('d F Y') }}</p>
                </div>
            @elseif($application->status === 'Diterima')
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <div>
                    <x-badge variant="success" size="lg">Diterima</x-badge>
                    <p class="mt-2 text-sm text-slate-600">Pembaruan {{ $application->updated_at->locale('id')->translatedFormat('d F Y') }}</p>
                </div>
            @elseif($application->status === 'Dibatalkan')
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-700">×</span>
                <div>
                    <x-badge variant="danger" size="lg">Dibatalkan</x-badge>
                    <p class="mt-2 text-sm text-slate-600">Lamaran ini telah dibatalkan.</p>
                </div>
            @else
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <div>
                    <h2 class="font-heading text-xl font-bold text-[#124143]">Lamaran Terkirim</h2>
                    <p class="mt-2 text-sm text-slate-600">{{ $application->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                </div>
            @endif
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 font-heading text-xl font-bold text-[#124143]">Informasi Lamaran</h2>
        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal Daftar</dt>
                <dd class="mt-1 text-sm font-medium text-slate-800">{{ $application->created_at->locale('id')->translatedFormat('d F Y') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Posisi</dt>
                <dd class="mt-1 text-sm font-medium text-slate-800">{{ $application->jobListing->title }}</dd>
            </div>
            @if($application->jobListing->department)
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Departemen</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800">{{ $application->jobListing->department }}</dd>
                </div>
            @endif
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tipe Pekerjaan</dt>
                <dd class="mt-1 text-sm font-medium text-slate-800">{{ $application->jobListing->type === 'magang' ? 'Magang' : 'Lowongan' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Lokasi</dt>
                <dd class="mt-1 text-sm font-medium text-slate-800">{{ $application->jobListing->location }}</dd>
            </div>
        </dl>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-3 font-heading text-xl font-bold text-[#124143]">Catatan</h2>
        <p class="text-sm leading-relaxed text-slate-600">{{ $application->notes ?: 'Admin belum menambahkan catatan.' }}</p>
    </section>
    <div class="flex flex-wrap gap-3">
        <x-button as="a" :href="route('applications.cv', $application)" variant="secondary">Unduh CV saya</x-button>
        @if($application->status === 'Diproses')
            <x-confirm-form :action="route('riwayat.cancel', $application)" method="PATCH" label="Batalkan lamaran" message="Lamaran yang dibatalkan tidak dapat diproses kembali. Lanjutkan?" />
        @endif
    </div>
</div>
@endsection

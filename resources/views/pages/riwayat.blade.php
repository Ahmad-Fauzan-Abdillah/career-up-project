@extends('layouts.app')

@section('title', 'Riwayat Pendaftaran')

@section('content')
<div class="space-y-6">
    <x-page-header
        title="Riwayat pendaftaran"
        description="Pantau perkembangan lamaran pekerjaan dan magang yang telah kamu kirim."
        eyebrow="Aktivitas karier" />

    <nav class="flex flex-wrap gap-2" aria-label="Filter status lamaran">
        @foreach(['' => 'Semua', 'Diproses' => 'Diproses', 'Diterima' => 'Diterima', 'Ditolak' => 'Ditolak', 'Dibatalkan' => 'Dibatalkan'] as $value => $label)
            <a href="{{ route('riwayat.index', $value === '' ? [] : ['status' => $value]) }}"
               @class([
                    'rounded-full border px-4 py-2 text-sm font-semibold transition',
                    'border-brand-teal bg-brand-teal text-white' => $status === ($value === '' ? null : $value),
                    'border-slate-300 bg-white text-slate-700 hover:border-brand-teal hover:text-brand-teal' => $status !== ($value === '' ? null : $value),
               ])
               @if($status === ($value === '' ? null : $value)) aria-current="page" @endif>
                {{ $label }}
            </a>
        @endforeach
    </nav>

    @if($applications->isNotEmpty())
        <ol class="relative space-y-4 before:absolute before:bottom-5 before:left-[1.7rem] before:top-5 before:w-px before:bg-slate-200">
            @foreach($applications as $application)
                @php
                    $statusVariant = match($application->status) {
                        'Diterima' => 'success',
                        'Diproses' => 'warning',
                        default => 'danger',
                    };
                @endphp
                <li class="relative pl-14">
                    <span class="absolute left-4 top-6 flex h-4 w-4 items-center justify-center rounded-full border-4 border-brand-cream bg-brand-teal" aria-hidden="true"></span>
                    <article class="cu-card p-5 sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex min-w-0 items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-100 bg-brand-cream text-sm font-bold text-brand-teal">
                                    @if($application->jobListing->company_logo)
                                        <img src="{{ asset('storage/'.$application->jobListing->company_logo) }}" alt="Logo {{ $application->jobListing->company_name }}" class="h-full w-full object-contain">
                                    @else
                                        {{ \Illuminate\Support\Str::of($application->jobListing->company_name)->explode(' ')->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('') }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h2 class="truncate font-serif text-lg font-bold text-brand-teal">{{ $application->jobListing->title }}</h2>
                                    <p class="mt-1 text-sm text-slate-600">{{ $application->jobListing->company_name }}</p>
                                    <time class="mt-1 block text-xs text-slate-500" datetime="{{ $application->created_at->toIso8601String() }}">
                                        Dikirim {{ $application->created_at->locale('id')->diffForHumans() }}
                                    </time>
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-4 sm:justify-end sm:border-0 sm:pt-0">
                                <x-badge :variant="$statusVariant" size="lg">{{ $application->status }}</x-badge>
                                <x-button as="a" :href="route('riwayat.show', $application)" variant="secondary" size="sm">Detail</x-button>
                            </div>
                        </div>
                    </article>
                </li>
            @endforeach
        </ol>
    @else
        <x-empty-state>
            <p>Belum ada riwayat pendaftaran. Cari peluang yang sesuai dan kirim lamaran pertamamu.</p>
            <x-slot:action>
                <x-button as="a" :href="route('lowongan.index')" variant="primary">Cari lowongan</x-button>
            </x-slot:action>
        </x-empty-state>
    @endif

    <x-pagination :paginator="$applications" />
</div>
@endsection

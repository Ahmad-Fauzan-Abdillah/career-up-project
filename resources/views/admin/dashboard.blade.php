@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<x-page-header title="Dashboard" description="Ringkasan aktivitas CareerUp berdasarkan data terbaru." eyebrow="CareerUp Admin" />

<section aria-label="Statistik" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    @foreach($statistics as $label => $count)
        <article class="cu-card p-5 sm:p-6">
            <p class="text-sm font-semibold text-slate-600">{{ $label }}</p>
            <p class="mt-3 font-serif text-3xl font-bold text-brand-teal">{{ number_format($count) }}</p>
        </article>
    @endforeach
</section>

<div class="mt-6 grid gap-6 xl:grid-cols-5">
    <section class="cu-card p-5 xl:col-span-3 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl font-bold text-brand-teal">Jumlah pelamar</h2>
                <p class="mt-1 text-sm text-slate-600">Aktivitas selama 30 hari terakhir.</p>
            </div>
        </div>
        @if(!$hasChartData)
            <div class="mt-5">
                <x-empty-state>Belum ada lamaran untuk ditampilkan.</x-empty-state>
            </div>
        @else
            <div class="relative mt-5 h-72">
                <x-skeleton id="applicant-chart-skeleton" class="absolute inset-0 h-full" />
                <canvas id="applicant-chart"
                        data-labels="{{ json_encode($chartLabels) }}"
                        data-values="{{ json_encode($chartValues) }}"
                        aria-label="Grafik jumlah pelamar selama 30 hari terakhir"
                        role="img"></canvas>
            </div>
        @endif
    </section>

    <section class="cu-card p-5 xl:col-span-2 sm:p-6">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="font-serif text-xl font-bold text-brand-teal">Lamaran terbaru</h2>
                <p class="mt-1 text-sm text-slate-600">5 aktivitas terkini.</p>
            </div>
            <x-button as="a" :href="route('admin.applications.index')" variant="ghost" size="sm">Semua pelamar</x-button>
        </div>
        @if($latestApplications->isEmpty())
            <div class="mt-5">
                <x-empty-state>Belum ada lamaran.</x-empty-state>
            </div>
        @else
            <div class="mt-4 divide-y divide-slate-100">
                @foreach($latestApplications as $application)
                    <a href="{{ route('admin.applications.show', $application) }}" class="block rounded-lg py-3 hover:bg-slate-50 focus-visible:outline-brand-teal">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-900">{{ $application->full_name }}</p>
                                <p class="mt-1 truncate text-sm text-slate-600">{{ $application->jobListing?->title }}</p>
                            </div>
                            <x-badge :variant="$application->status === 'Diproses' ? 'warning' : ($application->status === 'Diterima' ? 'success' : 'danger')" size="sm">{{ $application->status }}</x-badge>
                        </div>
                        <time class="mt-2 block text-xs text-slate-500" datetime="{{ $application->created_at->toIso8601String() }}">
                            {{ $application->created_at->locale('id')->diffForHumans() }}
                        </time>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection

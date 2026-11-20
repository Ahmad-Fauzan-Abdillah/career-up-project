@php
    $layout = auth()->guest() ? 'layouts.guest' : 'layouts.app';
    $fallbackUrl = $job->type === 'magang' ? route('magang.index') : route('lowongan.index');
    $breadcrumbItems = [
        ['label' => $job->type === 'magang' ? 'Magang' : 'Lowongan', 'url' => $fallbackUrl],
        ['label' => $job->title],
    ];
    $salaryLabel = match (true) {
        $job->salary_min !== null && $job->salary_max !== null => 'Rp'.number_format($job->salary_min, 0, ',', '.').' – Rp'.number_format($job->salary_max, 0, ',', '.'),
        $job->salary_min !== null => 'Mulai Rp'.number_format($job->salary_min, 0, ',', '.'),
        $job->salary_max !== null => 'Sampai Rp'.number_format($job->salary_max, 0, ',', '.'),
        default => 'Tidak dicantumkan',
    };
@endphp

@extends($layout)

@section('main-class', 'bg-brand-cream p-4 sm:p-8')
@section('content-container-class', 'mx-auto w-full max-w-7xl')
@section('title', $job->title.' - '.$job->company_name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($job->description), 155))
@section('og_title', $job->title.' - '.$job->company_name)
@section('og_description', \Illuminate\Support\Str::limit(strip_tags($job->description), 155))

@section('content')
<div>
    <x-breadcrumb :items="$breadcrumbItems" />
    <div class="grid items-start gap-6 lg:grid-cols-12">
        <article class="cu-card min-w-0 p-5 sm:p-8 lg:col-span-8">
            <header class="flex flex-col gap-5 border-b border-slate-100 pb-6 sm:flex-row sm:items-center">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-slate-100 bg-brand-cream text-lg font-bold text-brand-teal">
                    @if($job->company_logo)
                        <img src="{{ asset('storage/'.$job->company_logo) }}" alt="Logo {{ $job->company_name }}" class="h-full w-full object-contain">
                    @else
                        {{ \Illuminate\Support\Str::of($job->company_name)->explode(' ')->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('') }}
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-slate-700">{{ $job->company_name }}</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <x-badge :variant="$job->type === 'magang' ? 'magang' : 'lowongan'">{{ $job->type === 'magang' ? 'Magang' : 'Lowongan' }}</x-badge>
                        @if($job->work_system)
                            <x-badge variant="teal-light">{{ $job->work_system }}</x-badge>
                        @endif
                        @if($job->isClosed())
                            <x-badge variant="danger">Ditutup</x-badge>
                        @endif
                    </div>
                </div>
            </header>

            <h1 class="mt-6 font-heading text-3xl font-bold leading-tight text-brand-teal sm:text-4xl">{{ $job->title }}</h1>
            <p class="mt-2 text-sm text-slate-500">Diposting {{ $job->created_at->locale('id')->diffForHumans() }}</p>

            <section class="mt-8">
                <h2 class="font-heading text-xl font-bold text-brand-teal">Tentang posisi ini</h2>
                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-700">{{ $job->description }}</p>
            </section>

            @if(filled($job->qualifications))
                <section class="mt-8">
                    <h2 class="font-heading text-xl font-bold text-brand-teal">Kualifikasi</h2>
                    <ul class="mt-3 space-y-3">
                        @foreach(preg_split('/\r\n|\r|\n/', $job->qualifications) as $qualification)
                            @if(trim($qualification) !== '')
                                <li class="flex items-start gap-3 text-sm leading-6 text-slate-700">
                                    <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-brand-peach-dark" aria-hidden="true"></span>
                                    {{ trim($qualification) }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </section>
            @endif
        </article>

        <aside class="lg:sticky lg:top-24 lg:col-span-4">
            <div class="cu-card p-5 sm:p-6">
                <h2 class="font-heading text-xl font-bold text-brand-teal">Ringkasan peluang</h2>
                <dl class="mt-5 space-y-4">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Lokasi</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $job->location }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Departemen</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $job->department ?: 'Tidak dicantumkan' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rentang gaji</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $salaryLabel }}</dd>
                    </div>
                    @if($job->type === 'magang')
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Durasi</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $job->duration ?: 'Tidak dicantumkan' }}</dd>
                        </div>
                    @endif
                    @if($job->deadline)
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Batas lamaran</dt>
                            <dd class="mt-1 text-sm font-semibold {{ $job->isClosed() ? 'text-rose-800' : 'text-slate-800' }}">
                                {{ $job->deadline->locale('id')->translatedFormat('d F Y') }}
                                @if($job->isClosed()) · Ditutup @endif
                            </dd>
                        </div>
                    @endif
                </dl>

                <div class="mt-6 border-t border-slate-100 pt-5">
                    @auth
                        @if($application)
                            <x-button as="a" :href="route('riwayat.show', $application)" variant="secondary" class="w-full">Sudah Dilamar</x-button>
                        @elseif($job->isClosed())
                            <x-button type="button" variant="secondary" class="w-full" disabled>Lowongan ditutup</x-button>
                        @else
                            <x-button as="a" :href="route('jobs.apply', $job)" variant="primary" size="lg" class="w-full">Lamar sekarang</x-button>
                        @endif
                    @else
                        @if($job->isClosed())
                            <x-button type="button" variant="secondary" class="w-full" disabled>Lowongan ditutup</x-button>
                        @else
                            <x-button as="a" :href="route('login')" variant="primary" size="lg" class="w-full">Masuk untuk melamar</x-button>
                        @endif
                    @endauth
                    <p class="mt-3 text-center text-xs leading-5 text-slate-500">Pastikan profil dan CV-mu siap sebelum mengirim lamaran.</p>
                </div>
            </div>
        </aside>
    </div>

    @if($relatedJobs->isNotEmpty())
        <section class="mt-12">
            <x-page-header level="2" title="Peluang serupa" description="Peluang lain yang mungkin sesuai denganmu." />
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($relatedJobs as $relatedJob)
                    <x-job-card :job="$relatedJob" />
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@props([
    'job',
    'layout' => 'landing',
    'detailUrl' => null,
])

@php
    $url = $detailUrl ?? ($job->type === 'magang' ? route('magang.show', $job) : route('lowongan.show', $job));
    $initials = \Illuminate\Support\Str::of($job->company_name)
        ->explode(' ')
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');
    $salaryLabel = match (true) {
        $job->salary_min !== null && $job->salary_max !== null => 'Rp'.number_format($job->salary_min, 0, ',', '.').' – Rp'.number_format($job->salary_max, 0, ',', '.'),
        $job->salary_min !== null => 'Mulai Rp'.number_format($job->salary_min, 0, ',', '.'),
        $job->salary_max !== null => 'Sampai Rp'.number_format($job->salary_max, 0, ',', '.'),
        default => null,
    };
    $daysRemaining = $job->deadline ? today()->diffInDays($job->deadline, false) : null;
@endphp

<a href="{{ $url }}" class="group flex h-full flex-col rounded-panel border border-brand-line bg-white p-5 shadow-card transition duration-200 hover:-translate-y-0.5 hover:border-teal-200 hover:shadow-float focus-visible:outline-brand-teal sm:p-6">
    <div class="flex items-start gap-4">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-100 bg-brand-cream text-sm font-bold text-brand-teal">
            @if($job->company_logo)
                <img src="{{ asset('storage/'.$job->company_logo) }}" alt="Logo {{ $job->company_name }}" class="h-full w-full object-contain">
            @else
                <span aria-label="Inisial {{ $job->company_name }}">{{ $initials }}</span>
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <h3 class="line-clamp-2 font-serif text-lg font-bold leading-snug text-brand-teal group-hover:underline">{{ $job->title }}</h3>
            <p class="mt-1 truncate text-sm font-medium text-slate-700">{{ $job->company_name }}</p>
        </div>
        @if($job->is_featured)
            <x-badge variant="peach-subtle" size="sm">Pilihan</x-badge>
        @endif
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        <x-badge :variant="$job->type === 'magang' ? 'magang' : 'lowongan'" size="sm">
            {{ $job->type === 'magang' ? 'Magang' : 'Lowongan' }}
        </x-badge>
        @if($job->work_system)
            <x-badge variant="teal-light" size="sm">{{ $job->work_system }}</x-badge>
        @endif
        @if($job->isClosed())
            <x-badge variant="danger" size="sm">Ditutup</x-badge>
        @endif
    </div>

    <dl class="mt-5 grid gap-3 text-sm text-slate-600">
        <div class="flex items-center gap-2">
            <svg class="h-4 w-4 shrink-0 text-brand-teal" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-5.2 7-11a7 7 0 10-14 0c0 5.8 7 11 7 11z" />
                <circle cx="12" cy="10" r="2.2" stroke-width="1.8" />
            </svg>
            <dt class="sr-only">Lokasi</dt>
            <dd>{{ $job->location }}</dd>
        </div>
        @if($job->type === 'magang' && $job->duration)
            <div class="flex items-center gap-2">
                <span class="font-semibold text-brand-teal" aria-hidden="true">↗</span>
                <dt class="sr-only">Durasi</dt>
                <dd>{{ $job->duration }}</dd>
            </div>
        @endif
        @if($salaryLabel)
            <div class="flex items-center gap-2">
                <span class="font-semibold text-brand-teal" aria-hidden="true">Rp</span>
                <dt class="sr-only">Rentang gaji</dt>
                <dd>{{ $salaryLabel }}</dd>
            </div>
        @endif
    </dl>

    <div class="mt-auto flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4 mt-5 text-xs">
        @if($job->deadline)
            <div class="text-slate-600">
                <span class="{{ $job->isClosed() ? 'font-semibold text-rose-800' : 'font-semibold text-brand-teal' }}">
                    {{ $job->isClosed() ? 'Ditutup' : 'Ditutup '.$daysRemaining.' hari lagi' }}
                </span>
                <time class="mt-1 block text-slate-500" datetime="{{ $job->created_at->toIso8601String() }}">Diposting {{ $job->created_at->locale('id')->diffForHumans() }}</time>
            </div>
        @else
            <time class="text-slate-500" datetime="{{ $job->created_at->toIso8601String() }}">Diposting {{ $job->created_at->locale('id')->diffForHumans() }}</time>
        @endif
        <span class="font-bold text-brand-teal group-hover:underline">Lihat detail <span aria-hidden="true">→</span></span>
    </div>
</a>

@php
    $thumbnail = $training->thumbnail
        ? (\Illuminate\Support\Str::startsWith($training->thumbnail, ['http://', 'https://', 'images/'])
            ? asset($training->thumbnail)
            : asset('storage/'.$training->thumbnail))
        : null;
    $enrollmentCount = $training->enrollments_count ?? $training->enrollments()->count();
    $mentorInitials = \Illuminate\Support\Str::of($training->mentor_name)->explode(' ')->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('');
@endphp

@extends('layouts.app')

@section('title', $training->title.' - Pelatihan CareerUp')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($training->description), 155))
@section('og_title', $training->title.' - Pelatihan CareerUp')
@section('og_description', \Illuminate\Support\Str::limit(strip_tags($training->description), 155))

@section('content')
<div>
    <x-breadcrumb :items="[
        ['label' => 'Pelatihan', 'url' => route('pelatihan.index')],
        ['label' => $training->title],
    ]" />

    <div class="grid items-start gap-6 lg:grid-cols-12">
        <article class="cu-card min-w-0 overflow-hidden lg:col-span-8">
            @if($thumbnail)
                <img src="{{ $thumbnail }}" alt="{{ $training->title }}" class="aspect-[16/9] max-h-[28rem] w-full object-cover">
            @endif
            <div class="space-y-8 p-5 sm:p-8">
                <header>
                    <div class="mb-3 flex flex-wrap gap-2">
                        <x-badge :variant="$training->category === 'Premium' ? 'premium' : 'gratis'">{{ $training->category }}</x-badge>
                        @if($isFinished)
                            <x-badge variant="default">Selesai</x-badge>
                        @elseif($startAt && $endAt && now()->between($startAt, $endAt))
                            <x-badge variant="success">Berlangsung</x-badge>
                        @else
                            <x-badge variant="teal-light">Segera</x-badge>
                        @endif
                        @if($isFull)
                            <x-badge variant="danger">Kuota penuh</x-badge>
                        @endif
                    </div>
                    <h1 class="font-heading text-3xl font-bold leading-tight text-brand-teal sm:text-4xl">{{ $training->title }}</h1>
                    <p class="mt-3 text-sm text-slate-600">{{ $training->formatted_schedule_date }} @if($training->formatted_schedule_time) · {{ $training->formatted_schedule_time }} @endif</p>
                </header>

                <section>
                    <h2 class="font-heading text-xl font-bold text-brand-teal">Tentang pelatihan</h2>
                    <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-700">{{ $training->description }}</p>
                </section>

                @if(filled($training->syllabus))
                    <section>
                        <h2 class="font-heading text-xl font-bold text-brand-teal">Materi yang dipelajari</h2>
                        <ul class="mt-3 space-y-3">
                            @foreach(preg_split('/\r\n|\r|\n/', $training->syllabus) as $material)
                                @if(trim($material) !== '')
                                    <li class="flex items-start gap-3 text-sm leading-6 text-slate-700">
                                        <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-brand-peach-dark" aria-hidden="true"></span>
                                        {{ trim($material) }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </section>
                @endif

                <section class="rounded-xl bg-brand-cream p-5">
                    <h2 class="font-heading text-lg font-bold text-brand-teal">Mentor</h2>
                    <div class="mt-4 flex items-center gap-3">
                        @if($training->mentor_avatar)
                            <img src="{{ asset('storage/'.$training->mentor_avatar) }}" alt="Foto {{ $training->mentor_name }}" class="h-12 w-12 rounded-full object-cover">
                        @else
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-teal font-bold text-white" aria-label="Inisial {{ $training->mentor_name }}">{{ $mentorInitials }}</span>
                        @endif
                        <div>
                            <p class="font-semibold text-slate-900">{{ $training->mentor_name }}</p>
                            <p class="text-sm text-slate-600">{{ $training->mentor_role }}</p>
                        </div>
                    </div>
                </section>
            </div>
        </article>

        <aside class="lg:sticky lg:top-24 lg:col-span-4">
            <div class="cu-card p-5 sm:p-6">
                <h2 class="font-heading text-xl font-bold text-brand-teal">Informasi kelas</h2>
                <dl class="mt-5 space-y-4">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jadwal</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $training->formatted_schedule_date ?: 'Belum dijadwalkan' }}</dd>
                    </div>
                    @if($training->formatted_schedule_time)
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Waktu</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $training->formatted_schedule_time }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Durasi dan level</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $training->duration }} · {{ $training->level }}</dd>
                    </div>
                    @if($training->quota !== null)
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Peserta</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $enrollmentCount }} / {{ $training->quota }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="mt-6 border-t border-slate-100 pt-5">
                    @if($isFinished)
                        <p class="rounded-xl bg-slate-100 p-4 text-center text-sm font-semibold text-slate-700">Pelatihan sudah selesai.</p>
                    @elseif($isEnrolled && $canJoin)
                        <x-button as="a" :href="route('pelatihan.zoom', $training)" variant="primary" size="lg" class="w-full">Join Zoom</x-button>
                    @elseif($isEnrolled && $startAt)
                        <div class="rounded-xl bg-brand-teal-soft p-4 text-center">
                            <p class="text-sm font-semibold text-brand-teal">Kamu sudah terdaftar</p>
                            <p class="mt-2 text-sm text-slate-700" x-data='trainingCountdown(@js($startAt->toIso8601String()))' x-text="label" aria-live="off">Mulai dalam ...</p>
                        </div>
                    @elseif($isEnrolled)
                        <p class="rounded-xl bg-brand-teal-soft p-4 text-center text-sm text-slate-700">Jadwal pelatihan belum tersedia.</p>
                    @elseif($isFull)
                        <x-button type="button" variant="secondary" class="w-full" disabled>Kuota penuh</x-button>
                    @elseif($training->category === 'Premium' && !$hasActiveSubscription)
                        <x-button as="a" :href="route('subscriptions.index', ['training' => $training->id])" variant="peach" size="lg" class="w-full">Berlangganan</x-button>
                    @else
                        <form method="POST" action="{{ route('pelatihan.join', $training) }}">
                            @csrf
                            <x-button type="submit" variant="primary" size="lg" class="w-full">Daftar</x-button>
                        </form>
                    @endif
                    <p class="mt-3 text-center text-xs leading-5 text-slate-500">
                        @if($isEnrolled)
                            Tautan kelas hanya tersedia bagi peserta pada waktu yang diizinkan.
                        @else
                            Tautan Zoom tersedia setelah kamu terdaftar.
                        @endif
                    </p>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

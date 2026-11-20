@props(['training', 'detailUrl' => null])

@php
    $url = $detailUrl ?? route('pelatihan.show', $training);
    $startAt = $training->startAt();
    $endAt = $training->endAt();
    $isFinished = $endAt && now()->greaterThan($endAt);
    $isOngoing = $startAt && $endAt && now()->between($startAt, $endAt);
    $enrollmentCount = $training->enrollments_count ?? $training->enrollments()->count();
    $isFull = $training->quota !== null && $enrollmentCount >= $training->quota;
    $thumbnail = $training->thumbnail
        ? (\Illuminate\Support\Str::startsWith($training->thumbnail, ['http://', 'https://', 'images/'])
            ? asset($training->thumbnail)
            : asset('storage/'.$training->thumbnail))
        : asset('images/landing-training.jpg');
@endphp

<a href="{{ $url }}" class="group flex h-full flex-col overflow-hidden rounded-panel border border-brand-line bg-white shadow-card transition-[transform,box-shadow] duration-200 hover:-translate-y-0.5 hover:shadow-float focus-visible:outline-brand-teal">
    <div class="relative aspect-[16/9] overflow-hidden bg-brand-teal-soft">
        <img src="{{ $thumbnail }}" alt="{{ $training->title }}" class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-[1.02]">
        <span class="absolute left-3 top-3">
            <x-badge :variant="$training->category === 'Premium' ? 'premium' : 'gratis'" size="sm">{{ $training->category }}</x-badge>
        </span>
    </div>
    <div class="flex flex-1 flex-col p-4 sm:p-5">
        <div class="mb-3 flex flex-wrap gap-2">
            @if($isFinished)
                <x-badge variant="default" size="sm">Selesai</x-badge>
            @elseif($isOngoing)
                <x-badge variant="success" size="sm">Berlangsung</x-badge>
            @else
                <x-badge variant="teal-light" size="sm">Segera</x-badge>
            @endif
            @if($isFull)
                <x-badge variant="danger" size="sm">Kuota penuh</x-badge>
            @endif
        </div>
        <h3 class="font-heading text-lg font-bold leading-snug text-brand-teal group-hover:underline">{{ $training->title }}</h3>
        <p class="mt-2 text-sm text-slate-600">
            {{ $training->formatted_schedule_date ?: 'Jadwal belum tersedia' }}
            @if($training->formatted_schedule_time)
                <span class="block">{{ $training->formatted_schedule_time }}</span>
            @endif
        </p>
        @if($startAt && now()->lessThan($startAt))
            <p class="mt-2 text-xs font-semibold text-brand-teal" x-data='trainingCountdown(@js($startAt->toIso8601String()))' x-text="label" aria-live="off"></p>
        @endif
        @if($training->mentor_name)
            <p class="mt-auto border-t border-slate-100 pt-4 text-xs text-slate-600">
                Mentor: <span class="font-semibold text-slate-800">{{ $training->mentor_name }}</span>
            </p>
        @else
            <span class="mt-auto block pt-4 text-sm font-bold text-brand-teal">Lihat detail</span>
        @endif
    </div>
</a>

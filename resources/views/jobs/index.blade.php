@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="space-y-6">
    <x-page-header
        :title="$title"
        :description="$type === 'magang' ? 'Temukan kesempatan untuk belajar dan membangun pengalaman profesional.' : 'Temukan pekerjaan yang sesuai dengan minat, keterampilan, dan tujuan kariermu.'"
        :eyebrow="$type === 'magang' ? 'Belajar dan berkarya' : 'Langkah karier berikutnya'" />

    <details open class="cu-card group">
        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 p-4 font-semibold text-brand-teal md:hidden">
            Filter pencarian
            <span class="transition group-open:rotate-180" aria-hidden="true">⌄</span>
        </summary>
        <form method="GET" class="grid gap-3 p-4 pt-0 sm:grid-cols-2 md:grid-cols-4 md:pt-4">
            <x-input name="q" label="Jabatan atau perusahaan" :value="$keyword" placeholder="Cari kata kunci" />
            <x-input name="location" label="Lokasi" :value="$location" placeholder="Kota atau wilayah" />
            <x-input name="work_system" label="Sistem kerja" :value="$workSystem" placeholder="Remote, hybrid, ..." />
            <div class="flex flex-col">
                <x-select
                    name="sort"
                    label="Urutkan"
                    :selected="$sort"
                    :options="['latest' => 'Terbaru', 'deadline' => 'Deadline terdekat']" />
                <x-button type="submit" variant="primary" class="mt-3 w-full">Terapkan filter</x-button>
            </div>
        </form>
    </details>

    @if($jobs->isNotEmpty())
        <div class="{{ $type === 'magang' ? 'grid gap-4 md:grid-cols-2 xl:grid-cols-3' : 'space-y-3' }}">
            @foreach($jobs as $job)
                <x-job-card :job="$job" :layout="$type === 'magang' ? 'landing' : 'listing'" />
            @endforeach
        </div>
    @else
        <x-empty-state>
            @if($type === 'magang')
                Belum ada program magang yang sesuai. Pantau terus ya!
            @else
                Belum ada lowongan yang sesuai. Pantau terus ya!
            @endif
        </x-empty-state>
    @endif

    <x-pagination :paginator="$jobs" />
</div>
@endsection

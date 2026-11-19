@extends('layouts.admin')

@section('title', 'Detail Pelamar')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <x-page-header
        :title="$application->full_name"
        :description="$application->jobListing?->title.' · '.$application->jobListing?->company_name"
        eyebrow="Detail pelamar">
        <x-slot:actions>
            <x-button as="a" :href="route('admin.applications.index')" variant="secondary">Kembali ke pelamar</x-button>
            <x-button as="a" :href="route('admin.applications.cv', $application)" variant="primary">Unduh CV</x-button>
        </x-slot:actions>
    </x-page-header>

    <section class="grid gap-5 md:grid-cols-2">
        <article class="cu-card p-5 sm:p-6">
            <h2 class="font-serif text-xl font-bold text-brand-teal">Informasi pelamar</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</dt><dd class="mt-1 text-slate-800">{{ $application->email }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Telepon</dt><dd class="mt-1 text-slate-800">{{ $application->phone }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Domisili</dt><dd class="mt-1 text-slate-800">{{ $application->domicile }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Dikirim</dt><dd class="mt-1 text-slate-800">{{ $application->created_at->locale('id')->translatedFormat('d F Y, H:i') }}</dd></div>
            </dl>
        </article>
        <article class="cu-card p-5 sm:p-6">
            <h2 class="font-serif text-xl font-bold text-brand-teal">Status lamaran</h2>
            <div class="mt-3"><x-badge :variant="match($application->status) { 'Diterima' => 'success', 'Diproses' => 'warning', default => 'danger' }" size="lg">{{ $application->status }}</x-badge></div>
            @if($application->notes)
                <p class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $application->notes }}</p>
            @endif
        </article>
    </section>

    <section class="cu-card p-5 sm:p-6">
        <h2 class="font-serif text-xl font-bold text-brand-teal">Profil dan keterampilan</h2>
        <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $application->user->bio ?: 'Belum ada ringkasan profil.' }}</p>
        <div class="mt-4 flex flex-wrap gap-2">
            @forelse($application->user->skills as $skill)
                <x-badge variant="teal-light">{{ $skill->skill_name }}</x-badge>
            @empty
                <p class="text-sm text-slate-600">Belum ada skill.</p>
            @endforelse
        </div>
    </section>

    <section class="grid gap-5 md:grid-cols-2">
        <article class="cu-card p-5 sm:p-6">
            <h2 class="font-serif text-xl font-bold text-brand-teal">Pendidikan</h2>
            <div class="mt-4 space-y-4">
                @forelse($application->user->educations as $education)
                    <div class="border-l-2 border-brand-teal/20 pl-4">
                        <p class="font-semibold text-slate-900">{{ $education->institution }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $education->major }} · {{ $education->start_year }}–{{ $education->end_year ?: 'Sekarang' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-600">Belum ada data pendidikan.</p>
                @endforelse
            </div>
        </article>
        <article class="cu-card p-5 sm:p-6">
            <h2 class="font-serif text-xl font-bold text-brand-teal">Pengalaman</h2>
            <div class="mt-4 space-y-4">
                @forelse($application->user->experiences as $experience)
                    <div class="border-l-2 border-brand-teal/20 pl-4">
                        <p class="font-semibold text-slate-900">{{ $experience->position }} · {{ $experience->company }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $experience->period }}</p>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $experience->description }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-600">Belum ada pengalaman.</p>
                @endforelse
            </div>
        </article>
    </section>

    <form method="POST" action="{{ route('admin.applications.update', $application) }}" class="cu-card space-y-4 p-5 sm:p-6">
        @csrf
        @method('PATCH')
        <h2 class="font-serif text-xl font-bold text-brand-teal">Perbarui status lamaran</h2>
        <x-select name="status" label="Status" :selected="$application->status" :options="['Diproses' => 'Diproses', 'Diterima' => 'Diterima', 'Ditolak' => 'Ditolak', 'Dibatalkan' => 'Dibatalkan']" />
        <x-textarea name="notes" label="Catatan admin" :value="$application->notes" rows="4" />
        <x-button type="submit" variant="primary">Simpan perubahan</x-button>
    </form>
</div>
@endsection

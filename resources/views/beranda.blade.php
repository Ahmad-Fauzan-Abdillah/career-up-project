@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="space-y-10">
    <x-page-header
        :title="'Selamat datang, '.$user->name"
        description="Lanjutkan langkahmu menuju peluang karier berikutnya."
        eyebrow="Beranda">
        <x-slot:actions>
            <x-button as="a" :href="route('profil.edit')" variant="primary">Lengkapi profil</x-button>
        </x-slot:actions>
    </x-page-header>

    @unless($user->hasVerifiedEmail())
        <div class="flex flex-col gap-3 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-950 sm:flex-row sm:items-center sm:justify-between">
            <p>Email Anda belum terverifikasi. Verifikasi diperlukan sebelum kamu dapat mengirim lamaran.</p>
            <a href="{{ route('pengaturan.email') }}" class="shrink-0 font-bold underline underline-offset-2">Verifikasi email</a>
        </div>
    @endunless

    <section class="grid gap-4 sm:grid-cols-2">
        <a href="{{ route('profil.edit') }}" class="cu-card block p-5 transition hover:border-teal-300 sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-bold text-brand-teal">Kelengkapan profil</p>
                    <p class="mt-1 text-sm text-slate-600">
                        @if($profileCompleteness === 100)
                            Profilmu sudah lengkap.
                        @else
                            Tambahkan informasi agar profil siap digunakan.
                        @endif
                    </p>
                </div>
                <span class="font-serif text-2xl font-bold text-brand-teal">{{ $profileCompleteness }}%</span>
            </div>
            <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100" role="progressbar" aria-label="Kelengkapan profil" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $profileCompleteness }}">
                <span class="block h-full rounded-full bg-brand-teal transition-all" style="width: {{ $profileCompleteness }}%"></span>
            </div>
        </a>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('riwayat.index') }}" class="cu-card p-5 transition hover:border-teal-300">
                <p class="text-sm text-slate-600">Lamaran diproses</p>
                <p class="mt-2 font-serif text-3xl font-bold text-brand-teal">{{ number_format($activeApplicationsCount) }}</p>
                <span class="mt-2 block text-xs font-semibold text-brand-teal">Lihat riwayat <span aria-hidden="true">→</span></span>
            </a>
            <a href="{{ route('notifikasi.index') }}" class="cu-card p-5 transition hover:border-teal-300">
                <p class="text-sm text-slate-600">Notifikasi baru</p>
                <p class="mt-2 font-serif text-3xl font-bold text-brand-teal">{{ number_format($unreadNotificationsCount) }}</p>
                <span class="mt-2 block text-xs font-semibold text-brand-teal">Lihat notifikasi <span aria-hidden="true">→</span></span>
            </a>
        </div>
    </section>

    @unless($hasCareerActivity)
        <section class="rounded-panel border border-brand-line bg-white p-6 sm:p-8">
            <p class="text-xs font-bold uppercase tracking-[.14em] text-brand-peach-dark">Mulai di sini</p>
            <h2 class="mt-2 font-serif text-2xl font-bold text-brand-teal">Ambil langkah pertamamu</h2>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Lengkapi profil, temukan peluang yang cocok, lalu pantau prosesnya di satu tempat.</p>
            <div class="mt-5 flex flex-wrap gap-3">
                <x-button as="a" :href="route('lowongan.index')" variant="primary">Cari lowongan</x-button>
                <x-button as="a" :href="route('pelatihan.index')" variant="secondary">Jelajahi pelatihan</x-button>
            </div>
        </section>
    @endunless

    <section>
        <x-page-header level="2" title="Lowongan terbaru" description="Peluang kerja yang baru diterbitkan.">
            <x-slot:actions><x-button as="a" :href="route('lowongan.index')" variant="ghost" size="sm">Lihat semua</x-button></x-slot:actions>
        </x-page-header>
        @if($latestJobs->isNotEmpty())
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($latestJobs as $job)
                    <x-job-card :job="$job" layout="beranda" />
                @endforeach
            </div>
        @else
            <x-empty-state>Belum ada lowongan. Pantau terus ya!</x-empty-state>
        @endif
    </section>

    <section>
        <x-page-header level="2" title="Program magang" description="Mulai membangun pengalaman dan mengembangkan keterampilan.">
            <x-slot:actions><x-button as="a" :href="route('magang.index')" variant="ghost" size="sm">Lihat semua</x-button></x-slot:actions>
        </x-page-header>
        @if($internships->isNotEmpty())
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($internships as $internship)
                    <x-job-card :job="$internship" layout="beranda" />
                @endforeach
            </div>
        @else
            <x-empty-state>Belum ada program magang. Pantau terus ya!</x-empty-state>
        @endif
    </section>

    <section>
        <x-page-header level="2" title="Pelatihan mendatang" description="Kelas terbit dengan jadwal yang akan datang.">
            <x-slot:actions><x-button as="a" :href="route('pelatihan.index')" variant="ghost" size="sm">Lihat semua</x-button></x-slot:actions>
        </x-page-header>
        @if($popularTrainings->isNotEmpty())
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($popularTrainings as $training)
                    <x-training-card :training="$training" />
                @endforeach
            </div>
        @else
            <x-empty-state>Belum ada pelatihan mendatang.</x-empty-state>
        @endif
    </section>
</div>
@endsection

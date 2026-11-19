@extends('layouts.guest')

@section('title', 'CareerUp - Temukan Peluang Karier')

@section('content')
<section class="relative overflow-hidden bg-brand-teal text-brand-cream">
    <div class="absolute -right-36 -top-32 h-96 w-96 rounded-full bg-brand-peach/15 blur-3xl" aria-hidden="true"></div>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-12 lg:px-8 lg:py-24">
        <div class="relative z-10 lg:col-span-7">
            <p class="mb-4 text-xs font-bold uppercase tracking-[.18em] text-brand-peach">Karier dimulai dari langkah berikutnya</p>
            <h1 class="max-w-3xl font-serif text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl">Temukan peluang yang membawamu lebih maju.</h1>
            <p class="mt-5 max-w-2xl text-base leading-7 text-slate-200 sm:text-lg">
                Jelajahi lowongan, program magang, dan pelatihan yang membantumu bertumbuh dengan percaya diri.
            </p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <x-button as="a" :href="route('register')" variant="peach" size="lg">Mulai perjalananmu</x-button>
                <x-button as="a" :href="route('lowongan.index')" variant="outline-white" size="lg">Jelajahi lowongan</x-button>
            </div>
            @if($statistics->isNotEmpty())
                <dl class="mt-10 grid max-w-2xl grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach($statistics as $label => $count)
                        <div class="rounded-xl border border-white/15 bg-white/10 p-4">
                            <dd class="font-serif text-2xl font-bold text-white">{{ number_format($count) }}</dd>
                            <dt class="mt-1 text-xs font-medium text-slate-200">{{ $label }}</dt>
                        </div>
                    @endforeach
                </dl>
            @endif
        </div>
        <div class="relative mx-auto w-full max-w-xl lg:col-span-5 lg:max-w-none">
            <div class="absolute -inset-3 rotate-2 rounded-[2rem] border border-brand-peach/40" aria-hidden="true"></div>
            <img src="{{ asset('images/landing-hero.jpg') }}" alt="Seseorang sedang merencanakan langkah karier" class="relative h-72 w-full rounded-[1.75rem] object-cover shadow-float sm:h-96">
        </div>
    </div>
</section>

<section id="lowongan" class="scroll-mt-24 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-page-header level="2" title="Peluang pilihan" description="Temukan pekerjaan dan kesempatan yang baru diterbitkan." eyebrow="Cari peluang" />
        @if($featuredJobs->isNotEmpty())
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($featuredJobs as $job)
                    <x-job-card :job="$job" />
                @endforeach
            </div>
            <div class="mt-6 text-center">
                <x-button as="a" :href="route('lowongan.index')" variant="secondary">Lihat semua lowongan</x-button>
            </div>
        @else
            <x-empty-state class="py-10">Belum ada lowongan. Pantau terus ya!</x-empty-state>
        @endif
    </div>
</section>

<section id="magang" class="scroll-mt-24 border-y border-brand-line bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-page-header level="2" title="Program magang" description="Bangun pengalaman kerja dan kenali bidang yang paling sesuai untukmu." eyebrow="Belajar sambil berkarya">
            <x-slot:actions>
                <x-button as="a" :href="route('magang.index')" variant="secondary">Semua program magang</x-button>
            </x-slot:actions>
        </x-page-header>
        @if($internships->isNotEmpty())
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($internships as $internship)
                    <x-job-card :job="$internship" />
                @endforeach
            </div>
        @else
            <x-empty-state class="py-10">Belum ada program magang. Pantau terus ya!</x-empty-state>
        @endif
    </div>
</section>

<section id="pelatihan" class="scroll-mt-24 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-page-header level="2" title="Pelatihan untuk berkembang" description="Pilih kelas yang sesuai dengan tujuan belajar dan jadwalmu." eyebrow="Asah keterampilan">
            <x-slot:actions>
                <x-button as="a" :href="route('pelatihan.index')" variant="secondary">Semua pelatihan</x-button>
            </x-slot:actions>
        </x-page-header>
        @if($featuredTrainings->isNotEmpty())
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($featuredTrainings as $training)
                    <x-training-card :training="$training" />
                @endforeach
            </div>
        @else
            <x-empty-state class="py-10">Belum ada pelatihan.</x-empty-state>
        @endif
    </div>
</section>

<section id="tentang" class="scroll-mt-24 bg-brand-teal py-16 text-brand-cream sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-bold uppercase tracking-[.18em] text-brand-peach">Cara kerja CareerUp</p>
            <h2 class="mt-3 font-serif text-3xl font-bold text-white sm:text-4xl">Satu langkah pada satu waktu.</h2>
        </div>
        <ol class="mt-10 grid gap-5 md:grid-cols-3">
            <li class="rounded-panel border border-white/15 bg-white/5 p-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-peach font-bold text-brand-teal-dark">1</span>
                <h3 class="mt-5 font-serif text-xl font-bold text-white">Buat profil</h3>
                <p class="mt-2 text-sm leading-6 text-slate-200">Isi informasi dasar dan ceritakan kemampuan serta pengalamanmu.</p>
            </li>
            <li class="rounded-panel border border-white/15 bg-white/5 p-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-peach font-bold text-brand-teal-dark">2</span>
                <h3 class="mt-5 font-serif text-xl font-bold text-white">Temukan peluang</h3>
                <p class="mt-2 text-sm leading-6 text-slate-200">Cari pekerjaan, magang, atau pelatihan yang sesuai dengan tujuanmu.</p>
            </li>
            <li class="rounded-panel border border-white/15 bg-white/5 p-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-peach font-bold text-brand-teal-dark">3</span>
                <h3 class="mt-5 font-serif text-xl font-bold text-white">Ambil langkah</h3>
                <p class="mt-2 text-sm leading-6 text-slate-200">Kirim lamaran atau daftar pelatihan dan pantau perkembanganmu.</p>
            </li>
        </ol>
    </div>
</section>

<section id="faq" class="scroll-mt-24 py-16 sm:py-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <p class="text-xs font-bold uppercase tracking-[.18em] text-brand-peach-dark">Bantuan</p>
            <h2 class="mt-2 font-serif text-3xl font-bold text-brand-teal sm:text-4xl">Pertanyaan umum</h2>
        </div>
        <div class="space-y-3">
            <details class="group rounded-xl border border-brand-line bg-white p-5 shadow-sm">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-brand-teal">
                    Bagaimana cara melamar pekerjaan?
                    <span class="text-xl transition group-open:rotate-45" aria-hidden="true">+</span>
                </summary>
                <p class="mt-3 text-sm leading-6 text-slate-600">Buat akun, verifikasi email, lengkapi profil, lalu pilih lowongan yang masih terbuka dan kirim CV.</p>
            </details>
            <details class="group rounded-xl border border-brand-line bg-white p-5 shadow-sm">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-brand-teal">
                    Apakah saya harus mendaftar untuk mengikuti pelatihan?
                    <span class="text-xl transition group-open:rotate-45" aria-hidden="true">+</span>
                </summary>
                <p class="mt-3 text-sm leading-6 text-slate-600">Ya. Daftar melalui halaman pelatihan. Pelatihan premium memerlukan langganan aktif.</p>
            </details>
            <details class="group rounded-xl border border-brand-line bg-white p-5 shadow-sm">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-brand-teal">
                    Kapan tautan kelas daring tersedia?
                    <span class="text-xl transition group-open:rotate-45" aria-hidden="true">+</span>
                </summary>
                <p class="mt-3 text-sm leading-6 text-slate-600">Tautan Zoom tersedia bagi peserta yang sudah terdaftar pada waktu yang diizinkan sebelum kelas dimulai.</p>
            </details>
        </div>
    </div>
</section>
@endsection

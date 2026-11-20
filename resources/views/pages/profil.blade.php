@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="mx-auto max-w-7xl space-y-4">
    <x-page-header title="Profil" description="Informasi, pengalaman, dan sertifikat yang telah kamu kumpulkan." eyebrow="Profil karier" />
    @if(!$user->hasVerifiedEmail())
        <div class="flex flex-col gap-3 rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-950 sm:flex-row sm:items-center sm:justify-between">
            <p>Email belum diverifikasi. Verifikasi diperlukan sebelum mengirim lamaran.</p>
            <a href="{{ route('pengaturan.email') }}" class="font-bold underline underline-offset-2">Verifikasi email</a>
        </div>
    @endif
    <x-card variant="white" padding="p-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex min-w-0 items-center gap-4">
                @if($user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" class="h-20 w-20 shrink-0 rounded-2xl object-cover ring-4 ring-[#F5F3EE]">
                @else
                    <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-[#124143] text-2xl font-bold text-white ring-4 ring-[#F5F3EE]">
                        {{ \Illuminate\Support\Str::of($user->name)->explode(' ')->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('') }}
                    </div>
                @endif
                <div>
                    <h2 class="font-heading text-2xl font-bold text-[#124143] sm:text-3xl">{{ $user->name }}</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ $user->email }}</p>
                    <p class="mt-1 flex items-center gap-1.5 text-sm text-slate-500">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        {{ $user->location ?: 'Lokasi belum diisi' }}
                    </p>
                </div>
            </div>
            <x-button as="a" :href="route('profil.edit')" variant="primary">Edit Profil</x-button>
        </div>
    </x-card>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        @foreach([
            ['label' => 'Lowongan Dilamar', 'value' => $user->applications_count],
            ['label' => 'Lowongan Diterima', 'value' => $user->accepted_applications_count],
            ['label' => 'Pelatihan', 'value' => $user->training_enrollments_count],
            ['label' => 'Sertifikat', 'value' => $user->certificates_count],
        ] as $stat)
            <x-card variant="white" padding="p-3">
                <p class="font-heading text-2xl font-bold text-[#124143]">{{ $stat['value'] }}</p>
                <p class="mt-1 text-xs font-medium leading-4 text-slate-500">{{ $stat['label'] }}</p>
            </x-card>
        @endforeach
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="space-y-4">
            <x-card variant="white" padding="p-4">
                <h2 class="mb-2 font-heading text-lg font-bold text-[#124143]">Tentang Saya</h2>
                <p class="whitespace-pre-line text-sm leading-relaxed text-slate-700">{{ $user->bio ?: 'Belum ada informasi tentang diri Anda.' }}</p>
            </x-card>

            <x-card variant="white" padding="p-4">
                <h2 class="mb-3 font-heading text-lg font-bold text-[#124143]">Skill</h2>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->skills as $skill)
                        <span class="rounded-xl border border-slate-200 bg-[#F5F3EE] px-3 py-1.5 text-sm font-semibold text-[#124143]">{{ $skill->skill_name }}</span>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada skill yang ditambahkan.</p>
                    @endforelse
                </div>
            </x-card>
        </div>

        <div class="space-y-4">
            <x-card variant="white" padding="p-4">
                <h2 class="mb-3 font-heading text-lg font-bold text-[#124143]">Pendidikan</h2>
                <div class="space-y-3">
                    @forelse($user->educations as $education)
                        <div class="border-l-2 border-[#124143]/20 pl-4">
                            <h3 class="font-heading font-bold text-[#124143]">{{ $education->institution }}</h3>
                            <p class="mt-1 text-sm text-slate-700">{{ $education->major }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $education->start_year }} - {{ $education->end_year ?: 'Sekarang' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data pendidikan.</p>
                    @endforelse
                </div>
            </x-card>

            <x-card variant="white" padding="p-4">
                <h2 class="mb-3 font-heading text-lg font-bold text-[#124143]">Pengalaman</h2>
                <div class="space-y-3">
                    @forelse($user->experiences as $experience)
                        <div class="border-l-2 border-[#124143]/20 pl-4">
                            <h3 class="font-heading font-bold text-[#124143]">{{ $experience->position }}</h3>
                            <p class="mt-1 text-sm text-slate-700">{{ $experience->company }} &middot; {{ $experience->period }}</p>
                            <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-600">{{ $experience->description }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada pengalaman yang tercantum.</p>
                    @endforelse
                </div>
            </x-card>

            <x-card variant="white" padding="p-4">
                <h2 class="mb-3 font-heading text-lg font-bold text-[#124143]">Sertifikat</h2>
                <div class="space-y-2">
                    @forelse($user->trainingEnrollments as $enrollment)
                        <a class="flex items-center justify-between gap-4 rounded-lg border border-slate-200 p-3 text-sm hover:border-[#124143]" href="{{ route('certificates.show', $enrollment) }}">
                            <span><span class="block font-semibold text-[#124143]">{{ $enrollment->training->title }}</span><span class="text-slate-500">Sertifikat diterbitkan {{ $enrollment->certificate_issued_at?->locale('id')->translatedFormat('d F Y') }}</span></span>
                            <span class="shrink-0 font-semibold underline">Lihat sertifikat</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada sertifikat yang diterbitkan.</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">
    <x-page-header title="Informasi Pribadi" description="Perbarui informasi diri, keterampilan, pendidikan, dan pengalamanmu." eyebrow="Profil karier">
        <x-slot:actions>
            <x-button as="a" :href="route('profil.index')" variant="secondary">Lihat profil</x-button>
        </x-slot:actions>
    </x-page-header>

    <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" class="cu-card grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
        @csrf
        @method('PATCH')

        <div class="flex flex-col items-start gap-4 sm:col-span-2 sm:flex-row sm:items-center">
            @if($user->avatar)
                <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-2xl object-cover">
            @else
                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-[#124143] text-2xl font-bold text-white" aria-label="Avatar {{ $user->name }}">
                    {{ \Illuminate\Support\Str::of($user->name)->explode(' ')->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('') }}
                </div>
            @endif
            <div class="w-full">
                <label for="avatar" class="mb-1 block text-sm font-semibold text-slate-700">Foto Profil</label>
                <input id="avatar" name="avatar" type="file" accept="image/*"
                       class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-[#124143] file:px-4 file:py-2 file:font-semibold file:text-white">
                <p class="mt-1 text-xs text-slate-500">Format gambar, maksimal 2 MB.</p>
                @error('avatar') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="sm:col-span-2">
            <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#124143] focus:outline-none focus:ring-2 focus:ring-[#124143]/20">
            @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-4 sm:col-span-2 sm:grid-cols-2">
            <div>
                <label for="phone" class="mb-1.5 block text-sm font-semibold text-slate-700">No. Telepon</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#124143] focus:outline-none focus:ring-2 focus:ring-[#124143]/20">
                @error('phone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="gender" class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis Kelamin</label>
                <select id="gender" name="gender" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-[#124143] focus:outline-none focus:ring-2 focus:ring-[#124143]/20">
                    <option value="">Pilih jenis kelamin</option>
                    @foreach(['Laki-laki', 'Perempuan', 'Lainnya'] as $gender)
                        <option value="{{ $gender }}" @selected(old('gender', $user->gender) === $gender)>{{ $gender }}</option>
                    @endforeach
                </select>
                @error('gender') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#124143] focus:outline-none focus:ring-2 focus:ring-[#124143]/20">
            @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="location" class="mb-1.5 block text-sm font-semibold text-slate-700">Lokasi</label>
            <input id="location" name="location" type="text" value="{{ old('location', $user->location) }}" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#124143] focus:outline-none focus:ring-2 focus:ring-[#124143]/20">
            @error('location') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="bio" class="mb-1.5 block text-sm font-semibold text-slate-700">Tentang Saya</label>
            <textarea id="bio" name="bio" rows="5"
                      class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#124143] focus:outline-none focus:ring-2 focus:ring-[#124143]/20">{{ old('bio', $user->bio) }}</textarea>
            @error('bio') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        @php
            $skillRows = old('skills', $user->skills->map(fn ($item) => $item->only(['id', 'skill_name']))->all());
            $educationRows = old('educations', $user->educations->map(fn ($item) => $item->only(['id', 'institution', 'major', 'start_year', 'end_year']))->all());
            $experienceRows = old('experiences', $user->experiences->map(fn ($item) => $item->only(['id', 'position', 'company', 'period', 'description']))->all());
        @endphp

        <section class="space-y-3 border-t border-slate-200 pt-4 sm:col-span-2">
            <div class="flex items-center justify-between"><h2 class="font-heading text-xl font-bold text-[#124143]">Skill</h2><button type="button" data-add-row="skills" class="text-sm font-bold text-[#124143] underline">Tambah skill</button></div>
            <div id="skills" class="space-y-3">
                @foreach($skillRows as $index => $row)
                    <div class="flex gap-2"><input type="hidden" name="skills[{{ $index }}][id]" value="{{ $row['id'] ?? '' }}"><input name="skills[{{ $index }}][skill_name]" value="{{ $row['skill_name'] ?? '' }}" placeholder="Contoh: Komunikasi" class="min-w-0 flex-1 rounded-xl border border-slate-300 px-4 py-3 text-sm"><button type="button" data-remove-row class="rounded-lg px-3 text-rose-600">Hapus</button></div>
                @endforeach
            </div>
            <template id="skills-template"><div class="flex gap-2"><input type="hidden" name="skills[__INDEX__][id]" value=""><input name="skills[__INDEX__][skill_name]" placeholder="Nama skill" class="min-w-0 flex-1 rounded-xl border border-slate-300 px-4 py-3 text-sm"><button type="button" data-remove-row class="rounded-lg px-3 text-rose-600">Hapus</button></div></template>
        </section>

        <section class="space-y-3 border-t border-slate-200 pt-4 sm:col-span-2">
            <div class="flex items-center justify-between"><h2 class="font-heading text-xl font-bold text-[#124143]">Pendidikan</h2><button type="button" data-add-row="educations" class="text-sm font-bold text-[#124143] underline">Tambah pendidikan</button></div>
            <div id="educations" class="space-y-3">
                @foreach($educationRows as $index => $row)
                    <div class="grid gap-2 rounded-xl border border-slate-200 p-3 sm:grid-cols-2"><input type="hidden" name="educations[{{ $index }}][id]" value="{{ $row['id'] ?? '' }}"><input name="educations[{{ $index }}][institution]" value="{{ $row['institution'] ?? '' }}" placeholder="Institusi" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><input name="educations[{{ $index }}][major]" value="{{ $row['major'] ?? '' }}" placeholder="Jurusan" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><input name="educations[{{ $index }}][start_year]" value="{{ $row['start_year'] ?? '' }}" placeholder="Tahun mulai" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><input name="educations[{{ $index }}][end_year]" value="{{ $row['end_year'] ?? '' }}" placeholder="Tahun selesai (opsional)" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><button type="button" data-remove-row class="text-left text-sm text-rose-600">Hapus pendidikan</button></div>
                @endforeach
            </div>
            <template id="educations-template"><div class="grid gap-2 rounded-xl border border-slate-200 p-3 sm:grid-cols-2"><input type="hidden" name="educations[__INDEX__][id]" value=""><input name="educations[__INDEX__][institution]" placeholder="Institusi" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><input name="educations[__INDEX__][major]" placeholder="Jurusan" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><input name="educations[__INDEX__][start_year]" placeholder="Tahun mulai" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><input name="educations[__INDEX__][end_year]" placeholder="Tahun selesai (opsional)" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><button type="button" data-remove-row class="text-left text-sm text-rose-600">Hapus pendidikan</button></div></template>
        </section>

        <section class="space-y-3 border-t border-slate-200 pt-4 sm:col-span-2">
            <div class="flex items-center justify-between"><h2 class="font-heading text-xl font-bold text-[#124143]">Pengalaman</h2><button type="button" data-add-row="experiences" class="text-sm font-bold text-[#124143] underline">Tambah pengalaman</button></div>
            <div id="experiences" class="space-y-3">
                @foreach($experienceRows as $index => $row)
                    <div class="grid gap-2 rounded-xl border border-slate-200 p-3 sm:grid-cols-2"><input type="hidden" name="experiences[{{ $index }}][id]" value="{{ $row['id'] ?? '' }}"><input name="experiences[{{ $index }}][position]" value="{{ $row['position'] ?? '' }}" placeholder="Posisi" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><input name="experiences[{{ $index }}][company]" value="{{ $row['company'] ?? '' }}" placeholder="Perusahaan" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><input name="experiences[{{ $index }}][period]" value="{{ $row['period'] ?? '' }}" placeholder="Periode" class="rounded-lg border border-slate-300 px-3 py-2 text-sm sm:col-span-2"><textarea name="experiences[{{ $index }}][description]" placeholder="Deskripsi" class="rounded-lg border border-slate-300 px-3 py-2 text-sm sm:col-span-2">{{ $row['description'] ?? '' }}</textarea><button type="button" data-remove-row class="text-left text-sm text-rose-600">Hapus pengalaman</button></div>
                @endforeach
            </div>
            <template id="experiences-template"><div class="grid gap-2 rounded-xl border border-slate-200 p-3 sm:grid-cols-2"><input type="hidden" name="experiences[__INDEX__][id]" value=""><input name="experiences[__INDEX__][position]" placeholder="Posisi" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><input name="experiences[__INDEX__][company]" placeholder="Perusahaan" class="rounded-lg border border-slate-300 px-3 py-2 text-sm"><input name="experiences[__INDEX__][period]" placeholder="Periode" class="rounded-lg border border-slate-300 px-3 py-2 text-sm sm:col-span-2"><textarea name="experiences[__INDEX__][description]" placeholder="Deskripsi" class="rounded-lg border border-slate-300 px-3 py-2 text-sm sm:col-span-2"></textarea><button type="button" data-remove-row class="text-left text-sm text-rose-600">Hapus pengalaman</button></div></template>
        </section>

        <div class="sm:col-span-2"><x-button type="submit" variant="peach" size="lg">Simpan Perubahan</x-button></div>
    </form>
</div>
<script>
    document.querySelectorAll('[data-add-row]').forEach((button) => {
        button.addEventListener('click', () => {
            const group = button.dataset.addRow;
            const container = document.getElementById(group);
            const index = Date.now();
            container.insertAdjacentHTML('beforeend', document.getElementById(`${group}-template`).innerHTML.replaceAll('__INDEX__', index));
        });
    });
    document.addEventListener('click', (event) => {
        if (event.target.matches('[data-remove-row]')) event.target.closest('.flex, .grid').remove();
    });
</script>
@endsection

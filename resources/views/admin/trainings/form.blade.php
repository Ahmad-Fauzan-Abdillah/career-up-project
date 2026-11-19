@extends('layouts.admin')

@section('title', $training->exists ? 'Ubah Pelatihan' : 'Tambah Pelatihan')

@section('content')
<x-page-header
    :title="$training->exists ? 'Ubah pelatihan' : 'Tambah pelatihan'"
    description="Atur jadwal, mentor, materi, dan kuota peserta."
    eyebrow="Manajemen program">
    <x-slot:actions>
        <x-button as="a" :href="route('admin.trainings.index')" variant="secondary">Kembali ke pelatihan</x-button>
    </x-slot:actions>
</x-page-header>

@if($errors->any())
    <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-950">
        <p class="font-bold">Periksa kembali formulir.</p>
        <ul class="mt-2 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form
    method="POST"
    enctype="multipart/form-data"
    action="{{ $training->exists ? route('admin.trainings.update', $training) : route('admin.trainings.store') }}"
    class="cu-card grid gap-5 p-5 sm:grid-cols-2 sm:p-7">
    @csrf
    @if($training->exists) @method('PUT') @endif

    <div class="sm:col-span-2">
        <x-input name="title" label="Judul pelatihan" :value="$training->title" required />
    </div>
    <x-select name="category" label="Kategori" :selected="$training->category" :options="['Gratis' => 'Gratis', 'Premium' => 'Premium']" required />
    <x-input name="schedule_date" label="Tanggal" type="date" :value="$training->schedule_date?->format('Y-m-d')" required />
    <x-input name="start_time" label="Jam mulai" type="time" :value="$training->start_time ? substr($training->start_time, 0, 5) : ''" required />
    <x-input name="end_time" label="Jam selesai" type="time" :value="$training->end_time ? substr($training->end_time, 0, 5) : ''" required />
    <div class="sm:col-span-2">
        <x-input name="zoom_link" label="Tautan Zoom" type="url" :value="$training->zoom_link" required />
    </div>
    <x-input name="mentor_name" label="Nama mentor" :value="$training->mentor_name" required />
    <x-input name="mentor_role" label="Jabatan mentor" :value="$training->mentor_role" required />

    <div>
        <label for="mentor_avatar" class="cu-label">Foto mentor</label>
        @if($training->mentor_avatar)
            <img src="{{ asset('storage/'.$training->mentor_avatar) }}" alt="Foto {{ $training->mentor_name }}" class="mb-3 h-14 w-14 rounded-full object-cover">
        @endif
        <input id="mentor_avatar" type="file" name="mentor_avatar" accept=".jpg,.jpeg,.png,.webp" class="cu-field file:mr-3 file:rounded-lg file:border-0 file:bg-brand-teal file:px-3 file:py-2 file:font-semibold file:text-white">
        <p class="mt-1 text-xs text-slate-600">JPG, PNG, atau WEBP; maksimal 2 MB.</p>
        @error('mentor_avatar') <p class="mt-1.5 text-sm text-rose-800">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="thumbnail" class="cu-label">Thumbnail pelatihan</label>
        @if($training->thumbnail)
            <img src="{{ asset('storage/'.$training->thumbnail) }}" alt="Thumbnail {{ $training->title }}" class="mb-3 h-14 w-24 rounded-lg object-cover">
        @endif
        <input id="thumbnail" type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp" class="cu-field file:mr-3 file:rounded-lg file:border-0 file:bg-brand-teal file:px-3 file:py-2 file:font-semibold file:text-white">
        <p class="mt-1 text-xs text-slate-600">JPG, PNG, atau WEBP; maksimal 2 MB.</p>
        @error('thumbnail') <p class="mt-1.5 text-sm text-rose-800">{{ $message }}</p> @enderror
    </div>

    <x-input name="duration" label="Durasi" :value="$training->duration" required />
    <x-input name="level" label="Level" :value="$training->level" required />
    <x-input name="quota" label="Kuota (opsional)" type="number" :value="$training->quota" min="1" />
    <x-select name="status" label="Status" :selected="$training->status ?: 'draft'" :options="['draft' => 'Draft', 'terbit' => 'Terbit']" required />
    <div class="sm:col-span-2">
        <x-textarea name="description" label="Deskripsi" :value="$training->description" rows="5" required />
    </div>
    <div class="sm:col-span-2">
        <x-textarea name="syllabus" label="Silabus (satu materi per baris)" :value="$training->syllabus" rows="5" required />
    </div>
    <div class="sm:col-span-2">
        <x-button type="submit" variant="primary" size="lg">Simpan pelatihan</x-button>
    </div>
</form>
@endsection

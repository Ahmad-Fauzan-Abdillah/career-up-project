@extends('layouts.admin')

@section('title', $job->exists ? 'Ubah '.ucfirst($type) : 'Tambah '.ucfirst($type))

@section('content')
<x-page-header
    :title="($job->exists ? 'Ubah ' : 'Tambah ').ucfirst($type)"
    description="Lengkapi informasi peluang agar calon pelamar mendapat gambaran yang jelas."
    eyebrow="Manajemen peluang">
    <x-slot:actions>
        <x-button as="a" :href="route('admin.jobs.index', ['type' => $type])" variant="secondary">Kembali ke {{ ucfirst($type) }}</x-button>
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
    action="{{ $job->exists ? route('admin.jobs.update', ['type' => $type, 'job' => $job]) : route('admin.jobs.store', ['type' => $type]) }}"
    class="cu-card grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
    @csrf
    @if($job->exists) @method('PUT') @endif

    <x-input name="title" label="Judul" :value="$job->title" required />
    <x-input name="company_name" label="Nama perusahaan" :value="$job->company_name" required />

    <div>
        <label for="company_logo" class="cu-label">Logo perusahaan</label>
        @if($job->company_logo)
            <img src="{{ asset('storage/'.$job->company_logo) }}" alt="Logo {{ $job->company_name }}" class="mb-3 h-14 w-14 rounded-lg border border-slate-200 object-contain">
        @endif
        <input id="company_logo" type="file" name="company_logo" accept=".jpg,.jpeg,.png,.webp" class="cu-field file:mr-3 file:rounded-lg file:border-0 file:bg-brand-teal file:px-3 file:py-2 file:font-semibold file:text-white">
        <p class="mt-1 text-xs text-slate-600">JPG, PNG, atau WEBP; maksimal 2 MB.</p>
        @error('company_logo') <p class="mt-1.5 text-sm text-rose-800">{{ $message }}</p> @enderror
    </div>
    <x-input name="location" label="Lokasi" :value="$job->location" required />

    <div>
        <label for="job_type" class="cu-label">Tipe</label>
        <input id="job_type" value="{{ ucfirst($type) }}" disabled class="cu-field">
    </div>
    <x-input name="work_system" label="Sistem kerja" :value="$job->work_system" placeholder="Contoh: Remote atau hybrid" required />
    @if($type === 'magang')
        <x-input name="duration" label="Durasi magang" :value="$job->duration" required />
    @else
        <input type="hidden" name="duration" value="{{ old('duration', $job->duration) }}">
    @endif
    <x-input name="department" label="Departemen" :value="$job->department" />
    <x-input name="salary_min" label="Gaji minimum (Rp)" type="number" :value="$job->salary_min" min="0" />
    <x-input name="salary_max" label="Gaji maksimum (Rp)" type="number" :value="$job->salary_max" min="0" />
    <x-input name="deadline" label="Batas lamaran" type="date" :value="$job->deadline?->format('Y-m-d')" />
    <x-select name="status" label="Status" :selected="$job->status ?: 'draft'" :options="['draft' => 'Draft', 'terbit' => 'Terbit', 'ditutup' => 'Ditutup']" required />

    <label class="flex min-h-12 items-center gap-3 text-sm font-semibold text-slate-800">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $job->is_featured)) class="h-5 w-5 rounded border-slate-300 text-brand-teal focus:ring-brand-teal">
        Tampilkan sebagai peluang pilihan
    </label>

    <div class="sm:col-span-2">
        <x-textarea name="description" label="Deskripsi" :value="$job->description" rows="6" required />
    </div>
    <div class="sm:col-span-2">
        <x-textarea name="qualifications" label="Kualifikasi (satu per baris)" :value="$job->qualifications" rows="6" required />
    </div>
    <div class="sm:col-span-2">
        <x-button type="submit" variant="primary" size="lg">Simpan {{ ucfirst($type) }}</x-button>
    </div>
</form>
@endsection

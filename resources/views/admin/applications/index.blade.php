@extends('layouts.admin')

@section('title', 'Pelamar')

@section('content')
<x-page-header title="Pelamar" description="Tinjau lamaran, profil, dan berkas CV. Perubahan status akan memberi tahu pelamar." eyebrow="Manajemen pelamar" />

<form method="GET" class="cu-card mb-5 grid gap-3 p-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-input name="q" label="Nama pelamar" :value="request('q')" placeholder="Cari nama" />
    <x-select name="job_listing_id" label="Lowongan" :selected="request('job_listing_id')" placeholder="Semua lowongan" :options="$jobs->pluck('title', 'id')->all()" />
    <x-select name="status" label="Status lamaran" :selected="request('status')" placeholder="Semua status" :options="['Diproses' => 'Diproses', 'Diterima' => 'Diterima', 'Ditolak' => 'Ditolak', 'Dibatalkan' => 'Dibatalkan']" />
    <div class="flex items-end">
        <x-button type="submit" variant="secondary" class="w-full">Terapkan filter</x-button>
    </div>
</form>

@if($applications->isEmpty())
    <x-empty-state>Belum ada pelamar yang sesuai.</x-empty-state>
@else
    <x-table label="Daftar pelamar" class="min-w-[1100px]">
        <thead>
            <tr><th>Pelamar</th><th>Posisi</th><th>Tanggal</th><th>Berkas</th><th>Status dan catatan</th></tr>
        </thead>
        <tbody>
            @foreach($applications as $application)
                <tr>
                    <td>
                        <p class="font-semibold text-slate-900">{{ $application->full_name }}</p>
                        <p class="text-xs text-slate-600">{{ $application->email }}</p>
                    </td>
                    <td>{{ $application->jobListing?->title }}</td>
                    <td>{{ $application->created_at->locale('id')->translatedFormat('d M Y') }}</td>
                    <td>
                        <div class="flex min-w-32 flex-col items-start gap-2">
                            <a class="font-semibold text-brand-teal underline underline-offset-2" href="{{ route('admin.applications.show', $application) }}">Detail pelamar</a>
                            <a class="font-semibold text-brand-teal underline underline-offset-2" href="{{ route('admin.applications.cv', $application) }}">Unduh CV</a>
                        </div>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.applications.update', $application) }}" class="min-w-64 space-y-2">
                            @csrf
                            @method('PATCH')
                            <x-select
                                name="status"
                                :id="'application_status_'.$application->id"
                                label="Status"
                                :selected="old('status', $application->status)"
                                :options="['Diproses' => 'Diproses', 'Diterima' => 'Diterima', 'Ditolak' => 'Ditolak']" />
                            <x-input
                                name="notes"
                                :id="'application_notes_'.$application->id"
                                label="Catatan admin"
                                :value="old('notes', $application->notes)"
                                placeholder="Catatan untuk pelamar" />
                            <x-button type="submit" variant="primary" size="sm">Simpan perubahan</x-button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-table>
    <x-pagination :paginator="$applications" />
@endif
@endsection

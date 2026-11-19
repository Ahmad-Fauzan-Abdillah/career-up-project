@extends('layouts.admin')

@section('title', ucfirst($type))

@section('content')
<x-page-header
    :title="ucfirst($type)"
    :description="'Kelola lowongan '.$type.' CareerUp.'"
    eyebrow="Manajemen peluang">
    <x-slot:actions>
        <x-button as="a" :href="route('admin.jobs.create', ['type' => $type])" variant="primary">Tambah {{ $type }}</x-button>
    </x-slot:actions>
</x-page-header>

<form method="GET" class="cu-card mb-5 grid gap-3 p-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-input name="q" label="Pencarian" :value="request('q')" placeholder="Judul, perusahaan, lokasi" />
    <x-select name="type" label="Tipe" :selected="$type" :options="['lowongan' => 'Lowongan', 'magang' => 'Magang']" />
    <x-select name="status" label="Status" :selected="request('status')" :placeholder="'Semua status'" :options="['draft' => 'Draft', 'terbit' => 'Terbit', 'ditutup' => 'Ditutup']" />
    <div class="flex items-end">
        <x-button type="submit" variant="secondary" class="w-full">Terapkan filter</x-button>
    </div>
</form>

@if($jobs->isEmpty())
    <x-empty-state>Belum ada {{ $type }} yang sesuai.</x-empty-state>
@else
    <x-table label="Daftar {{ $type }}" class="min-w-[900px]">
        <thead>
            <tr><th>Posisi</th><th>Perusahaan</th><th>Pelamar</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($jobs as $job)
                @php($statusVariant = match($job->status) { 'terbit' => 'success', 'ditutup' => 'default', default => 'warning' })
                <tr>
                    <td class="font-semibold text-slate-900">
                        {{ $job->title }}
                        @if($job->deadline)
                            <span class="mt-1 block text-xs font-normal text-slate-500">Batas: {{ $job->deadline->format('d/m/Y') }}</span>
                        @endif
                    </td>
                    <td>{{ $job->company_name }}</td>
                    <td>{{ $job->applications_count }}</td>
                    <td><x-badge :variant="$statusVariant">{{ ucfirst($job->status) }}</x-badge></td>
                    <td>
                        <div class="flex min-w-[22rem] flex-wrap items-center gap-2">
                            <x-button as="a" :href="route('admin.jobs.edit', ['type' => $type, 'job' => $job])" variant="ghost" size="sm">Ubah</x-button>
                            @if($job->status !== 'terbit')
                                <form method="POST" action="{{ route('admin.jobs.publish', ['type' => $type, 'job' => $job]) }}">
                                    @csrf @method('PATCH')
                                    <x-button type="submit" variant="secondary" size="sm">Terbitkan</x-button>
                                </form>
                            @endif
                            @if($job->status !== 'ditutup')
                                <form method="POST" action="{{ route('admin.jobs.close', ['type' => $type, 'job' => $job]) }}">
                                    @csrf @method('PATCH')
                                    <x-button type="submit" variant="secondary" size="sm">Tutup</x-button>
                                </form>
                            @endif
                            @if($job->applications_count > 0)
                                <span class="text-xs text-slate-600">Ada pelamar; tutup alih-alih hapus.</span>
                            @else
                                <x-confirm-form
                                    :action="route('admin.jobs.destroy', ['type' => $type, 'job' => $job])"
                                    label="Hapus"
                                    message="Lowongan ini akan dipindahkan ke arsip. Lanjutkan?">
                                    Hapus
                                </x-confirm-form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-table>
    <x-pagination :paginator="$jobs" />
@endif
@endsection

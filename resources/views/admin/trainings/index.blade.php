@extends('layouts.admin')

@section('title', 'Pelatihan')

@section('content')
<x-page-header title="Pelatihan" description="Kelola jadwal, peserta, dan sertifikat kelas." eyebrow="Manajemen program">
    <x-slot:actions>
        <x-button as="a" :href="route('admin.trainings.create')" variant="primary">Tambah pelatihan</x-button>
    </x-slot:actions>
</x-page-header>

<form method="GET" class="cu-card mb-5 grid gap-3 p-4 sm:grid-cols-3">
    <x-input name="q" label="Pencarian" :value="request('q')" placeholder="Judul pelatihan" />
    <x-select name="status" label="Status" :selected="request('status')" placeholder="Semua status" :options="['draft' => 'Draft', 'terbit' => 'Terbit']" />
    <div class="flex items-end">
        <x-button type="submit" variant="secondary" class="w-full">Terapkan filter</x-button>
    </div>
</form>

@if($trainings->isEmpty())
    <x-empty-state>Belum ada pelatihan yang sesuai.</x-empty-state>
@else
    <x-table label="Daftar pelatihan" class="min-w-[850px]">
        <thead>
            <tr><th>Pelatihan</th><th>Tanggal</th><th>Kategori</th><th>Peserta</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($trainings as $training)
                <tr>
                    <td class="font-semibold text-slate-900">{{ $training->title }}</td>
                    <td>{{ $training->formatted_schedule_date ?: 'Belum dijadwalkan' }}</td>
                    <td>{{ $training->category }}</td>
                    <td>{{ $training->enrollments_count }}</td>
                    <td><x-badge :variant="$training->status === 'terbit' ? 'success' : 'warning'">{{ ucfirst($training->status) }}</x-badge></td>
                    <td>
                        <div class="flex min-w-[13rem] flex-wrap items-center gap-2">
                            <x-button as="a" :href="route('admin.trainings.edit', $training)" variant="ghost" size="sm">Ubah</x-button>
                            <x-button as="a" :href="route('admin.trainings.participants', $training)" variant="secondary" size="sm">Peserta</x-button>
                            <x-confirm-form :action="route('admin.trainings.destroy', $training)" label="Hapus" message="Pelatihan ini akan dipindahkan ke arsip. Lanjutkan?">Hapus</x-confirm-form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-table>
    <x-pagination :paginator="$trainings" />
@endif
@endsection

@extends('layouts.admin')

@section('title', 'Peserta Pelatihan')

@section('content')
<x-page-header
    :title="'Peserta: '.$training->title"
    description="Tinjau peserta kelas dan terbitkan sertifikat setelah memenuhi syarat."
    eyebrow="Manajemen pelatihan">
    <x-slot:actions>
        <x-button as="a" :href="route('admin.trainings.index')" variant="secondary">Kembali ke pelatihan</x-button>
    </x-slot:actions>
</x-page-header>

@if($participants->isEmpty())
    <x-empty-state>Belum ada peserta untuk pelatihan ini.</x-empty-state>
@else
    <x-table label="Daftar peserta" class="min-w-[800px]">
        <thead>
            <tr><th>Nama</th><th>Email</th><th>Tanggal bergabung</th><th>Sertifikat</th></tr>
        </thead>
        <tbody>
            @foreach($participants as $participant)
                <tr>
                    <td class="font-semibold text-slate-900">{{ $participant->user->name }}</td>
                    <td>{{ $participant->user->email }}</td>
                    <td>{{ $participant->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</td>
                    <td>
                        @if($participant->certificate_issued)
                            <x-badge variant="success">Diterbitkan</x-badge>
                        @else
                            <form method="POST" action="{{ route('admin.trainings.certificate.issue', [$training, $participant]) }}">
                                @csrf
                                @method('PATCH')
                                <x-button type="submit" variant="primary" size="sm">Terbitkan sertifikat</x-button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-table>
    <x-pagination :paginator="$participants" />
@endif
@endsection

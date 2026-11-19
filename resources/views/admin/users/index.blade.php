@extends('layouts.admin')

@section('title', 'Pengguna')

@section('content')
<x-page-header title="Pengguna" description="Telusuri akun dan tanggal pendaftaran pengguna CareerUp." eyebrow="Manajemen akun" />

<form method="GET" class="cu-card mb-5 flex flex-col gap-3 p-4 sm:flex-row">
    <x-input name="q" label="Pencarian pengguna" :value="request('q')" placeholder="Nama atau email" class="flex-1" />
    <div class="flex items-end">
        <x-button type="submit" variant="secondary">Cari pengguna</x-button>
    </div>
</form>

@if($users->isEmpty())
    <x-empty-state>Belum ada pengguna yang sesuai.</x-empty-state>
@else
    <x-table label="Daftar pengguna" class="min-w-[700px]">
        <thead>
            <tr><th>Nama</th><th>Email</th><th>Peran</th><th>Terdaftar</th></tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="font-semibold text-slate-900">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><x-badge :variant="$user->isAdmin() ? 'teal-light' : 'default'">{{ ucfirst($user->role) }}</x-badge></td>
                    <td>{{ $user->created_at->locale('id')->translatedFormat('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </x-table>
    <x-pagination :paginator="$users" />
@endif
@endsection

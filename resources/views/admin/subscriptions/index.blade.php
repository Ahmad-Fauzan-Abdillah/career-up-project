@extends('layouts.admin')

@section('title', 'Langganan')

@section('content')
<x-page-header title="Langganan" description="Tinjau pembayaran dan verifikasi status langganan pengguna." eyebrow="Pembayaran manual" />

<form method="GET" class="cu-card mb-5 flex flex-col gap-3 p-4 sm:flex-row">
    <x-select name="status" label="Status langganan" :selected="request('status')" placeholder="Semua status" :options="['pending' => 'Menunggu verifikasi', 'aktif' => 'Aktif', 'ditolak' => 'Ditolak', 'kedaluwarsa' => 'Kedaluwarsa']" class="flex-1" />
    <div class="flex items-end">
        <x-button type="submit" variant="secondary">Terapkan filter</x-button>
    </div>
</form>

@if($errors->any())
    <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-950">{{ $errors->first() }}</div>
@endif

@if($subscriptions->isEmpty())
    <x-empty-state>Belum ada langganan yang sesuai.</x-empty-state>
@else
    <x-table label="Daftar langganan" class="min-w-[1100px]">
        <thead>
            <tr><th>Pengguna</th><th>Pelatihan</th><th>Paket</th><th>Nominal</th><th>Status</th><th>Bukti dan tindakan</th></tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $subscription)
                @php
                    $statusVariant = match($subscription->status) {
                        'aktif' => 'success',
                        'pending' => 'warning',
                        'ditolak' => 'danger',
                        default => 'default',
                    };
                @endphp
                <tr>
                    <td>
                        <p class="font-semibold text-slate-900">{{ $subscription->user->name }}</p>
                        <p class="text-xs text-slate-600">{{ $subscription->user->email }}</p>
                    </td>
                    <td>{{ $subscription->training?->title ?? 'Semua pelatihan premium' }}</td>
                    <td>{{ ucfirst($subscription->plan_type) }}</td>
                    <td class="font-semibold">Rp{{ number_format($subscription->price + $subscription->unique_code, 0, ',', '.') }}</td>
                    <td>
                        <x-badge :variant="$statusVariant">{{ ucfirst($subscription->status) }}</x-badge>
                        @if($subscription->rejection_reason)
                            <p class="mt-2 max-w-xs text-xs leading-5 text-rose-800">{{ $subscription->rejection_reason }}</p>
                        @endif
                    </td>
                    <td>
                        @if($subscription->payment_proof)
                            <a class="font-semibold text-brand-teal underline underline-offset-2" href="{{ route('admin.subscriptions.proof', $subscription) }}">Lihat bukti pembayaran</a>
                        @else
                            <span class="text-sm text-slate-500">Belum ada bukti</span>
                        @endif
                        @if($subscription->status === 'pending')
                            <form method="POST" action="{{ route('admin.subscriptions.verify', $subscription) }}" class="mt-3 min-w-64 space-y-3">
                                @csrf
                                @method('PATCH')
                                <x-input name="rejection_reason" :id="'rejection_reason_'.$subscription->id" label="Alasan jika menolak" :value="old('rejection_reason')" />
                                <div class="flex flex-wrap gap-2">
                                    <x-button type="submit" name="action" value="confirm" variant="primary" size="sm">Konfirmasi</x-button>
                                    <x-button type="submit" name="action" value="reject" variant="danger" size="sm">Tolak</x-button>
                                </div>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-table>
    <x-pagination :paginator="$subscriptions" />
@endif
@endsection

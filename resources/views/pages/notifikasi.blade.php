@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <x-page-header title="Notifikasi" description="Pembaruan penting tentang akun dan aktivitas kariermu." eyebrow="Pusat informasi">
        @if(auth()->user()->unreadNotificationsCount() > 0)
            <x-slot:actions>
                <form method="POST" action="{{ route('notifikasi.read-all') }}">
                    @csrf
                    @method('PATCH')
                    <x-button type="submit" variant="secondary" size="sm">Tandai semua dibaca</x-button>
                </form>
            </x-slot:actions>
        @endif
    </x-page-header>

    @if($notifications->isNotEmpty())
        <ol class="space-y-3">
            @foreach($notifications as $notification)
                <li>
                    <form method="POST" action="{{ route('notifikasi.read', $notification) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="group flex w-full items-start gap-4 rounded-panel border p-5 text-left shadow-sm transition hover:border-teal-300 hover:shadow-card focus-visible:outline-brand-teal {{ $notification->is_read ? 'border-brand-line bg-white' : 'border-teal-200 bg-brand-teal-soft' }}">
                            <span class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $notification->is_read ? 'bg-slate-100 text-slate-500' : 'bg-brand-teal text-white' }}" aria-hidden="true">
                                @if($notification->is_read)
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12l4 4L19 6" /></svg>
                                @else
                                    <span class="h-2.5 w-2.5 rounded-full bg-brand-peach"></span>
                                @endif
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <span class="font-serif text-base font-bold text-brand-teal">{{ $notification->title }}</span>
                                    <x-badge :variant="$notification->is_read ? 'default' : 'teal-light'" size="sm">{{ $notification->is_read ? 'Dibaca' : 'Baru' }}</x-badge>
                                </span>
                                <span class="mt-2 block text-sm leading-6 text-slate-700">{{ $notification->message }}</span>
                                <time class="mt-3 block text-xs text-slate-500" datetime="{{ $notification->created_at->toIso8601String() }}">
                                    {{ $notification->created_at->locale('id')->diffForHumans() }}
                                </time>
                                @if($notification->url)
                                    <span class="mt-2 inline-flex text-xs font-bold text-brand-teal group-hover:underline">Buka informasi <span class="ml-1" aria-hidden="true">→</span></span>
                                @endif
                            </span>
                        </button>
                    </form>
                </li>
            @endforeach
        </ol>
    @else
        <x-empty-state>Belum ada notifikasi</x-empty-state>
    @endif

    <x-pagination :paginator="$notifications" />
</div>
@endsection

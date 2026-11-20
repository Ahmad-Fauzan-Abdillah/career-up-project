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
        @php
            $notificationGroups = $notifications->getCollection()->groupBy(fn ($notification) => $notification->created_at->isToday()
                ? 'Hari ini'
                : ($notification->created_at->isYesterday() ? 'Kemarin' : 'Lebih lama'));
        @endphp
        <div class="space-y-4">
            @foreach(['Hari ini', 'Kemarin', 'Lebih lama'] as $groupLabel)
                @if($notificationGroups->has($groupLabel))
                    <section>
                        <h2 class="mb-2 font-heading text-base font-bold text-brand-teal">{{ $groupLabel }}</h2>
                        <ol class="divide-y divide-brand-line rounded-xl border border-brand-line bg-white">
                            @foreach($notificationGroups[$groupLabel] as $notification)
                                <li>
                                    <form method="POST" action="{{ route('notifikasi.read', $notification) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="group flex w-full items-start gap-3 px-4 py-3 text-left transition-colors hover:bg-brand-teal-soft focus-visible:outline-brand-teal {{ $notification->is_read ? '' : 'bg-brand-teal-soft/60' }}">
                                            <span class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $notification->is_read ? 'bg-slate-100 text-slate-500' : 'bg-brand-teal text-white' }}" aria-hidden="true">
                                                @if($notification->is_read)
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12l4 4L19 6" /></svg>
                                                @else
                                                    <span class="h-2.5 w-2.5 rounded-full bg-brand-peach"></span>
                                                @endif
                                            </span>
                                            <span class="min-w-0 flex-1">
                                                <span class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                                    <span class="font-heading text-sm font-bold text-brand-teal">{{ $notification->title }}</span>
                                                    <x-badge :variant="$notification->is_read ? 'default' : 'teal-light'" size="sm">{{ $notification->is_read ? 'Dibaca' : 'Baru' }}</x-badge>
                                                </span>
                                                <span class="mt-1 block text-sm leading-5 text-slate-700">{{ $notification->message }}</span>
                                                <time class="mt-1 block text-xs text-slate-500" datetime="{{ $notification->created_at->toIso8601String() }}">
                                                    {{ $notification->created_at->locale('id')->diffForHumans() }}
                                                </time>
                                                @if($notification->url)
                                                    <span class="mt-1 inline-flex text-xs font-bold text-brand-teal group-hover:underline">Buka informasi</span>
                                                @endif
                                            </span>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endif
            @endforeach
        </div>
    @else
        <p class="rounded-xl border border-brand-line bg-white px-4 py-3 text-sm text-slate-600">Belum ada notifikasi</p>
    @endif

    <x-pagination :paginator="$notifications" />
</div>
@endsection

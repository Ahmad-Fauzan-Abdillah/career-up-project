<!DOCTYPE html>
<html lang="id" class="h-full bg-brand-cream">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') - CareerUp</title>
    <meta name="description" content="@yield('meta_description', 'Kelola profil, lamaran, dan peluang kariermu bersama CareerUp.')">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="CareerUp">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', 'CareerUp')))">
    <meta property="og:description" content="@yield('og_description', 'Kelola profil, lamaran, dan peluang kariermu bersama CareerUp.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @include('components.font-preload')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-dvh overflow-hidden bg-[#F5F3EE] font-sans text-slate-800 antialiased selection:bg-[#F4A261] selection:text-[#124143]"
      x-data="{ sidebarOpen: false }"
      @keydown.escape.window="sidebarOpen = false">
    <x-toast />

    <div class="flex h-full min-h-0">
        <!-- Backdrop mobile drawer -->
        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 z-40 lg:hidden backdrop-blur-xs">
        </div>

        <!-- Sidebar Deep Teal (Matches 12-beranda.png) -->
        <aside
            id="app-navigation"
            x-cloak
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="x-sidebar fixed inset-y-0 left-0 z-50 flex h-full w-64 shrink-0 flex-col justify-between overflow-hidden bg-[#124143] px-4 py-6 text-[#F5F3EE] shadow-xl transition-transform duration-200 ease-in-out lg:translate-x-0">

            <div class="min-h-0 flex-1 overflow-y-auto">
                <!-- Header / Logo -->
                <div class="flex items-center justify-between px-2 mb-8">
                    <a href="{{ route('beranda') }}">
                        <x-logo variant="light" />
                    </a>

                    <!-- Close button on mobile -->
                    <button
                        type="button"
                        @click="sidebarOpen = false"
                        class="lg:hidden rounded-lg p-2 text-slate-200 hover:bg-white/10 hover:text-white"
                        aria-controls="app-navigation"
                        aria-label="Tutup sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Menu (Pill abu terang untuk menu aktif) -->
                <nav class="space-y-1.5 text-sm">
                    <!-- 1. Beranda -->
                    @php
                        $isBeranda = request()->routeIs('beranda');
                    @endphp
                    <a href="{{ route('beranda') }}" @if($isBeranda) aria-current="page" @endif
                               class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ $isBeranda ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'font-medium text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <span>Beranda</span>
                    </a>

                    <!-- 2. Lowongan -->
                    @php
                        $isLowongan = request()->routeIs('lowongan.*');
                    @endphp
                    <a href="{{ route('lowongan.index') }}" @if($isLowongan) aria-current="page" @endif
                       class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ $isLowongan ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'font-medium text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <span>Lowongan</span>
                    </a>

                    <!-- 3. Magang -->
                    @php
                        $isMagang = request()->routeIs('magang.*');
                    @endphp
                    <a href="{{ route('magang.index') }}" @if($isMagang) aria-current="page" @endif
                       class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ $isMagang ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'font-medium text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <span>Magang</span>
                    </a>

                    <!-- 4. Pelatihan -->
                    @php
                        $isPelatihan = request()->routeIs('pelatihan.*');
                    @endphp
                    <a href="{{ route('pelatihan.index') }}" @if($isPelatihan) aria-current="page" @endif
                       class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ $isPelatihan ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'font-medium text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <span>Pelatihan</span>
                    </a>

                    <!-- 5. Riwayat Pendaftaran -->
                    @php
                        $isRiwayat = request()->routeIs('riwayat.*');
                    @endphp
                    <a href="{{ route('riwayat.index') }}" @if($isRiwayat) aria-current="page" @endif
                       class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ $isRiwayat ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'font-medium text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <span>Riwayat Pendaftaran</span>
                    </a>

                    <!-- 6. Notifikasi -->
                    @php
                        $isNotifikasi = request()->routeIs('notifikasi.*');
                        $unreadCount = auth()->user()->unreadNotificationsCount();
                    @endphp
                    <a href="{{ route('notifikasi.index') }}" @if($isNotifikasi) aria-current="page" @endif
                       class="flex items-center justify-between rounded-xl px-4 py-3 transition {{ $isNotifikasi ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'font-medium text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <span>Notifikasi</span>
                        @if($unreadCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-[#124143] bg-[#F4A261] rounded-full shadow-xs">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <!-- 7. Pengaturan -->
                    @php
                        $isPengaturan = request()->routeIs('pengaturan.*');
                    @endphp
                    <a href="{{ route('pengaturan.index') }}" @if($isPengaturan) aria-current="page" @endif
                       class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ $isPengaturan ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'font-medium text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <span>Pengaturan</span>
                    </a>

                    <!-- 8. Profil -->
                    @php
                        $isProfil = request()->routeIs('profil.*');
                    @endphp
                    <a href="{{ route('profil.index') }}" @if($isProfil) aria-current="page" @endif
                       class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ $isProfil ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'font-medium text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <span>Profil</span>
                    </a>
                </nav>
            </div>

            <!-- Bottom: Form POST Logout -->
            <div class="shrink-0 border-t border-white/10 pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-200 hover:text-white hover:bg-white/10 transition-colors text-base font-semibold text-left">
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="ml-0 flex min-h-0 min-w-0 flex-1 flex-col overflow-y-auto lg:ml-64">
            <!-- Search and account controls -->
            <header class="x-topbar sticky top-0 z-30 border-b border-slate-200/80 bg-white">
                <div class="x-container flex h-16 items-center justify-between gap-4">
                    <!-- Mobile Hamburger -->
                    <div class="flex items-center gap-3 lg:hidden">
                        <button
                            type="button"
                            @click="sidebarOpen = true"
                            :aria-expanded="sidebarOpen.toString()"
                            aria-controls="app-navigation"
                            class="p-2 rounded-xl text-slate-600 hover:text-[#124143] hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-[#124143]"
                            aria-label="Buka menu navigasi">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <a href="{{ route('beranda') }}">
                            <x-logo variant="dark" size="sm" />
                        </a>
                    </div>

                    <!-- Search across public listings -->
                    <div class="hidden lg:flex flex-1 items-center">
                        @php
                            $searchRoute = request()->routeIs('pelatihan.*') || isset($training)
                                ? route('pelatihan.index')
                                : ((request()->routeIs('magang.*') || (isset($job) && $job->type === 'magang'))
                                    ? route('magang.index')
                                    : route('lowongan.index'));
                        @endphp
                        <form action="{{ $searchRoute }}" method="GET" role="search" class="w-full max-w-lg">
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        name="q"
                                        value="{{ request('q') }}"
                                        aria-label="Cari peluang"
                                        placeholder="Cari peluang atau perusahaan"
                                        class="cu-field py-2 pl-10">
                                </div>
                        </form>
                    </div>

                    <!-- Right Controls: Notification Bell + Avatar (12-beranda.png) -->
                    <div class="flex items-center gap-4 shrink-0">
                            <a href="{{ route('notifikasi.index') }}"
                               class="relative p-2 rounded-xl text-slate-700 hover:text-[#124143] hover:bg-slate-100 transition-colors"
                               aria-label="Notifikasi{{ $unreadCount > 0 ? ', ' . $unreadCount . ' belum dibaca' : '' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-brand-peach px-1 text-[10px] font-bold text-brand-teal-dark ring-2 ring-white">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </a>

                        <!-- User Circular Avatar -->
                        <a href="{{ route('profil.index') }}" class="flex items-center group">
                            <div class="w-10 h-10 rounded-full bg-[#124143] text-white flex items-center justify-center font-bold text-sm ring-2 ring-slate-200 overflow-hidden shrink-0 group-hover:ring-[#124143] transition-[box-shadow]">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    <span>{{ \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('') }}</span>
                                @endif
                            </div>
                        </a>
                    </div>
                </div>
                <div class="px-4 pb-3 lg:hidden">
                    @php
                        $searchRoute = request()->routeIs('pelatihan.*') || isset($training)
                            ? route('pelatihan.index')
                            : ((request()->routeIs('magang.*') || (isset($job) && $job->type === 'magang'))
                                ? route('magang.index')
                                : route('lowongan.index'));
                    @endphp
                    <form action="{{ $searchRoute }}" method="GET" role="search">
                        <input type="search" name="q" value="{{ request('q') }}" aria-label="Cari peluang"
                               placeholder="Cari peluang atau perusahaan" class="cu-field">
                    </form>
                </div>
            </header>

            <!-- Main Content Area berlatar Cream (F5F3EE) -->
            <main class="min-h-0 flex-1 @yield('main-class', 'bg-[#F5F3EE] py-6')">
                <div class="@yield('content-container-class', 'x-container')">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>

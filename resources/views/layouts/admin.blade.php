<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') - CareerUp</title>
    <meta name="description" content="@yield('meta_description', 'Kelola CareerUp.')">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="CareerUp">
    <meta property="og:title" content="@yield('og_title', 'CareerUp Admin')">
    <meta property="og:description" content="@yield('og_description', 'Panel manajemen CareerUp.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-brand-cream font-sans text-slate-800" x-data="{ adminMenuOpen: false }" @keydown.escape.window="adminMenuOpen = false">
    <x-toast />
    <div x-show="adminMenuOpen" x-cloak @click="adminMenuOpen = false" class="fixed inset-0 z-40 bg-black/55 lg:hidden" aria-hidden="true"></div>
    <div class="min-h-screen lg:flex">
        <aside id="admin-navigation" :class="adminMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 flex w-72 shrink-0 flex-col bg-brand-teal px-4 py-6 text-brand-cream transition-transform duration-200 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
            <a href="{{ route('admin.dashboard') }}" class="mb-8 flex items-center gap-3 px-2">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#F4A261] font-serif text-xl font-bold text-[#124143]">C</span>
                <span class="font-serif text-xl font-bold">CareerUp <span class="block font-sans text-[10px] uppercase tracking-widest text-[#F4A261]">Admin</span></span>
            </a>
            <nav id="admin-menu" aria-label="Navigasi admin" class="flex-1 space-y-1.5 text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">Dashboard</a>
                <a href="{{ route('admin.jobs.index', ['type' => 'lowongan']) }}" @if(request()->routeIs('admin.jobs.*') && request('type', request()->route('type')) === 'lowongan') aria-current="page" @endif class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.jobs.*') && request('type', request()->route('type')) === 'lowongan' ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">Lowongan</a>
                <a href="{{ route('admin.jobs.index', ['type' => 'magang']) }}" @if(request()->routeIs('admin.jobs.*') && request('type', request()->route('type')) === 'magang') aria-current="page" @endif class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.jobs.*') && request('type', request()->route('type')) === 'magang' ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">Magang</a>
                <a href="{{ route('admin.trainings.index') }}" @if(request()->routeIs('admin.trainings.*')) aria-current="page" @endif class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.trainings.*') ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">Pelatihan</a>
                <a href="{{ route('admin.applications.index') }}" @if(request()->routeIs('admin.applications.*')) aria-current="page" @endif class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.applications.*') ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">Pelamar</a>
                <a href="{{ route('admin.subscriptions.index') }}" @if(request()->routeIs('admin.subscriptions.*')) aria-current="page" @endif class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.subscriptions.*') ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">Langganan</a>
                <a href="{{ route('admin.users.index') }}" @if(request()->routeIs('admin.users.*')) aria-current="page" @endif class="block rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.users.*') ? 'bg-white/15 font-bold text-white ring-1 ring-white/15' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">Pengguna</a>
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="border-t border-white/10 pt-5">
                @csrf
                <button type="submit" class="w-full rounded-xl px-4 py-3 text-left text-sm font-semibold text-slate-200 hover:bg-white/10 hover:text-white">Keluar</button>
            </form>
        </aside>

        <div class="min-w-0 flex-1">
            <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-slate-200 bg-white px-4 sm:px-6 lg:hidden">
                <button type="button" @click="adminMenuOpen = true" :aria-expanded="adminMenuOpen.toString()" aria-controls="admin-navigation" aria-label="Buka menu admin" class="rounded-lg p-2 text-brand-teal hover:bg-slate-100">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <span class="font-serif font-bold text-[#124143]">CareerUp Admin</span>
            </header>
            <main class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

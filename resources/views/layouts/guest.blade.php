<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CareerUp - Find Your Dream Career')</title>
    <meta name="description" content="@yield('meta_description', 'Temukan lowongan, magang, dan pelatihan untuk mengembangkan karier bersama CareerUp.')">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="CareerUp">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', 'CareerUp - Temukan Peluang Karier')))">
    <meta property="og:description" content="@yield('og_description', 'Temukan lowongan, magang, dan pelatihan untuk mengembangkan karier bersama CareerUp.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F3EE] font-sans text-slate-800 antialiased selection:bg-[#F4A261] selection:text-[#124143]" x-data="{ mobileMenuOpen: false }">
    <x-toast />
    <!-- Navbar Deep Teal Sticky (Matches 01-landing-hero.png) -->
    <header class="sticky top-0 z-50 bg-[#124143] text-[#F5F3EE] shadow-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo CareerUp -->
                <a href="{{ route('landing') }}">
                    <x-logo variant="light" />
                </a>

                <!-- Desktop Navigation Links (Anchor smooth scroll) -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="{{ route('landing') }}#lowongan" class="text-slate-200 hover:text-white transition-colors">
                        Lowongan
                    </a>
                    <a href="{{ route('landing') }}#magang" class="text-slate-200 hover:text-white transition-colors">
                        Magang
                    </a>
                    <a href="{{ route('landing') }}#pelatihan" class="text-slate-200 hover:text-white transition-colors">
                        Pelatihan
                    </a>
                    <a href="{{ route('landing') }}#tentang" class="text-slate-200 hover:text-white transition-colors">
                        Tentang Kami
                    </a>
                </nav>

                <!-- Right Action Button: Masuk / Daftar (Peach pill) -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-brand-peach px-5 py-2.5 text-sm font-bold text-brand-teal-dark shadow-sm hover:bg-amber-300">Daftar</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden">
                    <button
                        type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        :aria-expanded="mobileMenuOpen.toString()"
                        aria-controls="guest-menu"
                        class="p-2 rounded-xl text-slate-200 hover:text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-[#F4A261]"
                        aria-label="Buka menu navigasi">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div
            id="guest-menu"
            x-show="mobileMenuOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="md:hidden bg-[#0d2f31] border-b border-white/10 px-6 py-5 space-y-4">
            <nav class="flex flex-col space-y-3">
                <a @click="mobileMenuOpen = false" href="{{ route('landing') }}#lowongan" class="text-slate-200 hover:text-white text-base font-medium py-1">
                    Lowongan
                </a>
                <a @click="mobileMenuOpen = false" href="{{ route('landing') }}#magang" class="text-slate-200 hover:text-white text-base font-medium py-1">
                    Magang
                </a>
                <a @click="mobileMenuOpen = false" href="{{ route('landing') }}#pelatihan" class="text-slate-200 hover:text-white text-base font-medium py-1">
                    Pelatihan
                </a>
                <a @click="mobileMenuOpen = false" href="{{ route('landing') }}#tentang" class="text-slate-200 hover:text-white text-base font-medium py-1">
                    Tentang Kami
                </a>
            </nav>
            <div class="flex gap-3 border-t border-white/10 pt-4">
                <a href="{{ route('login') }}" class="flex-1 rounded-xl border border-white/30 py-3 text-center text-sm font-semibold text-white hover:bg-white/10">Masuk</a>
                <a href="{{ route('register') }}" class="flex-1 rounded-xl bg-brand-peach py-3 text-center text-sm font-bold text-brand-teal-dark hover:bg-amber-300">Daftar</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer (Matches 06-landing-fitur-dan-footer.png) -->
    <footer class="bg-[#124143] text-slate-300 border-t border-white/10 py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-10">
                <!-- Col 1: Logo & Tagline (5 cols) -->
                <div class="md:col-span-5 space-y-4">
                    <a href="{{ route('landing') }}">
                        <x-logo variant="light" size="lg" />
                    </a>
                    <p class="text-sm leading-6 text-slate-300 max-w-sm">
                        Ruang untuk menemukan peluang karier, mengembangkan keterampilan, dan mengambil langkah berikutnya.
                    </p>
                </div>

                <!-- Col 2: Menu (2 cols) -->
                <div class="md:col-span-2">
                    <h5 class="font-serif text-white font-bold text-base mb-3">Menu</h5>
                    <ul class="space-y-2 text-xs sm:text-sm">
                        <li><a href="{{ route('landing') }}#lowongan" class="hover:text-white transition-colors">Lowongan</a></li>
                        <li><a href="{{ route('landing') }}#magang" class="hover:text-white transition-colors">Magang</a></li>
                        <li><a href="{{ route('landing') }}#pelatihan" class="hover:text-white transition-colors">Pelatihan</a></li>
                        <li><a href="{{ route('landing') }}#tentang" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Col 3: Akun -->
                <div class="md:col-span-2">
                    <h5 class="mb-3 font-serif text-base font-bold text-white">Akun</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-white">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white">Buat akun</a></li>
                        <li><a href="{{ route('landing') }}#faq" class="hover:text-white">Pertanyaan umum</a></li>
                    </ul>
                </div>

                <!-- Col 4: Panduan -->
                <div class="md:col-span-3 space-y-3">
                    <h5 class="font-serif text-base font-bold text-white">Mulai jelajahi</h5>
                    <p class="max-w-xs text-sm leading-6 text-slate-300">
                        Cari pekerjaan, magang, atau kelas yang sesuai dengan minat dan tujuan kariermu.
                    </p>
                    <a href="{{ route('lowongan.index') }}" class="inline-flex rounded-lg font-semibold text-brand-peach hover:text-amber-200">Lihat lowongan <span aria-hidden="true" class="ml-1">→</span></a>
                </div>
            </div>

            <div class="pt-8 border-t border-white/10 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} CareerUp Indonesia. Seluruh hak cipta dilindungi undang-undang.
            </div>
        </div>
    </footer>
</body>
</html>

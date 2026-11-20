<!DOCTYPE html>
<html lang="id" class="h-full bg-[#0d2f31]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Autentikasi') - CareerUp</title>
    <meta name="description" content="@yield('meta_description', 'Masuk atau buat akun CareerUp untuk menemukan peluang karier.')">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="CareerUp">
    <meta property="og:title" content="@yield('og_title', 'CareerUp')">
    <meta property="og:description" content="@yield('og_description', 'Temukan peluang karier bersama CareerUp.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @include('components.font-preload')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#0d2f31] font-sans text-slate-100 antialiased selection:bg-[#F4A261] selection:text-white">
    <div class="min-h-full flex flex-col justify-center py-6 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-4xl">
            <div class="flex h-[min(42rem,calc(100dvh-7rem))] flex-col overflow-hidden rounded-2xl bg-[#124143] shadow-2xl ring-1 ring-white/10 md:flex-row">
                <div class="relative hidden md:flex md:w-1/2 flex-col justify-between overflow-hidden bg-[#0b2f31] p-7 lg:p-9">
                    <div class="relative z-10">
                        <a href="{{ route('landing') }}" class="font-heading text-lg font-bold tracking-tight text-white">
                            Career<span class="text-[#F4A261]">Up</span>
                        </a>
                    </div>

                    <div class="relative z-10 text-[#F5F3EE] space-y-3">
                        <p class="text-sm font-semibold uppercase tracking-[.14em] text-[#F4A261]">
                            Berkembang bersama CareerUp
                        </p>
                        <h2 class="font-heading text-2xl font-bold leading-snug text-white">
                            Temukan peluang yang menggerakkan kariermu.
                        </h2>
                        <p class="text-sm text-slate-200/90 leading-relaxed">
                            Jelajahi pekerjaan, magang, dan pelatihan untuk langkah berikutnya.
                        </p>
                    </div>
                </div>

                <div class="flex min-h-0 w-full flex-col overflow-y-auto p-5 text-[#F5F3EE] sm:p-7 md:w-1/2 lg:p-9">
                    @yield('content')
                    <a href="{{ route('landing') }}" class="mt-4 inline-flex min-h-10 items-center text-xs font-semibold text-[#F4A261] hover:text-white">Kembali ke situs</a>
                </div>
            </div>

            <!-- Footer note -->
            <p class="mt-4 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} CareerUp Indonesia. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</body>
</html>

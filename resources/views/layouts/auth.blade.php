<!DOCTYPE html>
<html lang="id" class="h-full">
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#0d2f31] font-sans text-slate-100 antialiased selection:bg-[#F4A261] selection:text-white">
    <div class="min-h-full flex flex-col justify-center py-6 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-5xl">
            <div class="overflow-hidden rounded-2xl shadow-2xl ring-1 ring-white/10 flex flex-col md:flex-row min-h-[620px] bg-[#124143]">
                <div class="relative hidden md:flex md:w-1/2 flex-col justify-between overflow-hidden bg-[#0b2f31] p-8 lg:p-12">
                    <div class="relative z-10">
                        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-white font-serif text-2xl font-bold tracking-tight">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-[#F4A261] text-[#124143] font-sans font-extrabold text-lg shadow">
                                C
                            </span>
                            <span>Career<span class="text-[#F4A261]">Up</span></span>
                        </a>
                    </div>

                    <div class="relative z-10 text-[#F5F3EE] space-y-3">
                        <p class="text-sm font-semibold uppercase tracking-[.14em] text-[#F4A261]">
                            Berkembang bersama CareerUp
                        </p>
                        <h2 class="font-serif text-2xl font-bold leading-snug text-white lg:text-3xl">
                            Temukan peluang yang menggerakkan kariermu.
                        </h2>
                        <p class="text-sm text-slate-200/90 leading-relaxed">
                            Jelajahi pekerjaan, magang, dan pelatihan untuk langkah berikutnya.
                        </p>
                    </div>
                </div>

                <div class="w-full md:w-1/2 flex flex-col justify-center p-6 sm:p-10 lg:p-12 bg-[#124143] text-[#F5F3EE]">
                    <div class="md:hidden flex items-center justify-between mb-6">
                        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-white font-serif text-xl font-bold">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#F4A261] text-[#124143] font-sans font-extrabold text-base">
                                C
                            </span>
                            <span>Career<span class="text-[#F4A261]">Up</span></span>
                        </a>
                    </div>

                    @yield('content')
                </div>
            </div>

            <!-- Footer note -->
            <p class="mt-6 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} CareerUp Indonesia. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</body>
</html>

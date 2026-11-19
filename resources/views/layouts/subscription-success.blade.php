<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pembayaran Berhasil') - CareerUp</title>
    <meta name="description" content="@yield('meta_description', 'Status langganan CareerUp.')">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="CareerUp">
    <meta property="og:title" content="@yield('og_title', 'CareerUp')">
    <meta property="og:description" content="@yield('og_description', 'Status langganan CareerUp.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[#F5F3EE] font-sans text-slate-800 antialiased">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-2xl">
            <div class="mb-7 flex justify-center">
                <a href="{{ route('pelatihan.index') }}"><x-logo variant="dark" /></a>
            </div>
            @yield('content')
        </div>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat - {{ $enrollment->training->title }}</title>
    <meta name="description" content="Sertifikat penyelesaian pelatihan CareerUp.">
    <meta name="robots" content="noindex, nofollow">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="CareerUp">
    <meta property="og:title" content="Sertifikat - {{ $enrollment->training->title }}">
    <meta property="og:description" content="Sertifikat penyelesaian pelatihan CareerUp.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css'])
    <style>
        @media print { .print-hidden { display: none; } body { background: white; } }
    </style>
</head>
<body class="min-h-screen bg-slate-100 p-6 text-slate-800">
    <main class="mx-auto max-w-4xl border-8 border-double border-[#124143] bg-white p-8 text-center shadow-xl sm:p-16">
        <p class="text-sm font-bold uppercase tracking-[0.3em] text-[#124143]">CareerUp</p>
        <h1 class="mt-8 font-serif text-4xl font-bold text-[#124143]">Sertifikat Penyelesaian</h1>
        <p class="mt-8 text-lg">Diberikan kepada</p>
        <p class="mt-3 font-serif text-3xl font-bold">{{ $enrollment->user->name }}</p>
        <p class="mx-auto mt-6 max-w-2xl leading-relaxed">Atas partisipasi dan penyelesaian pelatihan <strong>{{ $enrollment->training->title }}</strong> yang diselenggarakan CareerUp.</p>
        <p class="mt-10 text-sm text-slate-500">Diterbitkan pada {{ $enrollment->certificate_issued_at?->locale('id')->translatedFormat('d F Y') }}</p>
        <p class="mt-2 text-xs text-slate-500">Nomor sertifikat: CU-{{ str_pad((string) $enrollment->id, 8, '0', STR_PAD_LEFT) }}</p>
        <button type="button" onclick="window.print()" class="print-hidden mt-10 rounded-xl bg-[#124143] px-6 py-3 font-bold text-white">Cetak sertifikat</button>
    </main>
</body>
</html>

@extends('layouts.guest')

@section('title', 'Error '.trim($__env->yieldContent('code')).' - CareerUp')

@section('content')
<section class="mx-auto flex min-h-[65vh] max-w-3xl flex-col items-center justify-center px-4 py-16 text-center">
    <p class="font-heading text-7xl font-bold text-brand-teal">@yield('code')</p>
    <h1 class="mt-4 font-heading text-3xl font-bold text-brand-teal">@yield('message')</h1>
    <p class="mt-3 max-w-xl text-slate-600">Halaman yang kamu cari tidak dapat ditampilkan saat ini.</p>
    <a href="{{ route('landing') }}" class="mt-8 inline-flex rounded-xl bg-brand-teal px-5 py-3 font-semibold text-white hover:bg-brand-teal-dark">Kembali ke CareerUp</a>
</section>
@endsection

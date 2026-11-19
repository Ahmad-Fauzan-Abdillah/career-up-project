@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi')

@section('content')
<div class="space-y-5">
    <div><h1 class="font-serif text-3xl font-bold text-white">Lupa kata sandi?</h1><p class="mt-2 text-sm text-[#F5F3EE]/80">Kami akan mengirim tautan untuk mengatur ulang kata sandi ke email Anda.</p></div>
    @if(session('status'))<p role="status" class="rounded-xl bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>@endif
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">@csrf
        <label class="block text-sm font-semibold text-white" for="email">Email</label>
        <input id="email" name="email" type="email" required value="{{ old('email') }}" class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white">
        @error('email')<p class="text-sm text-rose-300">{{ $message }}</p>@enderror
        <button class="w-full rounded-xl bg-[#F5F3EE] px-4 py-3 font-bold text-[#124143]">Kirim link reset</button>
    </form>
    <a href="{{ route('login') }}" class="block text-center text-sm text-[#F4A261] underline">Kembali ke login</a>
</div>
@endsection

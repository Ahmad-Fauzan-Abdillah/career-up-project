@extends('layouts.auth')

@section('title', 'Atur Ulang Kata Sandi')

@section('content')
<div class="space-y-5">
    <div><h1 class="font-heading text-3xl font-bold text-white">Atur ulang kata sandi</h1><p class="mt-2 text-sm text-[#F5F3EE]/80">Masukkan kata sandi baru untuk akun Anda.</p></div>
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">@csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label class="block text-sm font-semibold text-white" for="email">Email</label>
        <input id="email" name="email" type="email" required value="{{ old('email', $email) }}" class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white">
        @error('email')<p class="text-sm text-rose-300">{{ $message }}</p>@enderror
        <label class="block text-sm font-semibold text-white" for="password">Kata sandi baru</label>
        <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white">
        @error('password')<p class="text-sm text-rose-300">{{ $message }}</p>@enderror
        <label class="block text-sm font-semibold text-white" for="password_confirmation">Ulangi kata sandi</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white">
        <button class="w-full rounded-xl bg-[#F5F3EE] px-4 py-3 font-bold text-[#124143]">Simpan kata sandi</button>
    </form>
</div>
@endsection

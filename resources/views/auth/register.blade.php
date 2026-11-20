@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
<div x-data="{
    role: '{{ old('role', 'pengguna') }}',
    showPassword: false
}">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="font-heading text-3xl font-bold text-white tracking-tight">Daftar</h1>
        <p class="mt-1 text-sm text-[#F5F3EE]/80">Buat akun baru</p>
    </div>

    <!-- Role Tab Selector -->
    <div class="mb-6">
        <label class="block text-xs font-semibold uppercase tracking-wider text-[#F5F3EE]/70 mb-2">
            Pilih Peran
        </label>
        <div class="grid grid-cols-2 p-1 rounded-xl bg-black/25 border border-white/10 backdrop-blur-sm">
            <button
                type="button"
                @click="role = 'pengguna'"
                :class="role === 'pengguna' ? 'bg-[#F5F3EE] text-[#124143] shadow-md font-semibold' : 'text-[#F5F3EE]/70 hover:text-white font-medium'"
                class="py-2.5 px-4 rounded-lg text-sm transition-[opacity,transform] duration-200 text-center flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Pengguna
            </button>
            <button
                type="button"
                @click="role = 'admin'"
                :class="role === 'admin' ? 'bg-[#F5F3EE] text-[#124143] shadow-md font-semibold' : 'text-[#F5F3EE]/70 hover:text-white font-medium'"
                class="py-2.5 px-4 rounded-lg text-sm transition-[opacity,transform] duration-200 text-center flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Admin
            </button>
        </div>
        @error('role')
            <p class="mt-2 text-xs text-rose-300 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 inline shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="role" :value="role">

        <!-- Nama -->
        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-[#F5F3EE]/80 mb-1.5">
                Nama Lengkap
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    placeholder="Nama lengkap Anda"
                    class="block w-full rounded-xl bg-white/10 border @error('name') border-rose-400 ring-1 ring-rose-400 @else border-white/15 @enderror pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-400/70 focus:outline-none focus:ring-2 focus:ring-[#F4A261] focus:border-transparent transition-colors">
            </div>
            @error('name')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-[#F5F3EE]/80 mb-1.5">
                Alamat Email
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                    </svg>
                </div>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    placeholder="nama@email.com"
                    class="block w-full rounded-xl bg-white/10 border @error('email') border-rose-400 ring-1 ring-rose-400 @else border-white/15 @enderror pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-400/70 focus:outline-none focus:ring-2 focus:ring-[#F4A261] focus:border-transparent transition-colors">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-[#F5F3EE]/80 mb-1.5">
                Kata Sandi
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input
                    id="password"
                    name="password"
                    :type="showPassword ? 'text' : 'password'"
                    required
                    autocomplete="new-password"
                    placeholder="Min. 8 karakter (huruf, angka, simbol)"
                    class="block w-full rounded-xl bg-white/10 border @error('password') border-rose-400 ring-1 ring-rose-400 @else border-white/15 @enderror pl-10 pr-11 py-2.5 text-sm text-white placeholder-slate-400/70 focus:outline-none focus:ring-2 focus:ring-[#F4A261] focus:border-transparent transition-colors">
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-300 hover:text-white transition-colors"
                    aria-label="Toggle password visibility">
                    <svg x-show="!showPassword" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <p class="mt-1 text-[11px] text-[#F5F3EE]/60">Minimal 8 karakter, kombinasi huruf besar & kecil, angka, dan simbol.</p>
            @error('password')
                <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Submit -->
        <div class="pt-2">
            <button
                type="submit"
                class="w-full flex justify-center items-center py-3 px-4 rounded-xl shadow-lg bg-[#F5F3EE] hover:bg-white text-[#124143] text-sm font-bold tracking-wide uppercase transition-[opacity,transform] duration-200 transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F4A261] focus:ring-offset-[#124143]">
                Daftar
            </button>
        </div>
    </form>

    <!-- Switch to Login -->
    <div class="mt-6 text-center text-sm text-[#F5F3EE]/80">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-[#F4A261] hover:text-[#f8b881] hover:underline underline-offset-4 ml-1 transition-colors">
            Masuk
        </a>
    </div>
</div>
@endsection

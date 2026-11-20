@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="mx-auto max-w-3xl space-y-4">
    <x-page-header title="Ubah kata sandi" description="Gunakan kata sandi yang kuat untuk menjaga keamanan akun." eyebrow="Keamanan akun">
        <x-slot:actions><x-button as="a" :href="route('pengaturan.index')" variant="secondary">Pengaturan</x-button></x-slot:actions>
    </x-page-header>

    <form method="POST" action="{{ route('pengaturan.password.update') }}" class="cu-card grid gap-4 p-4 sm:grid-cols-2">
        @csrf
        @method('PATCH')
        <div class="sm:col-span-2">
            <label for="current_password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password Saat Ini</label>
            <input id="current_password" name="current_password" type="password" autocomplete="current-password" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#124143] focus:outline-none focus:ring-2 focus:ring-[#124143]/20">
            @error('current_password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="new_password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password Baru</label>
            <input id="new_password" name="new_password" type="password" autocomplete="new-password" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#124143] focus:outline-none focus:ring-2 focus:ring-[#124143]/20">
            @error('new_password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="new_password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Konfirmasi Password Baru</label>
            <input id="new_password_confirmation" name="new_password_confirmation" type="password" autocomplete="new-password" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#124143] focus:outline-none focus:ring-2 focus:ring-[#124143]/20">
        </div>

        <section class="rounded-xl border border-slate-200 bg-[#F5F3EE] p-4 sm:col-span-2" aria-labelledby="password-criteria-heading">
            <h2 id="password-criteria-heading" class="text-sm font-bold text-[#124143]">Kriteria Password</h2>
            <ul class="mt-3 space-y-2 text-sm">
                <li data-password-criterion="length" class="flex items-center gap-2 text-slate-500"><span aria-hidden="true">○</span><span>Minimal 8 karakter</span></li>
                <li data-password-criterion="uppercase" class="flex items-center gap-2 text-slate-500"><span aria-hidden="true">○</span><span>Memiliki huruf besar</span></li>
                <li data-password-criterion="lowercase" class="flex items-center gap-2 text-slate-500"><span aria-hidden="true">○</span><span>Memiliki huruf kecil</span></li>
                <li data-password-criterion="number" class="flex items-center gap-2 text-slate-500"><span aria-hidden="true">○</span><span>Memiliki angka</span></li>
                <li data-password-criterion="symbol" class="flex items-center gap-2 text-slate-500"><span aria-hidden="true">○</span><span>Memiliki simbol</span></li>
            </ul>
        </section>
        <div class="sm:col-span-2"><x-button type="submit" variant="peach" size="lg">Simpan Password</x-button></div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const password = document.getElementById('new_password');
        const criteria = {
            length: value => value.length >= 8,
            uppercase: value => /[A-Z]/.test(value),
            lowercase: value => /[a-z]/.test(value),
            number: value => /[0-9]/.test(value),
            symbol: value => /[^A-Za-z0-9]/.test(value),
        };

        password.addEventListener('input', () => {
            Object.entries(criteria).forEach(([name, check]) => {
                const item = document.querySelector(`[data-password-criterion="${name}"]`);
                const icon = item.querySelector('span');
                const valid = check(password.value);
                item.classList.toggle('text-emerald-700', valid);
                item.classList.toggle('text-slate-500', !valid);
                icon.textContent = valid ? '✓' : '○';
            });
        });
    });
</script>
@endsection

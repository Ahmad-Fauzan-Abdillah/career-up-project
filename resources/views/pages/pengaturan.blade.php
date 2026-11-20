@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="mx-auto max-w-4xl space-y-5">
    <x-page-header title="Pengaturan" description="Kelola akun dan preferensi Anda" eyebrow="Akun" />

    <div class="grid gap-4 lg:grid-cols-2">
        <section class="cu-card p-4">
            <h2 class="mb-3 font-heading text-lg font-bold text-brand-teal">Keamanan</h2>
            <div class="divide-y divide-slate-200">
                <a href="{{ route('pengaturan.password') }}" class="block py-3 transition-colors hover:text-brand-teal">
                    <span class="block font-semibold text-[#124143]">Ubah Password</span>
                    <span class="mt-1 block text-sm text-slate-500">Perbarui kata sandi akun Anda</span>
                </a>
                <a href="{{ route('pengaturan.email') }}" class="block py-3 transition-colors hover:text-brand-teal">
                    <span class="block font-semibold text-[#124143]">Verifikasi Email</span>
                    <span class="mt-1 block text-sm text-slate-500">{{ $user->email }}</span>
                </a>
            </div>
        </section>
        <section class="cu-card p-4">
            <h2 class="mb-3 font-heading text-lg font-bold text-brand-teal">Preferensi</h2>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-semibold text-[#124143]">Notifikasi</h3>
                    <p class="mt-1 text-sm text-slate-500">Atur notifikasi CareerUp untuk akun Anda</p>
                    <p id="notification-error" class="mt-2 hidden text-sm text-rose-600" role="alert"></p>
                </div>
                <label class="inline-flex shrink-0 cursor-pointer items-center gap-3">
                    <span id="notification-label" class="text-sm font-semibold text-slate-600">{{ $user->is_notification_active ? 'On' : 'Off' }}</span>
                    <input id="notification-toggle" type="checkbox" class="peer sr-only" @checked($user->is_notification_active)
                           aria-label="Aktifkan notifikasi">
                    <span class="relative h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-[#124143] peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#124143]/20 after:absolute after:left-1 after:top-1 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-transform peer-checked:after:translate-x-5"></span>
                </label>
            </div>
        </section>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('notification-toggle');
        const label = document.getElementById('notification-label');
        const error = document.getElementById('notification-error');
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        toggle.addEventListener('change', async () => {
            const requestedValue = toggle.checked;
            toggle.disabled = true;
            error.classList.add('hidden');

            try {
                const response = await fetch(@json(route('pengaturan.notifikasi.update')), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ is_notification_active: requestedValue }),
                });

                if (!response.ok) {
                    throw new Error('Preferensi notifikasi gagal disimpan. Silakan coba kembali.');
                }

                const result = await response.json();
                toggle.checked = result.is_notification_active;
                label.textContent = result.is_notification_active ? 'On' : 'Off';
            } catch (requestError) {
                toggle.checked = !requestedValue;
                error.textContent = requestError.message || 'Terjadi kesalahan saat menyimpan preferensi.';
                error.classList.remove('hidden');
            } finally {
                toggle.disabled = false;
            }
        });
    });
</script>
@endsection

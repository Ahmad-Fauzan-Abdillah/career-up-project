@extends('layouts.app')

@section('title', 'Pelatihan')

@section('content')
<div class="space-y-6">
    <x-page-header
        title="Pelatihan"
        description="Pilih kelas yang sesuai dengan tujuan belajar dan jadwalmu."
        eyebrow="Belajar dan berkembang" />

    @if(session('subscription_active_until'))
        <p role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-950">
            Langganan aktif sampai {{ \Illuminate\Support\Carbon::parse(session('subscription_active_until'))->locale('id')->translatedFormat('d F Y') }}.
        </p>
    @endif

    @if($trainings->isNotEmpty())
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach($trainings as $training)
                <x-training-card :training="$training" />
            @endforeach
        </div>
    @else
        <x-empty-state>Belum ada pelatihan yang sesuai. Pantau terus ya!</x-empty-state>
    @endif

    <x-pagination :paginator="$trainings" />
</div>
@endsection

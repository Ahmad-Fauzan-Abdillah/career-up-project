@props(['lines' => 1])

@if($lines > 1)
    <div {{ $attributes->merge(['class' => 'space-y-3']) }} aria-hidden="true">
        @for($line = 0; $line < $lines; $line++)
            <div class="cu-skeleton h-4 {{ $line === $lines - 1 ? 'w-2/3' : 'w-full' }}"></div>
        @endfor
    </div>
@else
    <div {{ $attributes->merge(['class' => 'cu-skeleton']) }} aria-hidden="true"></div>
@endif

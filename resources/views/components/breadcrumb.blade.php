@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-5">
    <ol class="flex flex-wrap items-center gap-2 text-sm text-slate-600">
        @foreach($items as $index => $item)
            <li class="flex items-center gap-2">
                @if($index > 0)
                    <span aria-hidden="true" class="text-slate-400">/</span>
                @endif
                @if(!empty($item['url']) && $index < count($items) - 1)
                    <a href="{{ $item['url'] }}" class="rounded-sm hover:text-brand-teal hover:underline">{{ $item['label'] }}</a>
                @else
                    <span @if($index === count($items) - 1) aria-current="page" class="font-semibold text-brand-teal" @endif>{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

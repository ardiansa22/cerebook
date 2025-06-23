<div class="breadcrumb-wrapper">
    <nav aria-label="breadcrumb" class="my-3">
        <ol class="breadcrumb bg-light px-3 py-2 rounded shadow-sm">
            @foreach ($items as $index => $item)
                @if (isset($item['route']) && $index !== count($items) - 1)
                    <li class="breadcrumb-item">
                        <a href="{{ $item['route'] }}" wire:navigate>{{ $item['label'] }}</a>
                    </li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $item['label'] }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
</div>

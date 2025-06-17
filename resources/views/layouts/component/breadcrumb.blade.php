@props(['breadcrumbs' => []])

<nav class="text-sm text-gray-600 mb-4" aria-label="Breadcrumb">
    <ol class="list-reset flex items-center space-x-2">
        @foreach($breadcrumbs as $index => $breadcrumb)
            @if(isset($breadcrumb['url']) && $breadcrumb['url'])
                <li>
                    <a href="{{ $breadcrumb['url'] }}" class="text-gray-500 hover:text-blue-600">
                        {{ $breadcrumb['label'] }}
                    </a>
                </li>
                @if(!$loop->last)
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6 6L14 10L6 14V6Z" />
                        </svg>
                    </li>
                @endif
            @else
                <li>
                    <span class="text-gray-700 font-medium">{{ $breadcrumb['label'] }}</span>
                </li>
            @endif
        @endforeach
    </ol>
</nav>

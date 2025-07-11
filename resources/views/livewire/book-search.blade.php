<div>
    <form wire:submit.prevent="goToResult">
        <input
            type="text"
            class="form-control"
            placeholder="Search Your Favorite Book.."
            wire:model.live.debounce.300ms="query"
            wire:keydown.enter.prevent="goToResult" 
        >

    </form>

    @if(strlen($query) >= 2)
        <ul class="list-group mt-2">
            @forelse ($books as $book)
                <li class="list-group-item">
                    @php
                        $highlighted = preg_replace(
                            '/(' . preg_quote($query, '/') . ')/i',
                            '<strong>$1</strong>',
                            $book->title
                        );
                    @endphp
                    <a href="{{ route('book.show', $book->id) }}" wire:navigate class="text-dark text-decoration-none">
                        {!! $highlighted !!}
                    </a>
                </li>
            @empty
                <li class="list-group-item text-muted">Buku tidak ditemukan.</li>
            @endforelse
        </ul>
    @endif

</div>

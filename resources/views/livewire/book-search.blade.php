<div>
    <form wire:submit.prevent="goToResult">
        <input
            type="text"
            class="form-control"
            placeholder="Search Your Favorite Book.."
            wire:model="query"
            wire:keydown.enter.prevent="goToResult" 
        >
    </form>

    @if(strlen($query) >= 2)
        <ul class="list-group mt-2">
            @forelse ($books as $book)
                <li class="list-group-item">
                    <a href="{{ route('book.show', $book->id) }}" wire:navigate>
                        {{ $book->title }}
                    </a>
                </li>
            @empty
                <li class="list-group-item text-muted">Buku tidak ditemukan.</li>
            @endforelse
        </ul>
    @endif
</div>

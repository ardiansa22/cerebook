<div>
    <input
        type="text"
        class="form-control"
        placeholder="Cari Buku Favoritmu"
        wire:model.live.debounce.300ms="query"
    >
    
    @if(strlen($query) >= 2) <!-- Ubah kondisi ini -->
        <ul class="list-group mt-2">
            @forelse ($books as $book)
                <li class="list-group-item">
                    <a href="{{ route('book.show', $book->id) }}">{{ $book->title }}</a>
                </li>
            @empty
                <li class="list-group-item text-muted">Buku tidak ditemukan.</li>
            @endforelse
        </ul>
    @endif
</div>
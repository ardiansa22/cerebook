<div class="container mt-3 detail">
    @include('layouts.search')
    <!-- <h5 class="fw-bold mb-3">Kategori: {{ $categori->name }}</h5> -->
         @livewire('customer.breadcrumb-component')
    @if ($books->isEmpty())
        <p class="text-muted">Belum ada buku di kategori ini.</p>
    @endif

    <div class="row row-cols-1 row-cols-md-2 g-4 mt-3">
        @foreach ($books as $book)
         <a href="{{ route('book.show', $book->id) }}"
            wire:navigate
            class="text-decoration-none text-dark">
        <div class="col">
         
            

                <div class="card detail-card position-relative">
                    <img src="{{ asset('storage/books/' . $book->image) }}" class="detail-img" alt="{{ $book->title }}">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $book->title }}</h5>
                        <p class="card-text text-muted">
                            <i class="fas fa-star text-warning"></i> {{ $book->rating ?? '4.0' }}/5 
                            ({{ $book->reviews_count ?? '0' }} Review) • {{ $book->location ?? 'Tidak Diketahui' }}
                        </p>
                        <p class="fw-bold">Rp {{ number_format($book->rent_price, 0, ',', '.') }}</p>
                    </div>
                </div>
        </div>
        </a>
        @endforeach
    </div>
</div>

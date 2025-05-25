<div class="container mt-3 dashboard">
    @include('layouts.search')
    <div class="container mt-3">
        <div class="card greeting-card shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold">@auth
                    Halo, {{ auth()->user()->name }}
                @else
                    Selamat datang!
                @endauth</h5>
                <div class="weather-info d-flex align-items-center">
                    <i class="fas fa-cloud-sun fa-2x me-2 text-warning"></i>
                    <div>
                        <p class="mb-0 fw-semibold">🌍 Bandung</p>
                        <p class="mb-0 text-muted">🌞 28°C, Cerah</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Kategori -->
        <div class="d-flex justify-content-around mt-3 flex-wrap">
        @foreach ($categories->take(4) as $category)
        <div class="text-center">
            <a href="{{ route('categori.book', $category->name) }}" class="text-decoration-none text-dark">
                <div class="category-icon">
                    <img src="{{ asset('img/buku.png') }}" alt="{{ $category->name }}" class="category-img">
                </div>
                <p>{{ $category->name }}</p>
            </a>
        </div>
        @endforeach

        @if ($categories->count() > 4)
        <div class="text-center">
            <a href="#" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#iconModal">
                <div class="category-icon"><i class="fas fa-ellipsis-h"></i></div>
                <p>Semua</p>
            </a>
        </div>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="iconModal" tabindex="-1" aria-labelledby="iconModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="fw-bold modal-title" id="iconModalLabel">Pilih Kategori</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap justify-content-around">
                        @foreach ($categories->slice(4) as $category)
                        <div class="text-center m-2">
                            <a href="{{ route('categori.book', $category->name) }}" class="text-decoration-none text-dark">
                                <div class="category-icon">
                                    <img src="{{ asset('img/buku.png') }}" alt="{{ $category->name }}" class="category-img">
                                </div>
                                <p>{{ $category->name }}</p>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Swiper Carousel -->
    <div class="swiper-container mb-3">
    <h6 class="fw-bold">Buku Populer untuk Anda</h6>
    <div class="swiper-wrapper">
        @foreach ($books as $book)
        <div class="swiper-slide">
            <a href="{{ route('book.show', $book->id) }}" class="text-decoration-none text-dark">
                <div class="card p-2 h-100">
                    <img src="{{ asset('storage/books/' . $book->image) }}" class="card-img-top" alt="Gambar {{ $book->title }}">
                    <div class="card-body text-start">
                        <p class="text-muted mb-1" style="font-size: 11px;">Cerebook</p>
                        <h6 class="card-title mb-1">{{ $book->title }}</h6>
                        <p class="text-muted mb-1">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
</div>
<div class="container mt-3">
    @include('layouts.search')
    <div class="row">
        <!-- Kolom Kiri: Gambar -->
        <div class="col-md-5 text-center mt-3">
            <img 
                src="{{ asset('storage/books/' . $book->image) }}" 
                class="img-fluid rounded shadow-sm main-image" 
                alt="{{ $book->title }}"
                style="max-height: 500px; object-fit: cover;"
                 onclick="showModal('{{ asset('storage/books/' . $book->image) }}')"
                                style="cursor: pointer;"
            >
            <div class="mt-2">
                <!-- Thumbnail (bisa ditambahkan banyak nanti) -->
                <img 
                    src="{{ asset('storage/books/' . $book->image) }}" 
                    class="img-thumbnail" 
                    style="width: 70px; height: 100px; object-fit: cover;"
                >
            </div>
        </div>
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail -->
        <div class="col-md-7 mt-3">
            <h5 class="fw-bold">{{ $book->title }}</h5>
            <p class="text-muted">{{ $book->transactions->count() }} Terjual</p>
            
            <div class="my-3">
                <span class="text-danger fs-4 fw-bold">Rp{{ number_format($book->price - ($book->price * 0.25), 0, ',', '.') }}</span>
                <span class="text-muted text-decoration-line-through ms-2">Rp{{ number_format($book->price, 0, ',', '.') }}</span>
                <span class="badge bg-danger ms-2">25%</span>
            </div>

            <div class="alert alert-light border d-flex align-items-center">
                <i class="bi bi-truck me-2"></i>
                <small class="text-muted">Bebas biaya pengiriman jika ambil di toko terdekat</small>
            </div>

            <div class="my-2">
                <p class="mb-1 fw-semibold">Format Buku</p>
                <button class="btn btn-outline-dark btn-sm rounded-pill">Soft Cover</button>
            </div>

            <div class="my-2">
                <p class="mb-1 fw-semibold">Toko</p>
                <select class="form-select form-select-sm w-75">
                    <option>CereBrum Banjaran</option>
                </select>
            </div>

           <button class="btn btn-prim rounded-pill shadow-sm px-4 py-2"
                    wire:click="buy"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Beli Sekarang</span>
                <span wire:loading>Memproses...</span>
            </button>

           @php
                $words = explode(' ', $book->description);
                $shortDescription = implode(' ', array_slice($words, 0, 30)); // 30 kata agar mirip tampilan
                $isLong = count($words) > 30;
            @endphp

            <div class="card border-0 shadow-sm p-3 mt-3 mb-5">
                <h6 class="fw-bold mb-2">Deskripsi</h6>
                <div class="bg-light p-3 rounded">
                    <p class="mb-0 text-muted" style="text-align: justify;">
                        {{ $shortDescription }}@if($isLong)... @endif
                    </p>

                    @if($isLong)
                        <div class="mt-2">
                            <a href="#" class="text-decoration-none fw-semibold small" wire:click.prevent="$set('showModal', true)">
                                Baca Selengkapnya <i class="bi bi-chevron-down small"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
           @if ($showModal)
                <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index:1050;">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Deskripsi Lengkap</h5>
                                <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                            </div>
                            <div class="modal-body">
                                <p style="text-align: justify;">{{ $book->description }}</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" wire:click="$set('showModal', false)">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

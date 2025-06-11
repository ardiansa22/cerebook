<div class="container mt-3">
    
    @include('layouts.search')
    <div class="row">
        <!-- Kolom Kiri: Gambar -->
        <div class="col-md-5 text-center mt-3">
            <img 
                src="{{ asset('storage/books/' . $book->image) }}" 
                class="img-fluid rounded shadow-sm main-image" 
                alt="{{ $book->title }}"
                style="max-height: 500px; object-fit: cover; cursor: pointer;"
                onclick="showModal('{{ asset('storage/books/' . $book->image) }}')"
            >
            <div class="mt-2">
                <!-- Thumbnail (bisa ditambahkan banyak nanti) -->
                <img 
                    src="{{ asset('storage/books/' . $book->image) }}" 
                    class="img-thumbnail" 
                    style="width: 70px; height: 100px; object-fit: cover;"
                    alt="Thumbnail {{ $book->title }}"
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
                        <img id="modalImage" src="" class="img-fluid rounded shadow" alt="Preview Image">
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail -->
        <div class="col-md-7 mt-3">
            <h5 class="fw-bold">{{ $book->title }}</h5>

            <div class="my-3">
                <span class="text-danger fs-4 fw-bold">Rp{{ number_format($book->price, 0, ',', '.') }}</span>
                <span class="text-muted ms-2">/day</span>
            </div>

            <div class="alert alert-light border d-flex align-items-center">
                <i class="bi bi-info-circle me-2"></i>
                <small class="text-muted">Denda keterlambatan Rp5.000/day</small>
            </div>

            <div class="my-2">
                <p class="mb-1 fw-semibold">Durasi Sewa</p>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small">Tanggal Sewa</label>
                        <input type="date" wire:model="rental_date" class="form-control form-control-sm">
                        @error('rental_date') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Tanggal Kembali</label>
                        <input type="date" wire:model="return_date" class="form-control form-control-sm">
                        @error('return_date') <span class="text-danger small" wire:click="updatedReturnDate">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="my-2">
                <p class="mb-1 fw-semibold">Jumlah</p>
                <div class="input-group" style="max-width: 150px;">
                    <button class="btn btn-outline-secondary" wire:click="decrement" type="button">-</button>
                    <input type="text" class="form-control text-center" wire:model="quantity" readonly>
                    <button class="btn btn-outline-secondary" wire:click="increment" type="button">+</button>
                </div>
                <small class="text-muted">Stok tersedia: {{ $book->stock }}</small>
            </div>
            @php
                use Carbon\Carbon;

                $start = Carbon::parse($rental_date);
                $end = Carbon::parse($return_date);
                $days = $start->diffInDays($end) > 0 ? $start->diffInDays($end) : 1;

                $subtotal = $book->price * $quantity * $days;
            @endphp



            @auth
                @if($book->stock > 0)
                    <button class="btn btn-primary shadow-sm px-4 py-2"
                            wire:click="OpenRentalModal"
                            wire:loading.attr="disabled" type="button">
                        <span wire:loading.remove>Sewa</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                @else
                    <button class="btn btn-secondary shadow-sm px-4 py-2" disabled type="button">
                        Stok Habis
                    </button>
                @endif
            @endauth

            @guest
                <a href="{{ route('login') }}" class="btn btn-primary shadow-sm px-4 py-2">
                    Masuk untuk Sewa
                </a>
            @endguest

            {{-- Modal Konfirmasi Penyewaan --}}
            <!-- Modal Konfirmasi Penyewaan -->
            @if ($showRentalModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index:1050;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Penyewaan</h5>
                    <button type="button" class="btn-close" wire:click="$set('showRentalModal', false)"></button>
                </div>

                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/books/' . $book->image) }}" 
                             class="img-fluid rounded mb-2" 
                             style="max-height: 200px; object-fit: cover;" 
                             alt="Cover Buku">

                        <h5 class="fw-bold">Rp{{ number_format($totalPrice, 0, ',', '.') }}</h5>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 text-center"><strong>{{ $book->title }}</strong></p>
                        
                        <div class="row small text-muted">
                            <div class="col-6">Sewa</div>
                            <div class="col-6 text-end">{{ \Carbon\Carbon::parse($rental_date)->format('d M Y') }}</div>

                            <div class="col-6">Kembali</div>
                            <div class="col-6 text-end">{{ \Carbon\Carbon::parse($return_date)->format('d M Y') }}</div>

                            <div class="col-6">Jumlah</div>
                            <div class="col-6 text-end">{{ $quantity }} buku</div>

                            <div class="col-6">Harga per Buku</div>
                            <div class="col-6 text-end">Rp{{ number_format($book->price, 0, ',', '.') }}</div>

                            <div class="col-6">Total Hari Sewa</div>
                            <div class="col-6 text-end">{{ $days }} hari</div>

                            <div class="col-6">Subtotal</div>
                            <div class="col-6 text-end">Rp{{ number_format($subtotal, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label small fw-semibold">Metode Pembayaran</label>
                        <select id="paymentMethod" wire:model="paymentMethod" class="form-select form-select-sm">
                            <option value="transfer">Transfer Bank</option>
                            <option value="cash">Tunai</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer d-flex gap-2">
                    @auth
                        @if($book->stock > 0)
                            <button class="btn btn-primary shadow-sm py-2 flex-grow-1"
                                    wire:click="rental"
                                    wire:loading.attr="disabled" type="button">
                                <span wire:loading.remove>Rent Now</span>
                                <span wire:loading>Memproses...</span>
                            </button>

                            <button class="btn btn-danger shadow-sm py-2" 
                                    wire:click="addToCart" 
                                    wire:loading.attr="disabled" type="button">
                                <span wire:loading.remove>+ Keranjang</span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        @else
                            <button class="btn btn-secondary shadow-sm px-4 py-2" disabled type="button">
                                Stok Habis
                            </button>
                        @endif
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary shadow-sm px-4 py-2">
                            Masuk untuk Sewa
                        </a>
                    @endguest
                </div>

            </div>
        </div>
    </div>
@endif



            @php
                $words = explode(' ', $book->description);
                $shortDescription = implode(' ', array_slice($words, 0, 30));
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
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Deskripsi Lengkap</h5>
                                <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                            </div>
                            <div class="modal-body" style="white-space: pre-line; text-align: justify;">
                                {{ $book->description }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>





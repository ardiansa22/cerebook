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
                        @error('return_date') <span class="text-danger small">{{ $message }}</span> @enderror
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

            <div class="my-3 bg-light p-3 rounded">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>Rp{{ number_format($book->price * $quantity, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total:</span>
                    <span class="text-danger">Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
            </div>

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
            @if ($showRentalModal)
            <div class="modal fade show d-block" tabindex="-1" 
                 style="background-color: rgba(0,0,0,0.5); z-index:1050;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Penyewaan</h5>
                            <button type="button" class="btn-close" 
                                    wire:click="$set('showRentalModal', false)">
                            </button>
                        </div>

                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/books/' . $book->image) }}" 
                                 class="img-fluid rounded mb-3" 
                                 style="max-height: 200px; object-fit: cover;" 
                                 alt="Cover Buku">

                            <h5 class="fw-bold mb-3">Rp{{ number_format($totalPrice, 0, ',', '.') }}</h5>

                            <div class="mb-3">
                                <p class="mb-1"><strong>{{ $book->title }}</strong></p>
                                <p class="small text-muted mb-0">
                                    Sewa: {{ \Carbon\Carbon::parse($rental_date)->format('d M Y') }}<br>
                                    Kembali: {{ \Carbon\Carbon::parse($return_date)->format('d M Y') }}<br>
                                    Jumlah: {{ $quantity }} buku
                                </p>
                            </div>

                            <div class="mb-3 text-start">
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

<script>
    function showModal(imageUrl) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageUrl;
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }
</script>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('swal:success', (data) => {
            console.log('Global Event diterima:', data);
            Swal.fire({
                title: data[0].title,
                text: data[0].text,
                showConfirmButton: false,  // hilangkan tombol OK
                timer: 2000,               // tampil 2 detik (2000 ms)
                timerProgressBar: true,    // progress bar (optional)
            });
        });
    });
</script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
document.addEventListener('livewire:init', () => {
    Livewire.on('midtrans:pay', (payload) => {
        const data = payload[0];
        const snapToken = data.snapToken;
        
        // Hapus opsi embedId dan gunakan konfigurasi minimal
        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                console.log('success', result);
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran berhasil!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                });
            },
            onPending: function(result) {
                console.log('pending', result);
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu pembayaran Anda!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                });
            },
            onError: function(result) {
                console.log('error', result);
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran gagal!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                });
            },
            onClose: function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Popup ditutup sebelum pembayaran selesai!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                });
            }
        });
    });
});
</script>



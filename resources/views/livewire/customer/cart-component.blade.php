<div class="container mt-3">
    @include('layouts.search')
    @if($cartItems->isEmpty())
        <div class="alert alert-info mt-3   ">Keranjang Anda kosong</div>
    @else
        @php $grandTotal = 0; @endphp
        <div class="card shadow-sm mt-3">
    <div class="card-body p-0">
        <div style="max-height: 400px; overflow-y: auto; padding: 1rem;">
            @foreach ($cartItems as $item)
                @php
                    $days = \Carbon\Carbon::parse($item->rental_date)
                        ->diffInDays(\Carbon\Carbon::parse($item->return_date)) + 1;
                    $subtotal = $item->book->price * $item->quantity * $days;
                    if(in_array($item->id, $selectedItems)) {
                        $grandTotal += $subtotal;
                    }
                @endphp
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input 
                                        class="form-check-input mt-2" 
                                        type="checkbox" 
                                        value="{{ $item->id }}" 
                                        wire:model.live="selectedItems"
                                    >
                                </div>
                                <div class="d-flex">
                                    <img src="{{ asset('storage/books/' . $item->book->image) }}" 
                                         alt="{{ $item->book->title }}" 
                                         class="rounded" 
                                         width="80" 
                                         height="80"
                                         style="object-fit: cover;">
                                    <div class="ms-3">
                                        <h6 class="mb-1">{{ $item->book->title }}</h6>
                                        <p class="mb-0 text-muted small">
                                            {{ $item->quantity }} × Rp{{ number_format($item->book->price, 0, ',', '.') }} × {{ $days }} hari
                                        </p>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($item->rental_date)->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}
                                        </small>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label small mb-1">Jumlah:</label>
                                        <input type="number"
                                            min="1"
                                            class="form-control form-control-sm"
                                            wire:model.lazy="quantities.{{ $item->id }}"
                                            wire:change="updateQuantity({{ $item->id }}, $event.target.value)">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button 
                                    wire:click="removeFromCart({{ $item->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus item ini?"
                                    class="btn btn-sm btn-outline-danger"
                                >
                                    d
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

        <!-- Form Metode Pembayaran & Total -->
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold">Total Pembayaran:</span>
                    <span class="fw-bold fs-5">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>

                <button 
                    type="button"
                    class="btn btn-success w-100"
                    data-bs-toggle="modal" 
                    data-bs-target="#checkoutModal"
                    @if(empty($selectedItems)) disabled @endif
                >
                    Checkout Sekarang
                </button>
            </div>
        </div>
        <!-- Modal Konfirmasi Checkout -->
        <div wire:ignore.self class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Konfirmasi Checkout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <h6>Detail Produk:</h6>
                <ul class="list-group mb-3">
                    @foreach ($cartItems as $item)
                        @if(in_array($item->id, $selectedItems))
                            @php
                                $days = \Carbon\Carbon::parse($item->rental_date)->diffInDays(\Carbon\Carbon::parse($item->return_date)) + 1;
                                $subtotal = $item->book->price * $item->quantity * $days;
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $item->book->title }} ({{ $item->quantity }}× Rp{{ number_format($item->book->price, 0, ',', '.') }} × {{ $days }} hari)
                                <span class="fw-semibold">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold">Total Pembayaran:</span>
                    <span class="fw-bold fs-5">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
                <div class="mb-3">
                    <label for="modalPaymentMethod" class="form-label">Metode Pembayaran</label>
                    <select 
                        id="modalPaymentMethod" 
                        wire:model="paymentMethod" 
                        class="form-select"
                    >
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="cash">Tunai</option>
                        <option value="ewallet">E-Wallet</option>
                    </select>
                    @error('paymentMethod')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button wire:click="checkout" class="btn btn-success">Konfirmasi & Checkout</button>
            </div>
            </div>
        </div>
        </div>
    @endif
    @if (session()->has('message'))
        <div class="alert alert-success mt-3">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
</div>

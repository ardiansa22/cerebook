<div class="container mt-3">
    @include('layouts.search')
    @livewire('customer.breadcrumb-component')

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
                        ->diffInDays(\Carbon\Carbon::parse($item->return_date));
                    $subtotal = $item->book->final_price * $item->quantity * $days;
                    if(in_array($item->id, $selectedItems)) {
                        $grandTotal += $subtotal;
                    }
                @endphp
                <div class="card shadow-sm mb-3" wire:key="cart-item-{{ $item->id }}">
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
                                            {{ $item->quantity }} × Rp{{ number_format($item->book->final_price, 0, ',', '.') }} × {{ $days }} hari
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
                                    wire:loading.attr="disabled"
                                    wire:target="removeFromCart({{ $item->id }})"
                                    class="btn btn-sm btn-outline-danger"
                                >
                                    <span wire:loading.remove wire:target="removeFromCart({{ $item->id }})">Hapus</span>
                                    <span wire:loading wire:target="removeFromCart({{ $item->id }})">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                    </span>
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
                    wire:click="OpenRentalModal"
                    wire:loading.attr="disabled"
                    @if(empty($selectedItems)) disabled @endif
                >
                    <span wire:loading.remove>Checkout Sekarang</span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
        <!-- Modal Konfirmasi Checkout -->
       @if ($showRentalModal)
<div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index:1050;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Penyewaan</h5>
                <button type="button" class="btn-close" wire:click="$set('showRentalModal', false)"></button>
            </div>
            <div class="modal-body">
                @foreach($cartItems->whereIn('id', $selectedItems) as $item)
                <div class="mb-3 border-bottom pb-3">
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ asset('storage/books/' . $item->book->image) }}" 
                             width="50" height="50" 
                             class="rounded me-2" 
                             style="object-fit: cover;">
                        <h6 class="mb-0">{{ $item->book->title }}</h6>
                    </div>
                    <div class="row small text-muted">
                        <div class="col-6">Sewa</div>
                        <div class="col-6 text-end">{{ \Carbon\Carbon::parse($item->rental_date)->format('d M Y') }}</div>
                        
                        <div class="col-6">Kembali</div>
                        <div class="col-6 text-end">{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</div>
                        
                        @php
                            $days = \Carbon\Carbon::parse($item->rental_date)->diffInDays($item->return_date);
                            $subtotal = $item->book->final_price * $item->quantity * $days;
                        @endphp
                        
                        <div class="col-6">Subtotal</div>
                        <div class="col-6 text-end">Rp{{ number_format($subtotal, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="fw-semibold">Total Pembayaran:</span>
                    <span class="fw-bold fs-5">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="$set('showRentalModal', false)">Batal</button>
                <button type="button" class="btn btn-primary" wire:click="checkout" wire:loading.attr="disabled">
                    <span wire:loading.remove>Konfirmasi</span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
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

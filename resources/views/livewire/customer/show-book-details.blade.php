<div class="container py-4">
    <div class="mb-3">
        @livewire('customer.breadcrumb-component')
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detail Peminjaman Buku</h5>
        </div>

        <div class="card-body">
            <!-- Informasi Rental -->
            <div class="row mb-4">
                <div class="col-md-6 mb-2">
                    <strong>Tanggal Peminjaman:</strong><br>
                    {{ \Carbon\Carbon::parse($rental->rental_date)->translatedFormat('d M Y') }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Tanggal Pengembalian:</strong><br>
                    {{ \Carbon\Carbon::parse($rental->return_date)->translatedFormat('d M Y') }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Status Peminjaman:</strong><br>
                    <span class="badge bg-{{ $rental->status === 'returned' ? 'success' : ($rental->status === 'late' ? 'danger' : 'warning') }}">
                        {{ ucfirst($rental->status) }}
                    </span>
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Status Pembayaran:</strong><br>
                    <span class="{{ 
                        $rental->payment->status === 'paid' ? 'text-success' : 
                        ($rental->payment->status === 'failed' ? 'text-danger' : 'text-warning') }}">
                        {{ ucfirst($rental->payment->status) }}
                    </span>
                </div>
            </div>

            <!-- Daftar Buku -->
            <div class="mb-4">
                <h6 class="fw-bold">Buku yang Dipinjam:</h6>
                <div class="row">
                    @foreach ($rental->items as $item)
                        @php $book = $item->book; @endphp
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <img src="{{ asset('storage/books/' . $book->image) }}" 
                                             class="img-fluid rounded-start h-100 object-fit-cover" 
                                             alt="{{ $book->title }}">
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body">
                                            <h6 class="card-title mb-1">{{ $book->title }}</h6>
                                            <p class="card-text text-muted small mb-0">
                                                Subtotal: Rp{{ number_format($item->sub_total, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Total Pembayaran -->
            <div class="text-end mt-3">
                <h6 class="fw-bold">Total Pembayaran:</h6>
                <h5 class="text-primary">Rp{{ number_format($rental->payment->total, 0, ',', '.') }}</h5>
            </div>
            <!-- Tombol Lanjutkan Pembayaran jika pending -->
            @if ($rental->payment->status === 'pending')
                <div class="text-end mt-3">
                    <button wire:click="continuePayment({{ $rental->id }})" class="btn btn-warning">
                        Lanjutkan Pembayaran
                    </button>
                </div>
            @endif
        </div>
    </div>
    
</div>

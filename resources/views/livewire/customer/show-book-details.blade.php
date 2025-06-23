<div class="container py-4">
    <!-- Breadcrumb -->
    <div class="mb-3">
        @livewire('customer.breadcrumb-component')
    </div>

    <!-- Main Card -->
    <div class="card shadow border-0">
        <!-- Card Header -->
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detail Peminjaman Buku</h5>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <!-- Rental Information Section -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Informasi Peminjaman</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted d-block">Tanggal Peminjaman</small>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($rental->rental_date)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted d-block">Tanggal Pengembalian</small>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($rental->return_date)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted d-block">Status Peminjaman</small>
                            <span class="badge bg-{{ $rental->status === 'returned' ? 'success' : ($rental->status === 'late' ? 'danger' : 'warning') }}">
                                {{ ucfirst($rental->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded">
                            <small class="text-muted d-block">Status Pembayaran</small>
                            <span class="fw-medium {{ 
                                $rental->payment->status === 'paid' ? 'text-success' : 
                                ($rental->payment->status === 'failed' ? 'text-danger' : 'text-warning') }}">
                                {{ ucfirst($rental->payment->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Books Section -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Buku yang Dipinjam</h6>
                <div class="row g-3">
                    @foreach ($rental->items as $item)
                        @php $book = $item->book; @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border">
                                <div class="row g-0 h-100">
                                    <div class="col-md-5">
                                        <img src="{{ asset('storage/books/' . $book->image) }}" 
                                             class="img-fluid rounded-start h-100 w-100 object-fit-cover" 
                                             alt="{{ $book->title }}">
                                    </div>
                                    <div class="col-md-7">
                                        <div class="card-body d-flex flex-column h-100">
                                            <h6 class="card-title mb-2">{{ Str::limit($book->title, 30) }}</h6>
                                            <div class="mt-auto">
                                                <p class="card-text text-muted small mb-1">
                                                    <span class="d-block">Subtotal:</span>
                                                    <span class="fw-medium">Rp{{ number_format($item->sub_total, 0, ',', '.') }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Section -->
            @if ($rental->payment->status === 'pending')
                <div class="border-top pt-4">
                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold">Total Pembayaran:</span>
                                <h5 class="text-primary mb-0">Rp{{ number_format($rental->payment->total, 0, ',', '.') }}</h5>
                            </div>
                            <button wire:click="continuePayment({{ $rental->id }})" 
                                    class="btn btn-warning w-100 py-2 fw-medium">
                                Lanjutkan Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
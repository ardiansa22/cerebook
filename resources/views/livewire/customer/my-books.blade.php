<div class="container mt-3">
    @include('layouts.search')
    
    @if (session()->has('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
    
    @if (session()->has('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
    
    
    @forelse ($books as $rental)
        @foreach ($rental->items as $item)
            @php
                $book = $item->book;
                $payment = $rental->payment;
                $statusColor = [
                    'pending' => 'text-warning',
                    'paid' => 'text-success',
                    'failed' => 'text-danger'
                ];
            @endphp
            
            <a href="" class="text-decoration-none text-dark">
                <div class="card sesi-card shadow-sm p-3 mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Gambar Buku -->
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/books/' . $book->image) }}" 
                                 alt="{{ $book->title }}" 
                                 class="rounded" 
                                 width="80" 
                                 height="80"
                                 style="object-fit: cover;">
                            <div class="ms-3">
                                <h6 class="mb-1">{{ $book->title }}</h6>
                                <p class="text-muted mb-0">Rp{{ number_format($item->sub_total, 0, ',', '.') }}</p>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($rental->rental_date)->format('d M Y') }} - 
                                    {{ \Carbon\Carbon::parse($rental->return_date)->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                        
                        <!-- Status Pembayaran dan Rental -->
                        <div class="d-flex flex-column align-items-end">
                            <span class="badge bg-{{ $rental->status == 'returned' ? 'success' : ($rental->status == 'late' ? 'danger' : 'primary') }} mb-1">
                                {{ ucfirst($rental->status) }}
                            </span>
                            <span class="{{ $statusColor[$payment->status] ?? 'text-secondary' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    @empty
        <div class="alert alert-info mt-3">
            Anda belum meminjam buku apapun.
        </div>
    @endforelse
</div>
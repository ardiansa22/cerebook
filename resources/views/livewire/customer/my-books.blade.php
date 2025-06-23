<div class="container mt-3">
    @livewire('customer.breadcrumb-component')

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

    <!-- Navbar for Paid/Unpaid - Tambahkan mt-md-3 mt-6 di sini -->
    <ul class="nav nav-tabs mb-3 mt-md-3 mt-6" id="paymentStatusTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                All
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid" type="button" role="tab" aria-controls="paid" aria-selected="false">
                Paid
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid" type="button" role="tab" aria-controls="unpaid" aria-selected="false">
                Unpaid
            </button>
        </li>
    </ul>

    <!-- Sisanya tetap sama -->
    <div class="tab-content" id="paymentStatusTabsContent">
        <!-- All Tab -->
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
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
                    
                    <a href="{{ route('showbook', $rental->id) }}" wire:navigate class="text-decoration-none text-dark">
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

        <!-- Paid Tab -->
        <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
            @php
                $paidBooks = $books->filter(function($rental) {
                    return $rental->payment->status === 'paid';
                });
            @endphp
            
            @forelse ($paidBooks as $rental)
                @foreach ($rental->items as $item)
                    @php
                        $book = $item->book;
                        $payment = $rental->payment;
                    @endphp
                    
                    <a href="{{ route('showbook', $rental->id) }}" wire:navigate class="text-decoration-none text-dark">
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
                                    <span class="text-success">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @empty
                <div class="alert alert-info mt-3">
                    Tidak ada buku dengan status pembayaran Paid.
                </div>
            @endforelse
        </div>

        <!-- Unpaid Tab -->
        <div class="tab-pane fade" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
            @php
                $unpaidBooks = $books->filter(function($rental) {
                    return $rental->payment->status !== 'paid';
                });
            @endphp
            
            @forelse ($unpaidBooks as $rental)
                @foreach ($rental->items as $item)
                    @php
                        $book = $item->book;
                        $payment = $rental->payment;
                        $statusColor = [
                            'pending' => 'text-warning',
                            'failed' => 'text-danger'
                        ];
                    @endphp
                    
                    <a href="{{ route('showbook', $rental->id) }}" wire:navigate class="text-decoration-none text-dark">
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
                    Tidak ada buku dengan status pembayaran Unpaid.
                </div>
            @endforelse
        </div>
    </div>
</div>
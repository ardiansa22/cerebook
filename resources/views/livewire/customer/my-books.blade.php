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

    <div class="tab-content" id="paymentStatusTabsContent">
        <!-- Tab All -->
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            @forelse ($books as $rental)
                @php
                    $payment = $rental->payment;
                    $statusColor = [
                        'pending' => 'text-warning',
                        'paid' => 'text-success',
                        'failed' => 'text-danger'
                    ];
                @endphp

                <a href="{{ route('showbook', $rental->id) }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="card sesi-card shadow-sm p-3 mt-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                {{-- Daftar Buku --}}
                                @foreach ($rental->items as $item)
                                    <div class="d-flex mb-2">
                                        <img src="{{ asset('storage/books/' . $item->book->image) }}" 
                                             alt="{{ $item->book->title }}" 
                                             class="rounded" 
                                             width="60" height="60"
                                             style="object-fit: cover;">
                                        <div class="ms-2">
                                            <strong>{{ $item->book->title }}</strong><br>
                                            <small>Qty: {{ $item->quantity }}</small><br>
                                            <small>Subtotal: Rp{{ number_format($item->sub_total, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Tanggal dan Total --}}
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($rental->rental_date)->format('d M Y') }} - 
                                    {{ \Carbon\Carbon::parse($rental->return_date)->format('d M Y') }}
                                </small><br>
                                <strong>Total: Rp{{ number_format($rental->total_price, 0, ',', '.') }}</strong>
                            </div>

                            {{-- Status --}}
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-{{ $rental->status == 'returned' ? 'success' : ($rental->status == 'late' ? 'danger' : 'primary') }} mb-1">
                                    {{ ucfirst($rental->status) }}
                                </span>
                                <span class="{{ $statusColor[$payment->status] ?? 'text-secondary' }}">
                                    {{ ucfirst($payment->status ?? 'N/A') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="alert alert-info mt-3">Anda belum meminjam buku apapun.</div>
            @endforelse
        </div>

        <!-- Tab Paid -->
        <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
            @php
                $paidBooks = $books->filter(fn($r) => $r->payment && $r->payment->status === 'paid');
            @endphp
            @forelse ($paidBooks as $rental)
                @include('livewire.customer.partials.rental-card', ['rental' => $rental])
            @empty
                <div class="alert alert-info mt-3">Tidak ada buku dengan status pembayaran Paid.</div>
            @endforelse
        </div>

        <!-- Tab Unpaid -->
        <div class="tab-pane fade" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
            @php
                $unpaidBooks = $books->filter(fn($r) => !$r->payment || $r->payment->status !== 'paid');
            @endphp
            @forelse ($unpaidBooks as $rental)
                @include('livewire.customer.partials.rental-card', ['rental' => $rental])
            @empty
                <div class="alert alert-info mt-3">Tidak ada buku dengan status pembayaran Unpaid.</div>
            @endforelse
        </div>
    </div>
</div>

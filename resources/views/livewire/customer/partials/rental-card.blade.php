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
                @foreach ($rental->items as $item)
                    <div class="d-flex mb-2">
                        <img src="{{ asset('storage/books/' . $item->book->image) }}" 
                             alt="{{ $item->book->title }}" 
                             class="rounded" width="60" height="60"
                             style="object-fit: cover;">
                        <div class="ms-2">
                            <strong>{{ $item->book->title }}</strong><br>
                            <small>Qty: {{ $item->quantity }}</small><br>
                            <small>Subtotal: Rp{{ number_format($item->sub_total, 0, ',', '.') }}</small>
                        </div>
                    </div>
                @endforeach

                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($rental->rental_date)->format('d M Y') }} - 
                    {{ \Carbon\Carbon::parse($rental->return_date)->format('d M Y') }}
                </small><br>
                <strong>Total: Rp{{ number_format($rental->total_price, 0, ',', '.') }}</strong>
            </div>

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

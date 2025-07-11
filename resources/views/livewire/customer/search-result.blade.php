@extends('layouts.conapp')
@section('content')
    <div class="container mt-3 detail">
    @include('layouts.search')
    @livewire('customer.breadcrumb-component')
    @if ($books->isEmpty())
        <p class="text-muted">Buku tidak ditemukan.</p>
    @endif

    <div class="mb-3">
    <div class="grid-container">
        @foreach ($books as $book)
            <div class="grid-item">
                <a href="{{ route('book.show', $book->id) }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="card p-2 h-100">
                        <img src="{{ asset('storage/books/' . $book->image) }}" class="card-img-top book-img" alt="Gambar {{ $book->title }}">
                        <div class="card-body text-start">
                            <p class="text-muted mb-1" style="font-size: 11px;">Cerebook</p>
                            <h6 class="card-title mb-1">{{ $book->title }}</h6>
                            @if ($book->active_discount)
                                <p>
                                    <del class="text-muted">Rp {{ number_format($book->rent_price, 0, ',', '.') }}</del>
                                    <span class="text-danger fw-bold">Rp {{ number_format($book->final_price, 0, ',', '.') }}</span>
                                    <span class="badge bg-success ms-2">-{{ $book->active_discount->percentage }}%</span>
                                </p>
                            @else
                                <p>Rp {{ number_format($book->rent_price, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
</div>
@endsection
@if (!Route::is('home'))
<nav aria-label="breadcrumb" class="my-3">
    <ol class="breadcrumb bg-light px-3 py-2 rounded shadow-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">Home</a>
        </li>

        @switch(true)
            @case(Route::is('book.show'))
                <li class="breadcrumb-item active" aria-current="page">Detail Buku</li>
                @break

            @case(Route::is('my-books'))
                <li class="breadcrumb-item active" aria-current="page">Buku Saya</li>
                @break

            @case(Route::is('categori.book'))
                <li class="breadcrumb-item active" aria-current="page">
                    {{ request()->route('categori') }}
                </li>
                @break

            @case(Route::is('keranjang'))
                <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
                @break

            @case(Route::is('showbook'))
                <li class="breadcrumb-item active" aria-current="page">Detail Peminjaman</li>
                @break
        @endswitch
    </ol>
</nav>
@endif

<nav class="navbar navbar-expand-lg bg-white shadow-sm rounded mt-3 px-3 py-2">
    <div class="container-fluid d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center justify-content-between gap-2">
        
    <!-- Search Bar -->
    <div class="flex-grow-1 w-100">
        @livewire('book-search')
    </div>


        {{-- Guest: Show "Masuk" button --}}
        @guest
            <a href="{{ route('login') }}" class="btn btn-outline-primary d-flex align-items-center">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Masuk
            </a>
        @endguest

        {{-- Authenticated User --}}
        @auth
            {{-- Mobile: icon only (no dropdown) --}}
            

            {{-- Tablet & Up: dropdown --}}
            <div class="dropdown d-none d-md-block">
                <button class="btn btn-outline-success dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user me-2"></i>more
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="/my-books" wire:navigate><i class="fa-solid fa-book me-2"></i>My Books</a></li>
                    <li><a class="dropdown-item" href="/keranjang" wire:navigate><i class="fa-solid  fa-cart-shopping me-2"></i>Cart</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</nav>

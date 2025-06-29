<nav class="mobile-navbar d-md-none">
    <a href="{{ route('home') }}" wire:navigate class="nav-item">
        <i class="fas fa-home"></i><span>Home</span>
    </a>
    

    @auth
    <a href="{{ route('my-books') }}" wire:navigate class="nav-item">
        <i class="fas fa-calendar-alt"></i><span>MyBook</span>
    </a>
    <a href="/keranjang" wire:navigate class="nav-item">
         <i class="fa-solid fa-cart-shopping me-2"></i><span>Keranjang</span>
    </a>
    @endauth
</nav>

<nav class="mobile-navbar d-md-none">
    <a href="{{ route('home') }}" wire:navigate class="nav-item">
        <i class="fas fa-home"></i><span>Home</span>
    </a>
    
    <a href="" class="nav-item">
        <i class="fas fa-map-marker-alt"></i><span>Explore</span>
    </a>

    @auth
    <a href="{{ route('my-books') }}" wire:navigate class="nav-item">
        <i class="fas fa-calendar-alt"></i><span>MyBook</span>
    </a>
    @endauth
</nav>

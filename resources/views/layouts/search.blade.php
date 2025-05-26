
<div class="d-flex justify-content-between align-items-center mt-3">
    <button class="btn btn-light"><i class="fa-solid fa-location-crosshairs"></i></button>
    <input  type="text" class="form-control w-75" placeholder="Cari Buku Favoritmu">

    <div class="btn-group">
        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-regular fa-user"></i>
        </button>
                <ul class="dropdown-menu">
            @guest
                <li>
                    <a class="dropdown-item" href="{{ route('login') }}">Masuk</a>
                </li>
            @endguest

            @auth
                <li>
                    <a class="dropdown-item" href="#">Pengaturan</a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            Keluar
                        </button>
                    </form>
                </li>
            @endauth
        </ul>

    </div>
</div>


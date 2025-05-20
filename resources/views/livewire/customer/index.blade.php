
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center mt-3">
                <button class="bg-white p-2 rounded-lg shadow-sm"><i class="fa-solid fa-location-crosshairs"></i></button>
                <input wire:model='search' type="text" class="w-3/4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cari Buku Favoritmu">
                <div class="relative">
                    <button class="bg-white p-2 rounded-lg shadow-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-regular fa-user"></i>
                    </button>
                    <ul class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                        <li><a class="block px-4 py-2 text-gray-800 hover:bg-blue-500 hover:text-white" href="{{route('login')}}">Login</a></li>
                        <li><a class="block px-4 py-2 text-gray-800 hover:bg-blue-500 hover:text-white" href="#">Pengaturan</a></li>
                        <li><a class="block px-4 py-2 text-gray-800 hover:bg-blue-500 hover:text-white" href="">Keluar</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="container mx-auto mt-3">
                <div class="bg-gradient-to-r from-red-500 to-gray-200 rounded-xl shadow-sm p-3">
                    <div class="flex justify-between items-center">
                        <h5 class="font-bold text-gray-800">
                            @auth
                                Halo, {{ auth()->user()->name }}
                            @else
                                Selamat datang!
                            @endauth
                        </h5>
                        <div class="weather-info flex items-center">
                            <i class="fas fa-cloud-sun text-2xl mr-2 text-yellow-500"></i>
                            <div>
                                <p class="mb-0 font-semibold">🌍 Bandung</p>
                                <p class="mb-0 text-gray-500">🌞 28°C, Cerah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Kategori -->
            <div class="flex justify-around mt-3">
                <div class="text-center">
                    <a href="" class="no-underline text-gray-900">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                            <img src="{{ asset('img/buku.png') }}" alt="Novel" class="category-img">
                        </div>
                        <p class="mt-1">Novel</p>
                    </a>
                </div>        
                <div class="text-center">
                    <a href="" class="no-underline text-gray-900">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                            <img src="{{ asset('img/buku.png') }}" alt="Crime" class="category-img">
                        </div>
                        <p class="mt-1">Crime</p>
                    </a>
                </div>
                <div class="text-center">
                    <a href="" class="no-underline text-gray-900">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                            <img src="{{ asset('img/buku.png') }}" alt="Fantasi" class="category-img-goldfish">
                        </div>
                        <p class="mt-1">Fantasi</p>
                    </a>
                </div>
                <div class="text-center">
                    <a href="" class="no-underline text-gray-900">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                            <img src="{{ asset('img/buku.png') }}" alt="Horor" class="category-img">
                        </div>
                        <p class="mt-1">Horor</p>
                    </a>
                </div>
                <div class="text-center">
                    <a href="#" class="no-underline text-gray-900" data-bs-toggle="modal" data-bs-target="#iconModal">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                            <i class="fas fa-ellipsis-h text-white"></i>
                        </div>
                        <p class="mt-1">Semua</p>
                    </a>
                </div>        
            </div>
            
            <!-- Modal -->
            <div class="modal fixed hidden inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="iconModal" tabindex="-1" aria-labelledby="iconModalLabel" aria-hidden="true">
                <div class="modal-dialog relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="modal-header flex justify-between items-center border-b pb-3">
                        <h6 class="font-bold" class="modal-title" id="iconModalLabel">Pilih Kategori</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="flex flex-wrap justify-around">
                            <div class="text-center">
                                <a href="/kategori/gurame" class="no-underline text-gray-900">
                                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                                        <i class="fas fa-fish text-white"></i>
                                    </div>
                                    <p class="mt-1">Gurame</p>
                                </a>
                            </div>
                            <div class="text-center">
                                <a href="/kategori/patin" class="no-underline text-gray-900">
                                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                                        <i class="fas fa-water text-white"></i>
                                    </div>
                                    <p class="mt-1">Patin</p>
                                </a>
                            </div>
                            <div class="text-center">
                                <a href="/kategori/nila" class="no-underline text-gray-900">
                                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                                        <i class="fas fa-seedling text-white"></i>
                                    </div>
                                    <p class="mt-1">Nila</p>
                                </a>
                            </div>
                            <div class="text-center">
                                <a href="/kategori/bandeng" class="no-underline text-gray-900">
                                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                                        <i class="fas fa-leaf text-white"></i>
                                    </div>
                                    <p class="mt-1">Bandeng</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Swiper Carousel -->
            <!-- Swiper Carousel -->
<div class="mt-6">
    <h6 class="text-lg font-bold mb-4 flex items-center gap-2">
        Buku Populer untuk Anda 
        <i class="fas fa-arrow-right text-red-500"></i>
    </h6>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            @foreach ($books as $book)
            <div class="swiper-slide">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-transform hover:-translate-y-1 hover:shadow-xl duration-300 h-full">
                    <img src="{{ asset('storage/books/' . $book->image) }}" alt="Book Cover" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h6 class="text-md font-semibold text-gray-800 truncate" title="{{ $book->title }}">
                            {{ $book->title }}
                        </h6>
                        <span class="font-medium text-gray-800">{{ $book->price }}</span>
                        <a href="#" class="inline-block mt-3 bg-red-500 text-white text-sm px-4 py-1.5 rounded-md hover:bg-blue-700 transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <!-- Add navigation buttons -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>
        </div>

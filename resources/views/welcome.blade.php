<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adits Book</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            position: relative;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 40px;
            background: #133E87;
            z-index: -1;
        }
        .category-img {
            width: 60px;
            filter: brightness(0) invert(1);
        }
        .category-img-goldfish {
            width: 40px;
            filter: brightness(0) invert(1);
        }
        .swiper-slide img {
            border-radius: 10px;
        }
        .mobile-navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            border-top: 1px solid #ddd;
            z-index: 1000;
        }
        .mobile-navbar .nav-item {
            text-align: center;
            flex-grow: 1;
            color: #555;
            text-decoration: none;
            font-size: 14px;
        }
        .mobile-navbar .nav-item i {
            display: block;
            font-size: 18px;
            margin-bottom: 3px;
        }
        .mobile-navbar .nav-item:active,
        .mobile-navbar .nav-item:hover {
            color: #608BC1;
        }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="mobile-navbar md:hidden">
        <a href="" wire:navigate class="nav-item"><i class="fas fa-home"></i><span>Home</span></a>
        <a href="" class="nav-item"><i class="fas fa-map-marker-alt"></i><span>Spot</span></a>
        <a href="" wire:navigate class="nav-item"><i class="fas fa-calendar-alt"></i><span>MyBook</span></a>
        <a href="#" class="nav-item"><i class="fas fa-user"></i><span>Profil</span></a>
    </nav>
    
    <main class="pb-16">
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
                <div class="bg-gradient-to-r from-blue-900 to-gray-200 rounded-xl shadow-sm p-3">
                    <div class="flex justify-between items-center">
                        <h5 class="font-bold text-gray-800">
                            <!-- @auth
                                Halo, {{ auth()->user()->name }}
                            @else
                                Selamat datang!
                            @endauth -->
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
                        <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                            <img src="{{ asset('img/buku.png') }}" alt="Novel" class="category-img">
                        </div>
                        <p class="mt-1">Novel</p>
                    </a>
                </div>        
                <div class="text-center">
                    <a href="" class="no-underline text-gray-900">
                        <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                            <img src="{{ asset('img/buku.png') }}" alt="Crime" class="category-img">
                        </div>
                        <p class="mt-1">Crime</p>
                    </a>
                </div>
                <div class="text-center">
                    <a href="" class="no-underline text-gray-900">
                        <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                            <img src="{{ asset('img/buku.png') }}" alt="Fantasi" class="category-img-goldfish">
                        </div>
                        <p class="mt-1">Fantasi</p>
                    </a>
                </div>
                <div class="text-center">
                    <a href="" class="no-underline text-gray-900">
                        <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                            <img src="{{ asset('img/buku.png') }}" alt="Horor" class="category-img">
                        </div>
                        <p class="mt-1">Horor</p>
                    </a>
                </div>
                <div class="text-center">
                    <a href="#" class="no-underline text-gray-900" data-bs-toggle="modal" data-bs-target="#iconModal">
                        <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center overflow-hidden mx-auto">
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
                                    <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                                        <i class="fas fa-fish text-white"></i>
                                    </div>
                                    <p class="mt-1">Gurame</p>
                                </a>
                            </div>
                            <div class="text-center">
                                <a href="/kategori/patin" class="no-underline text-gray-900">
                                    <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                                        <i class="fas fa-water text-white"></i>
                                    </div>
                                    <p class="mt-1">Patin</p>
                                </a>
                            </div>
                            <div class="text-center">
                                <a href="/kategori/nila" class="no-underline text-gray-900">
                                    <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                                        <i class="fas fa-seedling text-white"></i>
                                    </div>
                                    <p class="mt-1">Nila</p>
                                </a>
                            </div>
                            <div class="text-center">
                                <a href="/kategori/bandeng" class="no-underline text-gray-900">
                                    <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center overflow-hidden mx-auto">
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
            <div class="swiper-container mb-3">
                <h6 class="font-bold mb-2">Spot Menjanjikan untuk Cuaca Hari ini <i class="fas fa-arrow-right"></i></h6>
                <div class="swiper-wrapper">
                    @foreach ($books as $book)
                    <div class="swiper-slide">
                        <div class="bg-white rounded-lg shadow-md p-2">
                            <img src="{{$book->image}}" class="w-full h-36 object-cover" alt="Book Cover">
                            <div class="p-2 text-left">
                                <h6 class="font-semibold">{{$book->title}}</h6>
                                <p class="text-gray-500 text-sm">Harga : {{$book->price}}</p>
                                <p class="text-gray-500 text-sm">Stok : {{$book->stock}}</p>
                            </div>
                            <a href="" class="text-blue-600 text-sm block mt-1">Detail</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            spaceBetween: 10,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 3,
                },
                0: {
                    slidesPerView: 2,
                }
            }
        });

        function downloadTicket() {
            alert("E-Ticket akan diunduh! (Fitur ini bisa dikembangkan lebih lanjut)");
        }

        function spinLapak() {
            let btnSpin = document.getElementById("btnSpin");
            let hasilSpin = document.getElementById("hasilSpin");
            let btnDaftarPeserta = document.getElementById("btnDaftarPeserta");

            btnSpin.disabled = true;
            btnSpin.innerText = "Memutar... 🔄";

            setTimeout(() => {
                let lapak = Math.floor(Math.random() * 10) + 1;
                hasilSpin.innerText = "Lapak : " + lapak;
                btnSpin.innerText = "Kocok Selesai";
                btnSpin.classList.add("bg-gray-500");
                btnSpin.classList.remove("bg-blue-900");
                btnDaftarPeserta.style.display = "block";
            }, 2000);
        }

        function addComment() {
            let commentText = document.getElementById("commentInput").value;
            if (commentText.trim() !== "") {
                alert("Komentar terkirim: " + commentText);
            }
        }

        function replyComment() {
            alert("Fitur balasan akan ditambahkan!");
        }

        const images = ["/img/kolam.jpg", "/img/kolam2.jpg"];

        function showModal(index) {
            document.getElementById("modalImage").src = images[index];
            document.getElementById("imageModal").classList.remove("hidden");
        }

        var swiperTips = new Swiper('.swiper-tips', {
            slidesPerView: 3,
            spaceBetween: 10,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 3,
                },
                0: {
                    slidesPerView: 1,
                }
            }
        });

        // Simple modal toggle for Tailwind
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('iconModal');
            const modalClose = document.querySelector('[data-bs-dismiss="modal"]');
            
            document.querySelector('[data-bs-target="#iconModal"]').addEventListener('click', function() {
                modal.classList.remove('hidden');
            });
            
            modalClose.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
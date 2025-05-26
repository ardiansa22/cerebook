
<div class="container mt-3">
    <div class="row g-4 mt-3 detail">
        @include('layouts.search')
        <!-- Kolom 1: Galeri Gambar dan Judul Tempat -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0 position-relative">
                <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                           <img src="{{ asset('storage/books/' . $book->image) }}" 
                            class="d-block w-100 rounded carousel-image" 
                            alt="Tempat" 
                            onclick="showModal(0)">

                        </div>
                        <!-- <div class="carousel-item">
                            <img src="/img/market.jpg" class="d-block w-100 rounded gallery-image" alt="Tempat" onclick="showModal(1)">
                        </div> -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                <div class="card-body text-center">
                    <h6 class="fw-bold  mt-2">{{$book->title}}</h6>
                </div>
            </div>
        </div>

        <!-- Modal untuk Preview Gambar -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>
        <!-- Kolom 4: Tentang Tempat -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0 p-3">
                <h6 class="fw-bold mb-3">Deskripsi Buku</h6>
                <p class="text-muted">
                    {{$book->description}}
                </p>
            </div>
        </div>
        
        <div class="col-md-12 text-center mt-4">
            @auth
                <button class="btn btn-prim rounded-pill shadow-sm" wire:click='buy'>Beli</button>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-danger rounded-pill shadow-sm">Masuk untuk Beli</a>
            @endguest
        </div>

        
        <div class="card shadow-sm p-3">
            <h6 class="fw-bold">💬 Diskusi</h6>
            <!-- Form Tambah Komentar -->
            <div class="mb-3">
                <textarea class="form-control" id="commentInput" placeholder="Tulis komentar..." rows="3"></textarea>
                <button class="btn btn-prim mt-2" onclick="addComment()">Kirim</button>
            </div>
            
            <!-- Daftar Komentar -->
            <div id="commentList">
                <div class="comment border rounded p-2 mb-2">
                    <div class="d-flex justify-content-between">
                        <strong>Ferian</strong> <small class="text-muted">2 menit lalu</small>
                    </div>
                    <p class="mb-1">Bagaiman Alur Bukunya</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="replyComment()">Balas</button>
                        <button class="btn btn-sm btn-outline-success">👍 3</button>
                        <button class="btn btn-sm btn-outline-danger">👎 0</button>
                    </div>
                    <!-- Balasan -->
                    <div class="reply border-start ps-3 mt-2">
                        <strong>Unknow</strong> <small class="text-muted">1 menit lalu</small>
                        <p class="mb-1">Cukup Menarik bagi yang suka misteri</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



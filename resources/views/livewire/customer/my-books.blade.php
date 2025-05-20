<div class="container mt-3 ">
    @include('layouts.search')

    @foreach ($books as $userbook)
    <a href="" class="text-decoration-none text-dark">
        <div class="card sesi-card shadow-sm p-3 mt-3">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Gambar Tempat -->
                <div class="d-flex align-items-center">
                    <img src="{{ asset('storage/books/' . $userbook->book->image) }}" 
                         alt="Tempat Mancing" class="rounded" width="80" height="80">
                    <div class="ms-3">
                        <h6 class="mb-1">{{$userbook->book->title}}</h6>
                        <p class="text-muted mb-0">{{$userbook->book->price}}</p>
                    </div>
                </div>
                <!-- Status Sesi -->
                <div class="d-flex align-items-center">
                    <div>
                            <p class="mb-0 ">{{$userbook->transaction->status}}</p>
                    </div>
                </div>
            </div>
        </div>
    </a>
    @endforeach
  
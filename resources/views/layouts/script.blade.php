<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    {{-- swiper dashboard --}}
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 3, // Menampilkan 3 card di desktop
            spaceBetween: 10,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 3, // 3 card untuk layar besar
                },
                0: {
                    slidesPerView: 2, // 2 card untuk layar mobile
                }
            }
        });
    </script>
{{-- spin lapak --}}
<script>
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
            let lapak = Math.floor(Math.random() * 10) + 1; // Misal ada 10 lapak
            hasilSpin.innerText = "Lapak : " + lapak;

            // Nonaktifkan tombol Spin setelah digunakan
            btnSpin.innerText = "Kocok Selesai";
            btnSpin.classList.add("btn-secondary"); // Ubah warna tombol setelah spin selesai
            btnSpin.classList.remove("btn-prim");

            // Tampilkan tombol "Lihat Daftar Peserta"
            btnDaftarPeserta.style.display = "block";
        }, 2000);
    }
</script>
{{-- form diskusi --}}
<script>
    function addComment() {
        let commentText = document.getElementById("commentInput").value;
        if (commentText.trim() !== "") {
            alert("Komentar terkirim: " + commentText);
        }
    }
    function replyComment() {
        alert("Fitur balasan akan ditambahkan!");
    }
</script>
{{-- array gambar --}}
<script>
    // Array gambar
    const images = ["/img/kolam.jpg", "/img/kolam2.jpg"];

    // Fungsi untuk menampilkan modal dengan gambar yang diklik
    function showModal(index) {
        document.getElementById("modalImage").src = images[index];
        new bootstrap.Modal(document.getElementById("imageModal")).show();
    }
</script>
{{-- swiper tips --}}
<script>
    var swiper = new Swiper('.swiper-tips', {
        slidesPerView: 3, // Menampilkan 3 card di desktop
        spaceBetween: 10,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            768: {
                slidesPerView: 3, // 3 card untuk layar besar
            },
            0: {
                slidesPerView: 1, // 2 card untuk layar mobile
            }
        }
    });
</script>
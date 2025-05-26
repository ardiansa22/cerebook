@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    {{-- swiper dashboard --}}
    <script>
        document.addEventListener('livewire:init', () => {
    const swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        spaceBetween: 10,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            768: { slidesPerView: 3 },
            0: { slidesPerView: 2 }
        },
        // Konfigurasi khusus untuk kompatibilitas Livewire
        simulateTouch: true,
        allowTouchMove: true,
        noSwiping: false,
        noSwipingClass: 'swiper-no-swiping',
        touchEventsTarget: 'container',
        on: {
            init: function() {
                // Aktifkan event click setelah swipe selesai
                this.el.addEventListener('click', (e) => {
                    if (this.allowClick) {
                        const link = e.target.closest('a[wire\\:navigate]');
                        if (link) {
                            e.preventDefault();
                            Livewire.navigate(link.href);
                        }
                    }
                }, true);
            },
            touchStart: function() {
                this.allowClick = false;
            },
            touchEnd: function() {
                setTimeout(() => {
                    this.allowClick = true;
                }, 100);
            }
        }
    });
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
    function showModal(imageUrl) {
        document.getElementById("modalImage").src = imageUrl;
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
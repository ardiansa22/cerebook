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
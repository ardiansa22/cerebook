
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
{{-- spin lapak --}}
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



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('swal:stok', (data) => {
            console.log('Global Event diterima:', data);
            Swal.fire({
                title: data[0].title,
                text: data[0].text,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
        });
    });
</script>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('swal:success', (data) => {
            console.log('Global Event diterima:', data);
            Swal.fire({
                title: data[0].title,
                text: data[0].text,
                showConfirmButton: false,  // hilangkan tombol OK
                timer: 2000,               // tampil 2 detik (2000 ms)
                timerProgressBar: true,    // progress bar (optional)
            });
        });
    });
</script>
<script>
    function showModal(imageUrl) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageUrl;
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }
</script>




<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
document.addEventListener('livewire:init', () => {
    Livewire.on('midtrans:pay', (payload) => {
        const data = payload[0];
        const snapToken = data.snapToken;
        
        // Hapus opsi embedId dan gunakan konfigurasi minimal
        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                console.log('success', result);
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran berhasil!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                });
            },
            onPending: function(result) {
                console.log('pending', result);
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu pembayaran Anda!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                });
            },
            onError: function(result) {
                console.log('error', result);
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran gagal!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                });
            },
            onClose: function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Popup ditutup sebelum pembayaran selesai!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                });
            }
        });
    });
});
</script>
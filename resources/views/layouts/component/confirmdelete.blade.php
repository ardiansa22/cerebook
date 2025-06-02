<script>
    window.addEventListener('confirm-delete', event => {
        Swal.fire({
            title: 'Yakin ingin menghapus data yang dipilih?',
            text: "Data yang sudah dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // warna merah tombol konfirmasi
            cancelButtonColor: '#3085d6', // warna biru tombol batal
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.deleteSelected()
                Swal.fire(
                    'Terhapus!',
                    'Data berhasil dihapus.',
                    'success'
                )
            }
        })
    })
</script>
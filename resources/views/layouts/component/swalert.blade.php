<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Livewire.on('swal:notification', ({ type, message }) => {
    Swal.fire({
        icon: type || 'info',
        title: message || 'Something happened',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        background: '#fff', // opsional
        color: '#000',       // pastikan teks terlihat
    });
});

    });
</script>
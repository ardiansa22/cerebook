<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('swal:notification', (payload) => {
    // payload ini adalah array dengan satu objek
    const data = payload[0];  // ambil objek pertama
    console.log("Type:", data.type);
    console.log("Message:", data.message);

    Swal.fire({
        icon: data.type || 'info',  // gunakan tipe dari objek
        title: data.message || 'No message',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        background: '#fff',
        color: '#000',
    });
});


    });
</script>

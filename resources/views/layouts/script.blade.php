
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

<?php if (session()->getFlashdata('success')): ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '<?= esc(session()->getFlashdata('success')) ?>',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: '<?= esc(session()->getFlashdata('error')) ?>',
            confirmButtonColor: '#3b82f6',
            confirmButtonText: 'Tutup'
        });
    });
</script>
<?php endif; ?>

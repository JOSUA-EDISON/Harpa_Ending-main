<!-- Sweet Alert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Sweet Alert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Fungsi untuk konfirmasi delete
    function confirmDelete(event) {
        event.preventDefault();
        const form = event.target.closest('form');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Fungsi untuk konfirmasi update
    function confirmUpdate(event) {
        event.preventDefault();
        const form = event.target.closest('form');

        Swal.fire({
            title: 'Konfirmasi Update',
            text: "Apakah Anda yakin ingin mengupdate data ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, update!',
            cancelButtonText: 'Batal',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Fungsi untuk konfirmasi edit
    function confirmEdit(url) {
        Swal.fire({
            title: 'Edit Data',
            text: "Anda akan mengedit data ini",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, edit!',
            cancelButtonText: 'Batal',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    // Fungsi untuk konfirmasi reset password
    function confirmResetPassword(event) {
        event.preventDefault();
        const form = event.target.closest('form');

        Swal.fire({
            title: 'Reset Password',
            text: "Password baru akan dikirim ke email pengguna dan semua sesi akan diakhiri. Lanjutkan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, reset password!',
            cancelButtonText: 'Batal',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Fungsi untuk menampilkan alert sukses
    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: message,
            timer: 3000,
            showConfirmButton: false,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    }

    // Fungsi untuk menampilkan alert error
    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: message,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    }

    // Tampilkan alert sukses jika ada session success
    @if(session('success'))
        showSuccess("{{ session('success') }}");
    @endif

    // Tampilkan alert error jika ada session error
    @if(session('error'))
        showError("{{ session('error') }}");
    @endif

    // Tampilkan alert sukses untuk message
    @if(session('message'))
        showSuccess("{{ session('message') }}");
    @endif
</script>

<!-- Animate.css untuk animasi -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

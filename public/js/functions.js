function editUser(user) {
    // Mengisi nilai input Full Name
    document.getElementById('form-user-fullname').value = user.nama_lengkap;

    // Mengisi nilai input Username
    document.getElementById('form-user-username').value = user.username;

    // Mengisi nilai input Email
    document.getElementById('form-user-email').value = user.email;

    document.getElementById('form-user-password-wrapper').classList.add('d-none');
    document.getElementById('form-user-password').value = user.password;


    // Mengisi nilai select Hak Akses
    var hakAksesSelect = document.getElementById('form-user-hak-akses');
    for (var i = 0; i < hakAksesSelect.options.length; i++) {
        if (hakAksesSelect.options[i].value === user.hak_akses) {
            hakAksesSelect.selectedIndex = i;
            break;
        }
    }

    // Mengubah action form untuk update
    document.getElementById('isUpdate').value = 'true';
    document.getElementById('form-user-id').value = user.id;

    document.getElementById('btn-submit-form-user').classList.remove('btn-success');
    document.getElementById('btn-submit-form-user').classList.add('btn-primary');
    document.getElementById('text-btn-submit-form-user').innerHTML = "Edit";
}

function resetFormUser() {
    // Mengosongkan nilai input Full Name
    document.getElementById('form-user-fullname').value = '';

    // Mengosongkan nilai input Username
    document.getElementById('form-user-username').value = '';

    // Mengosongkan nilai input Email
    document.getElementById('form-user-email').value = '';

    // Menampilkan kembali input password
    document.getElementById('form-user-password-wrapper').classList.remove('d-none');

    // Mengatur nilai default untuk select Hak Akses
    document.getElementById('form-user-hak-akses').selectedIndex = 0;

    // Mengubah action form untuk tambah
    document.getElementById('isUpdate').value = 'false';
    document.getElementById('form-user-id').value = '';

    // Mengubah tombol submit menjadi tambah
    document.getElementById('btn-submit-form-user').classList.remove('btn-primary');
    document.getElementById('btn-submit-form-user').classList.add('btn-success');
    document.getElementById('text-btn-submit-form-user').innerHTML = "Add";
}

@extends('layouts.main')

@section('title-page')
    <title>User - Visualisasi Data Mahasiswa</title>
@endsection

@section('container-content')
    <div class="page-heading">
        @if (session('user'))
            <h3>Manage User</h3>
            <p><strong>User/</strong></p>
        @else
            <h3>User not logged in</h3>
        @endif
    </div>
    <div class="page-content">

        @if (Session::has('success'))
            <div id="successAlert" class="alert alert-success alert-dismissible fade show small-alert p-1"
                style="padding-left: 15px !important;opacity: 0.8;" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('error'))
            <div id="errorAlert" class="alert alert-danger alert-dismissible fade show small-alert p-1"
                style="padding-left: 15px !important;opacity: 0.8;" role="alert">
                {{ Session::get('error') }}
                <button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div id="validationAlert" class="alert alert-danger alert-dismissible fade show small-alert p-1"
                style="padding-left: 15px !important;opacity: 0.8;" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-sm btn-success mb-3" data-bs-toggle="modal"
                    data-bs-target="#formAddEditUser" onclick="resetFormUser()">
                    <i class="bi bi-file-earmark-plus-fill"></i>&nbsp;Add User
                </button>

                <!-- Modal -->
                <div class="modal fade" id="formAddEditUser" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="formAddEditUserLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="formAddEditUserLabel">Form Add User</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <form action="{{ route('user.add-update') }}" method="POST" id="form-user">
                                @csrf
                                <input type="hidden" id="isUpdate" name="isUpdate">
                                <input type="hidden" id="form-user-id" name="id">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="form-user-fullname" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="form-user-fullname" name="fullname"
                                            placeholder="John D. Rockefeller" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="form-user-username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="form-user-username" name="username"
                                            placeholder="John" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="form-user-email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="form-user-email" name="email"
                                            placeholder="John@email.com" required>
                                    </div>
                                    <div class="mb-3" id="form-user-password-wrapper">
                                        <label for="form-user-password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="form-user-password" name="password"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="form-user-hak-akses" class="form-label">Hak Akses</label>
                                        <select class="form-select" id="form-user-hak-akses" name="hak_akses"
                                            aria-label="Select Hak Akses" required>
                                            <option value="" style="display: none;"></option>
                                            <option value="" disabled>~Universitas~</option>
                                            <option value="Rektor">Rektor</option>
                                            <option value="Admin">Admin</option>
                                            <option value="" disabled>~Fakultas~</option>
                                            @foreach ($ListFakultas as $fakultas)
                                                <option value="{{ $fakultas->nama_fakultas }}">
                                                    {{ $fakultas->nama_fakultas }}</option>
                                            @endforeach
                                            <option value="" disabled>~Prodi~</option>
                                            @foreach ($ListProdi as $prodi)
                                                <option value="{{ $prodi->nama_program_studi }}">
                                                    {{ $prodi->nama_program_studi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" id="btn-submit-form-user"><i
                                            class="bi bi-send-fill"></i>&nbsp;<span
                                            id="text-btn-submit-form-user">Add</span></button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
                <table class="table table-responsive-lg table-bordered table-striped" id="UserDataTables">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Hak Akses</th>
                            <th>Last Update</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($ListUser as $user)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $user->nama_lengkap }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->hak_akses }}</td>
                                <td>{{ $user->updated_at }}</td>
                                <td>
                                    <div class="wrapper-btn-action">
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#formAddEditUser"
                                            onclick="editUser({{ json_encode($user) }})">Edit</button>
                                        <form action="{{ route('user.delete') }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php $no++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script-pages')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk menghilangkan alert setelah beberapa detik
            function hideAlert(alertId) {
                setTimeout(function() {
                    var alert = document.getElementById(alertId);
                    alert.classList.remove('show');
                    alert.classList.add('hide');
                    alert.classList.add('d-none');
                }, 10000); // Menghilangkan alert setelah 10 detik
            }

            // Panggil fungsi hideAlert untuk masing-masing jenis alert
            hideAlert('successAlert');
            hideAlert('errorAlert');
            hideAlert('validationAlert');
        });
    </script>
@endsection

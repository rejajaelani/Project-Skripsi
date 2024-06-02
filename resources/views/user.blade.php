@extends('layouts.main')

@section('title-page')
    <title>Dashboard - Visualisasi Data Mahasiswa</title>
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
        <div class="card">
            <div class="card-body">
                <button class="btn btn-sm btn-success"><i class="bi bi-file-earmark-plus-fill"></i>&nbsp;Add User</button>
                <table class="table table-responsive-lg table-bordered table-striped" id="UserDataTables">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Access Rights</th>
                            <th>Status</th>
                            <th>Last Update</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Abdul Reja Jaelani</td>
                            <td>Admin</td>
                            <td>Active</td>
                            <td>Sunday, 13 October 2024</td>
                            <td>
                                <div class="wrapper-btn-action">
                                    <button class="btn btn-sm btn-primary">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.main')

@section('title-page')
<title>Data Beban Dosen - Visualisasi Data Mahasiswa</title>
@endsection

@section('container-content')
<div class="page-heading d-flex justify-content-between w-100">
    @if (session('user'))
    <div class="wrapper-head-titile">
        <h3>Welcome back, <span style="text-transform: capitalize;">{{ session('user')['username'] }}</span></h3>
        <p><strong>Data Beban Dosen/</strong></p>
    </div>
    {{-- {{ dd(session('user')) }} --}}
    @else
    <h3>User not logged in</h3>
    @endif
</div>
<div class="page-content">
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped" id="tableListDosen">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No</th>
                        <th>No</th>
                        <th>No</th>
                        <th>No</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#tableListDosen').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Search..."
            }
        });
    });
</script>
@endsection
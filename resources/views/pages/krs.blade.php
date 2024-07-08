@extends('layouts.main')

@section('title-page')
<title>Krs - Visualisasi Data Mahasiswa</title>
@endsection

@section('container-content')
<div class="page-heading d-flex justify-content-between w-100">
    @if (session('user'))
    <div class="wrapper-head-titile">
        <h3>Welcome back, <span style="text-transform: capitalize;">{{ session('user')['username'] }}</span></h3>
        <p><strong>Dashboard/</strong></p>
    </div>
    {{-- {{ dd(session('user')) }} --}}
    @else
    <h3>User not logged in</h3>
    @endif
</div>
<div class="page-content">
@if (
    $HakAkses == 'Admin' ||
    $HakAkses == 'Rektor')
    <form action="{{ route('krs') }}" method="GET" class="mb-3" id="form-select-filter">
        <div class="row">
            <div class="col-3">
                <select name="akses" id="akses" class="form-select form-select-sm">
                    @if ($HakAkses == 'Admin' || $HakAkses == 'Rektor')
                    <option value="" style="display: none;"></option>
                    <option value="All Data" {{ $SelectedAkses == 'All Data' ? 'selected' : '' }}>All Data
                    </option>
                    @foreach ($ListProdi as $prodi)
                    <option value="{{ $prodi->id_prodi }}" {{ $SelectedAkses == $prodi->id_prodi ? 'selected' : '' }}>
                        {{ $prodi->nama_program_studi }}
                    </option>
                    @endforeach

                    @endif
                </select>
            </div>
            <div class="col-2">
                <select name="semester" id="semester" class="form-select form-select-sm">
                    @for ($year = 2023; $year >= 2015; $year--)
                    <option value="{{ $year }}2" {{ $SelectedSemester == $year . '2' ? 'selected' : '' }}>{{ $year }} (Genap)
                    </option>
                    <option value="{{ $year }}1" {{ $SelectedSemester == $year . '1' ? 'selected' : '' }}>{{ $year }} (Ganjil)
                    </option>
                    @endfor
                </select>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-send-fill"></i>&nbsp;FILTER</button>
                <a href="{{ route('krs') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </div>
    </form>
    @else
    <form action="{{ route('krs') }}" method="GET" class="mb-3" id="form-select-filter">
        <div class="row">
            <div class="col-3">
                <select name="akses" id="akses" class="form-select form-select-sm">
                    @if ($HakAkses == 'Teknologi dan Informatika')
                    <option value="Teknologi dan Informatika" {{ $SelectedAkses == 'Teknologi dan Informatika' ? 'selected' : '' }}>All Data Fakultas</option>
                    @elseif ($HakAkses == 'Bisnis dan Desain Kreatif')
                    <option value="Bisnis dan Desain Kreatif" {{ $SelectedAkses == 'Bisnis dan Desain Kreatif' ? 'selected' : '' }}>All Data Fakultas</option>
                    @endif
                    @foreach ($ListProdi as $prodi)
                    <option value="{{ $prodi->id_prodi }}" {{ $SelectedAkses == $prodi->id_prodi ? 'selected' : '' }}>
                        {{ $prodi->nama_program_studi }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-2">
                <select name="semester" id="semester" class="form-select form-select-sm">
                    @for ($year = 2023; $year >= 2015; $year--)
                    <option value="{{ $year }}2" {{ $SelectedSemester == $year . '2' ? 'selected' : '' }}>{{ $year }} (Genap)
                    </option>
                    <option value="{{ $year }}1" {{ $SelectedSemester == $year . '1' ? 'selected' : '' }}>{{ $year }} (Ganjil)
                    </option>
                    @endfor
                </select>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-send-fill"></i>&nbsp;FILTER</button>
                <a href="{{ route('krs') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </div>
    </form>
    @endif
    <div class="row">
        @foreach ($TotalMahasiswaAktif as $mhsAktif)
        <div class="col-3 col-lg-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex" style="align-items: center;gap: 10px;">
                        <div class="stats-icon green" style="width: 50px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold">Total Mahasiswa Aktif
                            </h6>
                            <h6 class="font-extrabold mb-0">{{ $mhsAktif->Total }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @foreach ($TotalMahasiswaKRS as $mhsKRS)
        <div class="col-3 col-lg-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex" style="align-items: center;gap: 10px;">
                        <div class="stats-icon green" style="width: 50px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold">Total Mahasiswa Sudah KRS
                            </h6>
                            <h6 class="font-extrabold mb-0">{{ $mhsKRS->Total }}</h6>
                        </div>
                    </div>
                </div>
                <button type="button" style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;" data-bs-toggle="modal" data-bs-target="#ModalMahasiswaAktifSudahKRS"><i class="bi bi-box-arrow-down-right"></i></button>
            </div>
        </div>
        @endforeach
        @foreach ($TotalMahasiswaBelumKRS as $mhsBelumKRS)
        <div class="col-3 col-lg-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex" style="align-items: center;gap: 10px;">
                        <div class="stats-icon green" style="width: 50px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold">Total Mahasiswa Belum KRS
                            </h6>
                            <h6 class="font-extrabold mb-0">{{ $mhsBelumKRS->Total }}</h6>
                        </div>
                    </div>
                </div>
                <button type="button" style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;" data-bs-toggle="modal" data-bs-target="#ModalMahasiswaAktifBelumKRS"><i class="bi bi-box-arrow-down-right"></i></button>
            </div>
        </div>
        @endforeach
        @foreach ($TotalMahsiswaSKSMIN as $mhsSKSMIN)
        <div class="col-3 col-lg-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex" style="align-items: center;gap: 10px;">
                        <div class="stats-icon green" style="width: 50px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold">SKS Mahasiswa (MIN)
                            </h6>
                            <h6 class="font-extrabold mb-0">{{ $mhsSKSMIN->Total }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @foreach ($TotalMahsiswaSKSMAX as $mhsSKSMAX)
        <div class="col-3 col-lg-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex" style="align-items: center;gap: 10px;">
                        <div class="stats-icon green" style="width: 50px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold">SKS Mahasiswa (MAX)
                            </h6>
                            <h6 class="font-extrabold mb-0">{{ $mhsSKSMAX->Total }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @foreach ($TotalMahsiswaSKS as $mhsSKS)
        <div class="col-3 col-lg-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex" style="align-items: center;gap: 10px;">
                        <div class="stats-icon green" style="width: 50px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold">Total Mahasiswa SKS
                            </h6>
                            <h6 class="font-extrabold mb-0">{{ $mhsSKS->Total }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5>List Mahasiswa Aktif</h5>
                    <table class="table table-bordered table-striped" id="tableListMahasiswa">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Jenis Kelamin</th>
                                <th>Agama</th>
                                <th>Prodi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ListMahasiswaAktif as $data)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $data->NamaMahasiswa }}</td>
                                <td>{{ $data->NIM }}</td>
                                <td>{{ $data->JenisKelamin }}</td>
                                <td>{{ $data->Agama }}</td>
                                <td>{{ $data->NamaProdi }}</td>
                            </tr>
                            <?php $no++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Mahasiswa Aktif Sudah KRS -->
<div class="modal fade" id="ModalMahasiswaAktifSudahKRS" tabindex="-1" aria-labelledby="ModalMahasiswaAktifSudahKRSLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalMahasiswaAktifSudahKRSLabel">List Mahasiswa Sudah KRS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-sm" id="tableListMahasiswaSudahKRS">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Jenis Kelamin</th>
                            <th>Agama</th>
                            <th>Prodi</th>
                            <th>Total SKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($ListMahasiswaAktifSudahKRS as $data)
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $data->NamaMahasiswa }}</td>
                            <td>{{ $data->NIM }}</td>
                            <td>{{ $data->JenisKelamin }}</td>
                            <td>{{ $data->Agama }}</td>
                            <td>{{ $data->NamaProdi }}</td>
                            <td>{{ $data->TotalSKS }}</td>
                        </tr>
                        <?php $no++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Mahasiswa Aktif Belum KRS -->
<div class="modal fade" id="ModalMahasiswaAktifBelumKRS" tabindex="-1" aria-labelledby="ModalMahasiswaAktifBelumKRSLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalMahasiswaAktifBelumKRSLabel">List Mahasiswa Belum KRS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-sm" id="tableListMahasiswaBelumKRS">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Jenis Kelamin</th>
                            <th>Agama</th>
                            <th>Prodi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($ListMahasiswaAktifBelumKRS as $data)
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $data->NamaMahasiswa }}</td>
                            <td>{{ $data->NIM }}</td>
                            <td>{{ $data->JenisKelamin }}</td>
                            <td>{{ $data->Agama }}</td>
                            <td>{{ $data->NamaProdi }}</td>
                        </tr>
                        <?php $no++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script-pages')
<script>
    $(document).ready(function() {
        $('#tableListMahasiswa').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Search..."
            }
        });
        $('#tableListMahasiswaSudahKRS').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Search..."
            }
        });
        $('#tableListMahasiswaBelumKRS').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Search..."
            }
        });
    });
</script>
@endsection
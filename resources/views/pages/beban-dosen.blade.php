@extends('layouts.main')

@section('title-page')
<title>Beban Dosen - Visualisasi Data Mahasiswa</title>
@endsection

@section('container-content')
<div class="page-heading d-flex justify-content-between w-100">
    @if (session('user'))
    <div class="wrapper-head-titile">
        <h3>Welcome back, <span style="text-transform: capitalize;">{{ session('user')['username'] }}</span></h3>
        <p><strong>Beban Dosen /</strong></p>
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
    <form action="{{ route('beban-dosen') }}" method="GET" class="mb-3" id="form-select-filter">
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
                <a href="{{ route('beban-dosen') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </div>
    </form>
    @else
    <form action="{{ route('beban-dosen') }}" method="GET" class="mb-3" id="form-select-filter">
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
                <a href="{{ route('beban-dosen') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </div>
    </form>
    @endif
    <div class="row">
        <div class="col-3 col-lg-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex" style="align-items: center;gap: 10px;">
                        <div class="stats-icon green" style="width: 50px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold">Total Dosen Mengajar</h6>
                            <h6 class="font-extrabold mb-0">{{ $TotalDosenMengajar }}</h6>
                        </div>
                    </div>
                </div>
                <button type="button" style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;" data-bs-toggle="modal" data-bs-target="#ModalDosenMengajar"><i class="bi bi-box-arrow-down-right"></i></button>
            </div>
        </div>
        <div class="col-3 col-lg-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex" style="align-items: center;gap: 10px;">
                        <div class="stats-icon green" style="width: 50px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold">Total Dosen Tidak Mengajar</h6>
                            <h6 class="font-extrabold mb-0">{{ $TotalDosenTidakMengajar }}</h6>
                        </div>
                    </div>
                </div>
                <button type="button" style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;" data-bs-toggle="modal" data-bs-target="#ModalDosenTidakMengajar"><i class="bi bi-box-arrow-down-right"></i></button>
            </div>
        </div>
        <div class="col-3 col-lg-4 col-md-6">
            <div class="card mb-4">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex" style="align-items: center;gap: 10px;">
                        <div class="stats-icon green" style="width: 50px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold">Total Dosen</h6>
                            <h6 class="font-extrabold mb-0">{{ $TotalDosen }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5>List Dosen</h5>
                    <table class="table table-bordered table-striped" id="tableListDosen">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dosen</th>
                                <th>NIDN</th>
                                <th>Jenis Kelamin</th>
                                <th>Agama</th>
                                <th>Status</th>
                                <th>Sks Diajar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ListDosen as $dosen)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $dosen->nama_dosen }}</td>
                                <td>{{ $dosen->nidn }}</td>
                                <td>{{ $dosen->jenis_kelamin }}</td>
                                <td>{{ $dosen->nama_agama }}</td>
                                <td>{{ $dosen->nama_status_aktif }}</td>
                                <td>{{ $dosen->TotalSKS }}</td>
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
<!-- Modal Dosen Mengajar -->
<div class="modal fade" id="ModalDosenMengajar" tabindex="-1" aria-labelledby="ModalDosenMengajarLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalDosenMengajarLabel">List Dosen Mengajar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-sm" id="tableListDosenMengajar">
                <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dosen</th>
                                <th>NIDN</th>
                                <th>Jenis Kelamin</th>
                                <th>Agama</th>
                                <th>Status</th>
                                <th>Sks Diajar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ListDosenMengajar as $dosen)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $dosen->nama_dosen }}</td>
                                <td>{{ $dosen->nidn }}</td>
                                <td>{{ $dosen->jenis_kelamin }}</td>
                                <td>{{ $dosen->nama_agama }}</td>
                                <td>{{ $dosen->nama_status_aktif }}</td>
                                <td>{{ $dosen->TotalSKS }}</td>
                            </tr>
                            <?php $no++; ?>
                            @endforeach
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Dosen Tidak Mengajar -->
<div class="modal fade" id="ModalDosenTidakMengajar" tabindex="-1" aria-labelledby="ModalDosenTidakMengajarLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalDosenTidakMengajarLabel">List Dosen Tidak Mengajar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-sm" id="tableListDosenTidakMengajar">
                <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dosen</th>
                                <th>NIDN</th>
                                <th>Jenis Kelamin</th>
                                <th>Agama</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ListDosenTidakMengajar as $dosen)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $dosen->nama_dosen }}</td>
                                <td>{{ $dosen->nidn }}</td>
                                <td>{{ $dosen->jenis_kelamin }}</td>
                                <td>{{ $dosen->nama_agama }}</td>
                                <td>{{ $dosen->nama_status_aktif }}</td>
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
        $('#tableListDosen').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Search..."
            }
        });
        $('#tableListDosenMengajar').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Search..."
            }
        });
        $('#tableListDosenTidakMengajar').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Search..."
            }
        });
    });
</script>
@endsection
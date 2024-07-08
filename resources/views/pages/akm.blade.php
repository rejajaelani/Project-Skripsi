@extends('layouts.main')

@section('title-page')
    <title>Aktivitas Kuliah Mahasiswa - Visualisasi Data Mahasiswa</title>
@endsection
<style>
    .dataTables_wrapper>.row:nth-of-type(2)>.col-sm-12 {
        overflow-x: auto;
    }
</style>
@section('container-content')
    <div class="page-heading d-flex justify-content-between w-100">
        @if (session('user'))
            <div class="wrapper-head-titile">
                <h3>Welcome back, <span style="text-transform: capitalize;">{{ session('user')['username'] }}</span></h3>
                <p><strong>Aktivitas Kuliah Mahasiswa /</strong></p>
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
    <form action="{{ route('akm') }}" method="GET" class="mb-3" id="form-select-filter">
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
                <a href="{{ route('akm') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </div>
    </form>
    @else
    <form action="{{ route('akm') }}" method="GET" class="mb-3" id="form-select-filter">
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
                <a href="{{ route('akm') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </div>
    </form>
    @endif
        <div class="row">
            <?php $index1 = 1; ?>
            @foreach ($ListMahasiswaAKM as $data)
                <div class="col-3 col-lg-4 col-md-6">
                    <div class="card mb-4">
                        <div class="card-body px-4 py-4-5">
                            <div class="d-flex" style="align-items: center;gap: 10px;">
                                <div class="stats-icon green" style="width: 50px;">
                                    <i class="iconly-boldShow"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted font-semibold">Total Aktivitas Kuliah Mahasiswa <br>
                                        ({{ $data->Status }})
                                    </h6>
                                    <h6 class="font-extrabold mb-0">{{ $data->Total }}</h6>
                                </div>
                            </div>
                        </div>
                        <button type="button"
                            style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;"
                            data-bs-toggle="modal" data-bs-target="#ModalAktivitasKuliahMahasiswa{{ $index1 }}"><i
                                class="bi bi-box-arrow-down-right"></i></button>
                    </div>
                </div>
                <?php $index1++; ?>
            @endforeach
            @foreach ($TotalMahasiswaAKM as $data)
                <div class="col-3 col-lg-4 col-md-6">
                    <div class="card mb-4">
                        <div class="card-body px-4 py-4-5">
                            <div class="d-flex" style="align-items: center;gap: 10px;">
                                <div class="stats-icon green" style="width: 50px;">
                                    <i class="iconly-boldShow"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted font-semibold">Total Aktivitas Kuliah Mahasiswa <br>
                                    </h6>
                                    <h6 class="font-extrabold mb-0">{{ $data->Total }}</h6>
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
                        <h5>List Aktivitas Kuliah Mahasiswa</h5>
                        <table class="table table-bordered table-striped table-responsive" id="tableListAkm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Agama</th>
                                    <th>Prodi</th>
                                    <th>Semester</th>
                                    <th>Angkatan</th>
                                    <th>Status</th>
                                    <th>IPS</th>
                                    <th>IPK</th>
                                    <th>SKS (Semester)</th>
                                    <th>SKS (Keseluruhan)</th>
                                    <th>Biaya Per-Semester</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($ListMahasiswaALLStatus as $data)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $data->NamaMahasiswa }}</td>
                                        <td>{{ $data->NIM }}</td>
                                        <td>{{ $data->JenisKelamin }}</td>
                                        <td>{{ $data->NamaAgama }}</td>
                                        <td>{{ $data->NamaProdi }}</td>
                                        <td>{{ $data->Semester }}</td>
                                        <td>{{ $data->Angkatan }}</td>
                                        <td>{{ $data->StatusMahasiswa }}</td>
                                        <td>{{ $data->IPS }}</td>
                                        <td>{{ $data->IPK }}</td>
                                        <td>{{ $data->SKSPerSemester }}</td>
                                        <td>{{ $data->SKSKeseluruhan }}</td>
                                        <td>Rp. {{ number_format($data->BiayaKuliahPerSemester, 0, ',', '.') }}</td>
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

    <?php $index2 = 1; ?>
    @foreach ($ListMahasiswaAKM as $data)
        <!-- Modal AKM -->
        <div class="modal fade" id="ModalAktivitasKuliahMahasiswa{{ $index2 }}" tabindex="-1"
            aria-labelledby="ModalAktivitasKuliahMahasiswa{{ $index2 }}Label" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="ModalAktivitasKuliahMahasiswa{{ $index2 }}Label">List
                            Aktivitas Kuliah Mahasiswa ({{ $data->Status }})
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped table-responsive"
                            id="tableListAkm{{ $index2 }}">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Agama</th>
                                    <th>Prodi</th>
                                    <th>Semester</th>
                                    <th>Angkatan</th>
                                    <th>IPS</th>
                                    <th>IPK</th>
                                    <th>SKS (Semester)</th>
                                    <th>SKS (Keseluruhan)</th>
                                    <th>Biaya Per-Semester</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($data->ListMahasiswa as $list)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $list->NamaMahasiswa }}</td>
                                        <td>{{ $list->NIM }}</td>
                                        <td>{{ $list->JenisKelamin }}</td>
                                        <td>{{ $list->NamaAgama }}</td>
                                        <td>{{ $list->NamaProdi }}</td>
                                        <td>{{ $list->Semester }}</td>
                                        <td>{{ $list->Angkatan }}</td>
                                        <td>{{ $list->IPS }}</td>
                                        <td>{{ $list->IPK }}</td>
                                        <td>{{ $list->SKSPerSemester }}</td>
                                        <td>{{ $list->SKSKeseluruhan }}</td>
                                        <td>Rp. {{ number_format($list->BiayaKuliahPerSemester, 0, ',', '.') }}</td>
                                    </tr>
                                    <?php $no++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php $index2++; ?>
    @endforeach
@endsection

@section('script-pages')
    <script>
        $(document).ready(function() {
            $('#tableListAkm').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });

            var jumProdi = parseInt('{{ $index1 }}', 10);

            for (var i = 0; i < jumProdi; i++) {
                $('#tableListAkm' + i).DataTable({
                    "language": {
                        "search": "",
                        "searchPlaceholder": "Search..."
                    }
                });
            }
        });
    </script>
@endsection

@extends('layouts.main')

@section('title-page')
    <title>KelulusanDO - Visualisasi Data Mahasiswa</title>
@endsection

@section('container-content')
    <div class="page-heading d-flex justify-content-between w-100">
        @if (session('user'))
            <div class="wrapper-head-titile">
                <h3>Welcome back, <span style="text-transform: capitalize;">{{ session('user')['username'] }}</span></h3>
                <p><strong>KelulusanDO /</strong></p>
            </div>
            {{-- {{ dd(session('user')) }} --}}
        @else
            <h3>User not logged in</h3>
        @endif
    </div>
    <div class="page-content">
        @if ($HakAkses == 'Admin' || $HakAkses == 'Rektor')
            <form action="{{ route('kelulusan-do') }}" method="GET" class="mb-3" id="form-select-filter">
                <div class="row">
                    <div class="col-3">
                        <select name="akses" id="akses" class="form-select form-select-sm">
                            @if ($HakAkses == 'Admin' || $HakAkses == 'Rektor')
                                <option value="" style="display: none;"></option>
                                <option value="All Data" {{ $SelectedAkses == 'All Data' ? 'selected' : '' }}>All Data
                                </option>
                                <option value="Teknologi dan Informatika"
                                    {{ $SelectedAkses == 'Teknologi dan Informatika' ? 'selected' : '' }}>Teknologi dan
                                    Informatika
                                </option>
                                <option value="Bisnis dan Desain Kreatif"
                                    {{ $SelectedAkses == 'Bisnis dan Desain Kreatif' ? 'selected' : '' }}>Bisnis dan Desain
                                    Kreatif
                                </option>
                                @foreach ($ListProdi as $prodi)
                                    <option value="{{ $prodi->id_prodi }}"
                                        {{ $SelectedAkses == $prodi->id_prodi ? 'selected' : '' }}>
                                        {{ $prodi->nama_program_studi }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="semester" id="semester" class="form-select form-select-sm">
                            @for ($year = 2023; $year >= 2015; $year--)
                                <option value="{{ $year }}2"
                                    {{ $SelectedSemester == $year . '2' ? 'selected' : '' }}>{{ $year }} (Genap)
                                </option>
                                <option value="{{ $year }}1"
                                    {{ $SelectedSemester == $year . '1' ? 'selected' : '' }}>{{ $year }} (Ganjil)
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-sm btn-outline-primary"><i
                                class="bi bi-send-fill"></i>&nbsp;FILTER</button>
                        <a href="{{ route('kelulusan-do') }}" class="btn btn-sm btn-outline-secondary"><i
                                class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </div>
            </form>
        @else
            <form action="{{ route('kelulusan-do') }}" method="GET" class="mb-3" id="form-select-filter">
                <div class="row">
                    <div class="col-3">
                        <select name="akses" id="akses" class="form-select form-select-sm">
                            @if ($HakAkses == 'Teknologi dan Informatika')
                                <option value="Teknologi dan Informatika"
                                    {{ $SelectedAkses == 'Teknologi dan Informatika' ? 'selected' : '' }}>All Data Fakultas
                                </option>
                            @elseif ($HakAkses == 'Bisnis dan Desain Kreatif')
                                <option value="Bisnis dan Desain Kreatif"
                                    {{ $SelectedAkses == 'Bisnis dan Desain Kreatif' ? 'selected' : '' }}>All Data Fakultas
                                </option>
                            @endif
                            @foreach ($ListProdi as $prodi)
                                <option value="{{ $prodi->id_prodi }}"
                                    {{ $SelectedAkses == $prodi->id_prodi ? 'selected' : '' }}>
                                    {{ $prodi->nama_program_studi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="semester" id="semester" class="form-select form-select-sm">
                            @for ($year = 2023; $year >= 2015; $year--)
                                <option value="{{ $year }}2"
                                    {{ $SelectedSemester == $year . '2' ? 'selected' : '' }}>{{ $year }} (Genap)
                                </option>
                                <option value="{{ $year }}1"
                                    {{ $SelectedSemester == $year . '1' ? 'selected' : '' }}>{{ $year }} (Ganjil)
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-sm btn-outline-primary"><i
                                class="bi bi-send-fill"></i>&nbsp;FILTER</button>
                        <a href="{{ route('kelulusan-do') }}" class="btn btn-sm btn-outline-secondary"><i
                                class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </div>
            </form>
        @endif
        <div class="row">
            <?php $index1 = 1; ?>
            @foreach ($ListKelulusanDO as $data)
                <div class="col-3 col-lg-4 col-md-6">
                    <div class="card mb-4">
                        <div class="card-body px-4 py-4-5">
                            <div class="d-flex" style="align-items: center;gap: 10px;">
                                <div class="stats-icon green" style="width: 50px;">
                                    <i class="iconly-boldShow"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted font-semibold">Total Mahasiswa <br>
                                        ({{ $data->Jenis }})
                                    </h6>
                                    <h6 class="font-extrabold mb-0">{{ $data->Total }}</h6>
                                </div>
                            </div>
                        </div>
                        <button type="button"
                            style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;"
                            data-bs-toggle="modal" data-bs-target="#ModalMahasiswaLulusDOPerJenis{{ $index1 }}"><i
                                class="bi bi-box-arrow-down-right"></i></button>
                    </div>
                </div>
                <?php $index1++; ?>
            @endforeach
        </div>
    </div>

    <?php $index2 = 1; ?>
    @foreach ($ListKelulusanDO as $data)
        <!-- Modal Mahasiswa LulusDO Per Jenis -->
        <div class="modal fade" id="ModalMahasiswaLulusDOPerJenis{{ $index2 }}" tabindex="-1"
            aria-labelledby="ModalMahasiswaLulusDOPerJenis{{ $index2 }}Label" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="ModalMahasiswaLulusDOPerJenis{{ $index2 }}Label">List
                            Mahsiswa ({{ $data->Jenis }})
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped table-sm"
                            id="tableListMahasiswaLulusDO{{ $index2 }}">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nim</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th>Angkatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($data->ListMahasiswa as $mhs)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $mhs->Nim }}</td>
                                        <td>{{ $mhs->NamaMahasiswa }}</td>
                                        <td>{{ $mhs->NamaProdi }}</td>
                                        <td>{{ $mhs->Angkatan }}</td>
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

            var jumProdi = parseInt('{{ $index1 }}', 10);

            for (var i = 0; i < jumProdi; i++) {
                $('#tableListMahasiswaLulusDO' + i).DataTable({
                    "language": {
                        "search": "",
                        "searchPlaceholder": "Search..."
                    }
                });
            }

        });
    </script>
@endsection

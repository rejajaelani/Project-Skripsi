@extends('layouts.main')

@section('title-page')
    <title>Kelas Perkuliahan - Visualisasi Data Mahasiswa</title>
@endsection

@section('container-content')
    <div class="page-heading d-flex justify-content-between w-100">
        @if (session('user'))
            <div class="wrapper-head-titile">
                <h3>Welcome back, <span style="text-transform: capitalize;">{{ session('user')['username'] }}</span></h3>
                <p><strong>Kelas Perkuliahan/</strong></p>
            </div>
            {{-- {{ dd(session('user')) }} --}}
        @else
            <h3>User not logged in</h3>
        @endif
    </div>
    <div class="page-content">
        @if (
            $HakAkses == 'Admin' ||
                $HakAkses == 'Rektor' ||
                $HakAkses == 'Teknologi dan Informatika' ||
                $HakAkses == 'Bisnis dan Desain Kreatif')
            <form action="{{ route('data-beban-dosen') }}" method="GET" class="mb-3" id="form-select-filter">
                <div class="row">
                    <div class="col-3">
                        <select name="select_data" id="select_data" class="form-select form-select-sm">
                            @if ($HakAkses == 'Admin' || $HakAkses == 'Rektor')
                                <option value="" style="display: none;"></option>
                                <option value="" disabled>~Universitas~</option>
                                <option value="Rektor" {{ $SelectedAkses == 'Rektor' ? 'selected' : '' }}
                                    {{ $SelectedAkses != 'Rektor' ? 'style=display:none;' : '' }}>All Data</option>
                                <option value="Admin" {{ $SelectedAkses == 'Admin' ? 'selected' : '' }}
                                    {{ $SelectedAkses != 'Admin' ? 'style=display:none;' : '' }}>All Data</option>
                                <option value="" disabled>~Fakultas~</option>
                                @foreach ($ListFakultas as $fakultas)
                                    <option value="{{ $fakultas->nama_fakultas }}"
                                        {{ $SelectedAkses == $fakultas->nama_fakultas ? 'selected' : '' }}>
                                        {{ $fakultas->nama_fakultas }}
                                    </option>
                                @endforeach
                                <option value="" disabled>~Prodi~</option>
                                @foreach ($ListProdi as $prodi)
                                    <option value="{{ $prodi->nama_program_studi }}"
                                        {{ $SelectedAkses == $prodi->nama_program_studi ? 'selected' : '' }}>
                                        {{ $prodi->nama_program_studi }}
                                    </option>
                                @endforeach
                            @elseif ($HakAkses == 'Teknologi dan Informatika')
                                <option value="" disabled>~Fakultas~</option>
                                <option value="Teknologi dan Informatika"
                                    {{ $SelectedAkses == 'Teknologi dan Informatika' ? 'selected' : '' }}>Teknologi dan
                                    Informatika</option>
                                <option value="" disabled>~Prodi~</option>
                                <option value="Teknik Informatika"
                                    {{ $SelectedAkses == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika
                                </option>
                                <option value="Rekayasa Sistem Komputer"
                                    {{ $SelectedAkses == 'Rekayasa Sistem Komputer' ? 'selected' : '' }}>Rekayasa Sistem
                                    Komputer</option>
                                <option value="Sistem Komputer"
                                    {{ $SelectedAkses == 'Sistem Komputer' ? 'selected' : '' }}>
                                    Sistem Komputer</option>
                            @elseif ($HakAkses == 'Bisnis dan Desain Kreatif')
                                <option value="" disabled>~Fakultas~</option>
                                <option value="Bisnis dan Desain Kreatif"
                                    {{ $SelectedAkses == 'Bisnis dan Desain Kreatif' ? 'selected' : '' }}>Bisnis dan Desain
                                    Kreatif</option>
                                <option value="" disabled>~Prodi~</option>
                                <option value="Bisnis Digital" {{ $SelectedAkses == 'Bisnis Digital' ? 'selected' : '' }}>
                                    Bisnis Digital</option>
                                <option value="Desain Komunikasi Visual"
                                    {{ $SelectedAkses == 'Desain Komunikasi Visual' ? 'selected' : '' }}>Desain Komunikasi
                                    Visual</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="" id="" class="form-select form-select-sm">
                            @for ($year = 2023; $year >= 2015; $year--)
                                <option value="{{ $year }}2">{{ $year }} (Genap)</option>
                                <option value="{{ $year }}1">{{ $year }} (Ganjil)</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-sm btn-outline-primary"><i
                                class="bi bi-send-fill"></i>&nbsp;FILTER</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary"><i
                                class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </div>
            </form>
        @else
            <div class="row mb-3">
                <div class="col-3">
                    <select name="" id="" class="form-select form-select-sm" disabled>
                        <option value="{{ $SelectedAkses }}" style="display: none" selected>{{ $SelectedAkses }}
                        </option>
                    </select>
                </div>
            </div>
        @endif
        <div class="row">
            {{-- <h5>Total Keseluruhan</h5> --}}
            <div class="col-3 col-lg-4 col-md-6">
                <div class="card mb-4">
                    <div class="card-body px-4 py-4-5">
                        <div class="d-flex" style="align-items: center;gap: 10px;">
                            <div class="stats-icon green" style="width: 50px;">
                                <i class="iconly-boldShow"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold">Kulikulum</h6>
                                @foreach ($TotalMatkul as $total)
                                    <h6 class="font-extrabold mb-0">{{ $total->TotalMatkul }}</h6>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button type="button"
                        style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;"
                        data-bs-toggle="modal" data-bs-target="#ModalTotalMataKuliah"><i
                            class="bi bi-box-arrow-down-right"></i></button>
                </div>
            </div>
            <div class="col-3 col-lg-4 col-md-6">
                <div class="card mb-4">
                    <div class="card-body px-4 py-4-5">
                        <div class="d-flex" style="align-items: center;gap: 10px;">
                            <div class="stats-icon bg-warning" style="width: 50px;">
                                <i class="iconly-boldShow"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold">Fakultas</h6>
                                @foreach ($TotalMatkul as $total)
                                    <h6 class="font-extrabold mb-0">{{ $total->TotalMatkul }}</h6>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button type="button"
                        style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;"
                        data-bs-toggle="modal" data-bs-target="#ModalTotalMataKuliah"><i
                            class="bi bi-box-arrow-down-right"></i></button>
                </div>
            </div>
            <div class="col-3 col-lg-4 col-md-6">
                <div class="card mb-4">
                    <div class="card-body px-4 py-4-5">
                        <div class="d-flex" style="align-items: center;gap: 10px;">
                            <div class="stats-icon purple" style="width: 50px;">
                                <i class="iconly-boldShow"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold">Prodi</h6>
                                @foreach ($TotalMatkul as $total)
                                    <h6 class="font-extrabold mb-0">{{ $total->TotalMatkul }}</h6>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button type="button"
                        style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;"
                        data-bs-toggle="modal" data-bs-target="#ModalTotalMataKuliah"><i
                            class="bi bi-box-arrow-down-right"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3 col-lg-4 col-md-6">
                <div class="card mb-4">
                    <div class="card-body px-4 py-4-5">
                        <div class="d-flex" style="align-items: center;gap: 10px;">
                            <div class="stats-icon bg-info" style="width: 50px;">
                                <i class="iconly-boldShow"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold">Total Mata Kuliah</h6>
                                @foreach ($TotalMatkul as $total)
                                    <h6 class="font-extrabold mb-0">{{ $total->TotalMatkul }}</h6>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button type="button"
                        style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;"
                        data-bs-toggle="modal" data-bs-target="#ModalTotalMataKuliah"><i
                            class="bi bi-box-arrow-down-right"></i></button>
                </div>
            </div>
            <div class="col-3 col-lg-4 col-md-6">
                <div class="card mb-4">
                    <div class="card-body px-4 py-4-5">
                        <div class="d-flex" style="align-items: center;gap: 10px;">
                            <div class="stats-icon bg-secondary" style="width: 50px;">
                                <i class="iconly-boldShow"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold">Kelas Belum Ada Dosen</h6>
                                @foreach ($TotalKelasNULL as $total)
                                    <h6 class="font-extrabold mb-0">{{ $total->TotalKelas }}</h6>
                                @endforeach
                            </div>
                        </div>
                        <button type="button"
                            style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;"
                            data-bs-toggle="modal" data-bs-target="#ModalKelasBelumAdaDosen"><i
                                class="bi bi-box-arrow-down-right"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-3 col-lg-4 col-md-6">
                <div class="card mb-4">
                    <div class="card-body px-4 py-4-5">
                        <div class="d-flex" style="align-items: center;gap: 10px;">
                            <div class="stats-icon bg-primary" style="width: 50px;">
                                <i class="iconly-boldShow"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold">Total Kelas</h6>
                                @foreach ($TotalKelas as $total)
                                    <h6 class="font-extrabold mb-0">{{ $total->TotalKelas }}</h6>
                                @endforeach
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
                        <h5>List Kelas Kuliah</h5>
                        <table class="table table-bordered table-striped" id="tableListKelasPerkuliahan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th>Kode Mata Kuliah</th>
                                    <th>Kelas</th>
                                    <th>Jumlah SKS</th>
                                    <th>Nama Dosen Pengajar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($ListKelasPerkuliahan as $kelas)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $kelas->NamaMatkul }}</td>
                                        <td>{{ $kelas->KodeMatkul }}</td>
                                        <td>{{ $kelas->Kelas }}</td>
                                        <td>{{ $kelas->JumlahSKS }}</td>
                                        <td>{{ $kelas->NamaDosen }}</td>
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

    <!-- List Modal Bootstraps For Data -->

    <!-- Modal Kelas Belum Ada Dosen -->
    <div class="modal fade" id="ModalKelasBelumAdaDosen" tabindex="-1" aria-labelledby="ModalKelasBelumAdaDosenLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalKelasBelumAdaDosenLabel">List Kelas Belum Ada Dosen</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-sm" id="tableListKelasPerkuliahanNULL">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Kode Mata Kuliah</th>
                                <th>Kelas</th>
                                <th>Jumlah SKS</th>
                                <th>Nama Dosen Pengajar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ListKelasPerkuliahanNULL as $kelas)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $kelas->NamaMatkul }}</td>
                                    <td>{{ $kelas->KodeMatkul }}</td>
                                    <td>{{ $kelas->Kelas }}</td>
                                    <td>{{ $kelas->JumlahSKS }}</td>
                                    <td>{{ $kelas->NamaDosen }}</td>
                                </tr>
                                <?php $no++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Mata Kuliah Wajib -->
    <div class="modal fade" id="ModalMataKuliahWajib" tabindex="-1" aria-labelledby="ModalMataKuliahWajibLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalMataKuliahWajibLabel">List Mata Kuliah Wajib</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-sm" id="tableListMataKuliahWajib">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Kode Mata Kuliah</th>
                                <th>Nama Program Studi</th>
                                <th>Jumlah SKS (Materi)</th>
                                <th>Jumlah SKS (Praktek)</th>
                                <th>Jumlah SKS (Praktek Lapangan)</th>
                                <th>Jumlah SKS (Simulasi)</th>
                                <th>Jumlah SKS (Keseluruhan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ListMataKuliahWajib as $matkul)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $matkul->NamaMatkul }}</td>
                                    <td>{{ $matkul->KodeMatkul }}</td>
                                    <td>{{ $matkul->NamaStudi }}</td>
                                    <td>{{ $matkul->MateriSKS }}</td>
                                    <td>{{ $matkul->PraktekSKS }}</td>
                                    <td>{{ $matkul->PraktekLapanganSKS }}</td>
                                    <td>{{ $matkul->SimulasiSKS }}</td>
                                    <td>{{ $matkul->JumlahSKS }}</td>
                                </tr>
                                <?php $no++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Mata Kuliah Wajib Peminatan -->
    <div class="modal fade" id="ModalMataKuliahWajibPeminatan" tabindex="-1"
        aria-labelledby="ModalMataKuliahWajibPeminatanLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalMataKuliahWajibPeminatanLabel">List Mata Kuliah Wajib Peminatan
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-sm" id="tableListMataKuliahWajibPeminatan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Kode Mata Kuliah</th>
                                <th>Nama Program Studi</th>
                                <th>Jumlah SKS (Materi)</th>
                                <th>Jumlah SKS (Praktek)</th>
                                <th>Jumlah SKS (Praktek Lapangan)</th>
                                <th>Jumlah SKS (Simulasi)</th>
                                <th>Jumlah SKS (Keseluruhan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ListMataKuliahWajibPeminatan as $matkul)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $matkul->NamaMatkul }}</td>
                                    <td>{{ $matkul->KodeMatkul }}</td>
                                    <td>{{ $matkul->NamaStudi }}</td>
                                    <td>{{ $matkul->MateriSKS }}</td>
                                    <td>{{ $matkul->PraktekSKS }}</td>
                                    <td>{{ $matkul->PraktekLapanganSKS }}</td>
                                    <td>{{ $matkul->SimulasiSKS }}</td>
                                    <td>{{ $matkul->JumlahSKS }}</td>
                                </tr>
                                <?php $no++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Mata Kuliah Pilihan -->
    <div class="modal fade" id="ModalMataKuliahPilihan" tabindex="-1" aria-labelledby="ModalMataKuliahPilihanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalMataKuliahPilihanLabel">List Mata Kuliah Pilihan
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-sm" id="tableListMataKuliahPilihan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Kode Mata Kuliah</th>
                                <th>Nama Program Studi</th>
                                <th>Jumlah SKS (Materi)</th>
                                <th>Jumlah SKS (Praktek)</th>
                                <th>Jumlah SKS (Praktek Lapangan)</th>
                                <th>Jumlah SKS (Simulasi)</th>
                                <th>Jumlah SKS (Keseluruhan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ListMataKuliahPilihan as $matkul)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $matkul->NamaMatkul }}</td>
                                    <td>{{ $matkul->KodeMatkul }}</td>
                                    <td>{{ $matkul->NamaStudi }}</td>
                                    <td>{{ $matkul->MateriSKS }}</td>
                                    <td>{{ $matkul->PraktekSKS }}</td>
                                    <td>{{ $matkul->PraktekLapanganSKS }}</td>
                                    <td>{{ $matkul->SimulasiSKS }}</td>
                                    <td>{{ $matkul->JumlahSKS }}</td>
                                </tr>
                                <?php $no++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Mata Kuliah Tugas Akhir / Skripsi -->
    <div class="modal fade" id="ModalMataKuliahTA" tabindex="-1" aria-labelledby="ModalMataKuliahTALabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalMataKuliahTALabel">List Mata Kuliah Tugas Akhir / Skripsi
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-sm" id="tableListMataKuliahTA">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Kode Mata Kuliah</th>
                                <th>Nama Program Studi</th>
                                <th>Jumlah SKS (Materi)</th>
                                <th>Jumlah SKS (Praktek)</th>
                                <th>Jumlah SKS (Praktek Lapangan)</th>
                                <th>Jumlah SKS (Simulasi)</th>
                                <th>Jumlah SKS (Keseluruhan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ListMataKuliahTA as $matkul)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $matkul->NamaMatkul }}</td>
                                    <td>{{ $matkul->KodeMatkul }}</td>
                                    <td>{{ $matkul->NamaStudi }}</td>
                                    <td>{{ $matkul->MateriSKS }}</td>
                                    <td>{{ $matkul->PraktekSKS }}</td>
                                    <td>{{ $matkul->PraktekLapanganSKS }}</td>
                                    <td>{{ $matkul->SimulasiSKS }}</td>
                                    <td>{{ $matkul->JumlahSKS }}</td>
                                </tr>
                                <?php $no++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Mata Kuliah Tanpa Keterangan -->
    <div class="modal fade" id="ModalMataKuliahNULL" tabindex="-1" aria-labelledby="ModalMataKuliahNULLLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalMataKuliahNULLLabel">List Mata Kuliah Tanpa Keterangan
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-sm" id="tableListMataKuliahNULL">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Kode Mata Kuliah</th>
                                <th>Nama Program Studi</th>
                                <th>Jumlah SKS (Materi)</th>
                                <th>Jumlah SKS (Praktek)</th>
                                <th>Jumlah SKS (Praktek Lapangan)</th>
                                <th>Jumlah SKS (Simulasi)</th>
                                <th>Jumlah SKS (Keseluruhan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ListMataKuliahNULL as $matkul)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $matkul->NamaMatkul }}</td>
                                    <td>{{ $matkul->KodeMatkul }}</td>
                                    <td>{{ $matkul->NamaStudi }}</td>
                                    <td>{{ $matkul->MateriSKS }}</td>
                                    <td>{{ $matkul->PraktekSKS }}</td>
                                    <td>{{ $matkul->PraktekLapanganSKS }}</td>
                                    <td>{{ $matkul->SimulasiSKS }}</td>
                                    <td>{{ $matkul->JumlahSKS }}</td>
                                </tr>
                                <?php $no++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Total Mata Kuliah -->
    <div class="modal fade" id="ModalTotalMataKuliah" tabindex="-1" aria-labelledby="ModalTotalMataKuliahLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalTotalMataKuliahLabel">List Mata Kuliah
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-sm" id="tableListTotalMataKuliah">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Kode Mata Kuliah</th>
                                <th>Nama Program Studi</th>
                                <th>Jumlah SKS (Materi)</th>
                                <th>Jumlah SKS (Praktek)</th>
                                <th>Jumlah SKS (Praktek Lapangan)</th>
                                <th>Jumlah SKS (Simulasi)</th>
                                <th>Jumlah SKS (Keseluruhan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ListTotalMataKuliah as $matkul)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $matkul->NamaMatkul }}</td>
                                    <td>{{ $matkul->KodeMatkul }}</td>
                                    <td>{{ $matkul->NamaStudi }}</td>
                                    <td>{{ $matkul->MateriSKS }}</td>
                                    <td>{{ $matkul->PraktekSKS }}</td>
                                    <td>{{ $matkul->PraktekLapanganSKS }}</td>
                                    <td>{{ $matkul->SimulasiSKS }}</td>
                                    <td>{{ $matkul->JumlahSKS }}</td>
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
            $('#tableListKelasPerkuliahan').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
            $('#tableListKelasPerkuliahanNULL').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
            $('#tableListMataKuliahWajib').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
            $('#tableListMataKuliahWajibPeminatan').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
            $('#tableListMataKuliahPilihan').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
            $('#tableListMataKuliahTA').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
            $('#tableListMataKuliahNULL').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
            $('#tableListTotalMataKuliah').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
        });
    </script>
@endsection

@extends('layouts.main')

@section('title-page')
    <title>Dashboard - Visualisasi Data Mahasiswa</title>
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
        <div class="wrapper-btn-header-right d-none">
            <a href="{{ route('process.synchronizeTEST') }}" class="btn btn-outline-light"><i
                    class="bi bi-bezier2"></i>&nbsp;SYNC
                MANUAL MAINTENANCE 1 TABLE</a>
        </div>
    </div>
    <div class="page-content">
        <section class="row">
            @foreach ($JumMhsTotal as $totalmhs)
                <form action="" class="mb-5" id="form-select-filter">
                    <div class="row">
                        <div class="col-3">
                            <select name="selectProdi" id="selectProdi" class="form-select form-select-sm">
                                <option value="All">All</option>
                                @foreach ($ListProdi as $prodi)
                                    <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_program_studi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-1">
                            <select name="selectTahun" id="selectTahun" class="form-select form-select-sm">
                                <?php
                                $currentYear = date('Y');
                                for ($i = $currentYear; $i >= 2014; $i--) {
                                    $selected = ($i == 2023) ? ' selected' : '';
                                ?>
                                <option value="<?= $i ?>"<?= $selected ?>><?= $i ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-sm btn-outline-primary"><i
                                    class="bi bi-send-fill"></i>&nbsp;FILTER</button>
                        </div>
                    </div>
                </form>

                <div class="col-12">
                    <div class="row">
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="d-flex" style="gap: 10px;">
                                        <div class="stats-icon green">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted font-semibold">Mahasiswa Aktif</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalmhs->Total_Mahasiswa_Aktif }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="d-flex" style="gap: 10px;">
                                        <div class="stats-icon bg-warning">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted font-semibold">Mahasiswa Cuti</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalmhs->Total_Mahasiswa_Cuti }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="d-flex" style="gap: 10px;">
                                        <div class="stats-icon bg-secondary">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted font-semibold">Mahasiswa Non Aktif</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalmhs->Total_Mahasiswa_NonAktif }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="d-flex" style="gap: 10px;">
                                        <div class="stats-icon purple">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted font-semibold">Total Mahasiswa</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalmhs->TotalMahasiswa }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Mahasiswa Per-Tahun</h4>
                                    <p class="p-0 m-0 mt-1">Mahasiswa Aktif, Mahasiswa Non Aktif, dan Total Mahasiswa</p>
                                </div>
                                <div class="card-body">
                                    <div id="MahasiswaPertahun" data-jum-mhs-total-per-tahun='@json($JumMhsTotalPerTahun)'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-xl-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Profile Visit</h4>
                                </div>
                                <div class="card-body">
                                    @foreach ($JumMhsPerProdi as $prodi)
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="d-flex align-items-center">
                                                    <svg class="bi text-primary" width="32" height="32"
                                                        fill="blue" style="width:10px">
                                                        <use xlink:href="assets/images/bootstrap-icons.svg#circle-fill" />
                                                    </svg>
                                                    <h6 class="mb-0 ms-3">
                                                        {{ $prodi->Nama_Program_Studi }}</h6>
                                                </div>
                                            </div>
                                            <div class="col-2 d-flex justify-content-end">
                                                <h6 class="mb-0" style="margin-right: 10px;">{{ $prodi->Total }}</h6>
                                            </div>
                                            <div class="col-12">
                                                <div id="chart-europe"
                                                    data-jum-mhs-per-prodi='@json($JumMhsPerProdi)'>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="mb-0">Mahasiswa Per-Tahun</h4>
                                    <p class="p-0 m-0 mt-1">Mahasiswa Cuti</p>
                                </div>
                                <div class="card-body">
                                    <div id="MahasiswaPertahun-Cuti"
                                        data-jum-mhs-total-per-tahun='@json($JumMhsTotalPerTahun)'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


        </section>
    </div>
@endsection

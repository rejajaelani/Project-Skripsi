@extends('layouts.main')

@section('title-page')
    <title>Dashboard - Visualisasi Data Mahasiswa</title>
@endsection

@section('container-content')
    <style>
        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: transparent;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            height: 70%;
            object-fit: cover;
        }
    </style>

    <div class="page-heading d-flex justify-content-between w-100">
        @if (session('user'))
            <div class="wrapper-head-title">
                <h3>Welcome back, <span style="text-transform: capitalize;">{{ session('user')['username'] }}</span></h3>
                <p><strong>Dashboard/</strong></p>
            </div>
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
            @if (
                $UserAkses == 'Admin' ||
                    $UserAkses == 'Rektor' ||
                    $UserAkses == 'Teknologi dan Informatika' ||
                    $UserAkses == 'Bisnis dan Desain Kreatif')
                <form action="{{ route('dashboard') }}" method="GET" class="mb-5" id="form-select-filter">
                    <div class="row">
                        <div class="col-3">
                            <select name="select_data" id="select_data" class="form-select form-select-sm">
                                @if ($UserAkses == 'Admin' || $UserAkses == 'Rektor')
                                    <option value="" style="display: none;"></option>
                                    <option value="" disabled>~Universitas~</option>
                                    <option value="Rektor" {{ $HakAkses == 'Rektor' ? 'selected' : '' }}
                                        {{ $HakAkses != 'Rektor' ? 'style=display:none;' : '' }}>All Data</option>
                                    <option value="Admin" {{ $HakAkses == 'Admin' ? 'selected' : '' }}
                                        {{ $HakAkses != 'Admin' ? 'style=display:none;' : '' }}>All Data</option>
                                    <option value="" disabled>~Fakultas~</option>
                                    @foreach ($ListFakultas as $fakultas)
                                        <option value="{{ $fakultas->nama_fakultas }}"
                                            {{ $HakAkses == $fakultas->nama_fakultas ? 'selected' : '' }}>
                                            {{ $fakultas->nama_fakultas }}
                                        </option>
                                    @endforeach
                                    <option value="" disabled>~Prodi~</option>
                                    @foreach ($ListProdi as $prodi)
                                        <option value="{{ $prodi->nama_program_studi }}"
                                            {{ $HakAkses == $prodi->nama_program_studi ? 'selected' : '' }}>
                                            {{ $prodi->nama_program_studi }}
                                        </option>
                                    @endforeach
                                @elseif ($UserAkses == 'Teknologi dan Informatika')
                                    <option value="" disabled>~Fakultas~</option>
                                    <option value="Teknologi dan Informatika"
                                        {{ $HakAkses == 'Teknologi dan Informatika' ? 'selected' : '' }}>Teknologi dan
                                        Informatika</option>
                                    <option value="" disabled>~Prodi~</option>
                                    <option value="Teknik Informatika"
                                        {{ $HakAkses == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika
                                    </option>
                                    <option value="Rekayasa Sistem Komputer"
                                        {{ $HakAkses == 'Rekayasa Sistem Komputer' ? 'selected' : '' }}>Rekayasa Sistem
                                        Komputer</option>
                                    <option value="Sistem Komputer" {{ $HakAkses == 'Sistem Komputer' ? 'selected' : '' }}>
                                        Sistem Komputer</option>
                                @elseif ($UserAkses == 'Bisnis dan Desain Kreatif')
                                    <option value="" disabled>~Fakultas~</option>
                                    <option value="Bisnis dan Desain Kreatif"
                                        {{ $HakAkses == 'Bisnis dan Desain Kreatif' ? 'selected' : '' }}>Bisnis dan Desain
                                        Kreatif</option>
                                    <option value="" disabled>~Prodi~</option>
                                    <option value="Bisnis Digital" {{ $HakAkses == 'Bisnis Digital' ? 'selected' : '' }}>
                                        Bisnis Digital</option>
                                    <option value="Desain Komunikasi Visual"
                                        {{ $HakAkses == 'Desain Komunikasi Visual' ? 'selected' : '' }}>Desain Komunikasi
                                        Visual</option>
                                @endif
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
                            <option value="{{ $HakAkses }}" style="display: none" selected>{{ $HakAkses }}
                            </option>
                        </select>
                    </div>
                </div>
            @endif

            <div class="col-12">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="wrapper-left">
                                    <h4>Total Mahasiswa Per-Fakultas</h4>
                                    <p class="p-0 m-0 mt-1">Mahasiswa Aktif, Mahasiswa Non Aktif, dan Total Mahasiswa</p>
                                </div>
                                <div class="wrapper-right">
                                    <h4>{{ $JumMhsTotal->TotalMahasiswa }}</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="MahasiswaPertahun" data-jum-mhs-total-per-tahun='@json($JumMhsTotalPerTahun)'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 col-xl-4">
                        <div class="card" style="padding-bottom: 20px;">
                            <div class="card-header pb-0">
                                <h4 class="mb-0">Total Mahasiswa Per-Prodi</h4>
                            </div>
                            <div class="swiper mySwiper">
                                <div class="swiper-wrapper">
                                    @foreach ($JumMhsPerProdi as $prodi)
                                        @php
                                            $nameProdiWithoutS1 = str_replace('S1 ', '', $prodi->Nama_Program_Studi);
                                        @endphp
                                        <div class="swiper-slide">

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-10">
                                                        <div class="d-flex align-items-center">
                                                            <svg class="bi text-primary" width="32" height="32"
                                                                fill="blue" style="width:10px">
                                                                <use
                                                                    xlink:href="assets/images/bootstrap-icons.svg#circle-fill" />
                                                            </svg>
                                                            <h6 class="mb-0 ms-3">{{ $nameProdiWithoutS1 }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-2 d-flex justify-content-end">
                                                        <h6 class="mb-0" style="margin-right: 10px;">
                                                            {{ $prodi->Total }}
                                                        </h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <div id="prodi-chart-{{ \Illuminate\Support\Str::slug($nameProdiWithoutS1) }}"
                                                            data-jum-mhs-per-prodi='@json($RekapPerProdi[$nameProdiWithoutS1])'>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-8 col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Total Mahasiswa Per-Tahun</h4>
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
        </section>
    </div>
@endsection

@section('script-pages')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: '.swiper-pagination',
                    dynamicBullets: true,
                },
                allowTouchMove: false,
            });

            var swiperContainer = document.querySelector('.mySwiper');

            // Pause autoplay on mouseenter
            swiperContainer.addEventListener('mouseenter', function() {
                swiper.autoplay.stop();
                //swiper.allowTouchMove = true;
            });

            // Resume autoplay on mouseleave
            swiperContainer.addEventListener('mouseleave', function() {
                swiper.autoplay.start();
                //swiper.allowTouchMove = false;
            });
        });
    </script>
@endsection

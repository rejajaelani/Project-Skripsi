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
        <a href="{{ route('process.synchronizeTEST') }}" class="btn btn-outline-light"><i class="bi bi-bezier2"></i>&nbsp;SYNC
            MANUAL MAINTENANCE 1 TABLE</a>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <form action="" class="mb-5" id="form-select-filter">
            <div class="row">
                <div class="col-3">
                    <select name="selectProdi" id="selectProdi" class="form-select form-select-sm">
                        <option value="" style="display: none;"></option>
                        <option value="" disabled>~Universitas~</option>
                        <option value="All">All</option>
                        <option value="" disabled>~Fakultas~</option>
                        @foreach($ListFakultas as $fakultas)
                        <option value="{{ $fakultas->nama_fakultas }}">{{ $fakultas->nama_fakultas }}</option>
                        @endforeach
                        <option value="" disabled>~Prodi~</option>
                        @foreach($ListProdi as $prodi)
                        <option value="{{ $prodi->nama_program_studi }}">{{ $prodi->nama_program_studi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-send-fill"></i>&nbsp;FILTER</button>
                </div>
            </div>
        </form>

        <div class="col-12">
            <div class="row">
                <h5>Total Keseluruhan</h5>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="d-flex" style="gap: 10px;">
                                <div class="stats-icon green">
                                    <i class="iconly-boldShow"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted font-semibold">Mahasiswa Aktif</h6>
                                    <h6 class="font-extrabold mb-0">{{ $JumMhsTotal->Total_Mahasiswa_Aktif }}</h6>
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
                                    <h6 class="font-extrabold mb-0">{{ $JumMhsTotal->Total_Mahasiswa_Cuti }}</h6>
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
                                    <h6 class="font-extrabold mb-0">{{ $JumMhsTotal->Total_Mahasiswa_NonAktif }}</h6>
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
                                    <h6 class="font-extrabold mb-0">{{ $JumMhsTotal->TotalMahasiswa }}</h6>
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
                            <h4>Total Mahasiswa Per-Tahun</h4>
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
                                                    <svg class="bi text-primary" width="32" height="32" fill="blue" style="width:10px">
                                                        <use xlink:href="assets/images/bootstrap-icons.svg#circle-fill" />
                                                    </svg>
                                                    <h6 class="mb-0 ms-3">{{ $nameProdiWithoutS1 }}</h6>
                                                </div>
                                            </div>
                                            <div class="col-2 d-flex justify-content-end">
                                                <h6 class="mb-0" style="margin-right: 10px;">{{ $prodi->Total }}</h6>
                                            </div>
                                            <div class="col-12">
                                                <div id="prodi-chart-{{ \Illuminate\Support\Str::slug($nameProdiWithoutS1) }}" data-jum-mhs-per-prodi='@json($RekapPerProdi[$nameProdiWithoutS1])'>
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
                            <div id="MahasiswaPertahun-Cuti" data-jum-mhs-total-per-tahun='@json($JumMhsTotalPerTahun)'>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
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
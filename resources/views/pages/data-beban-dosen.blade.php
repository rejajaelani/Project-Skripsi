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
        <div class="row">
            <div class="col-9 col-lg-9 col-md-12">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="d-flex" style="gap: 10px;">
                                    <div class="stats-icon green">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted font-semibold">Dosen Aktif</h6>
                                        @foreach ($DosenAktif as $total)
                                            <h6 class="font-extrabold mb-0">{{ $total->TotalDosen }}</h6>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="d-flex" style="gap: 10px;">
                                    <div class="stats-icon blue">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted font-semibold">Dosen Tidak Aktif</h6>
                                        @foreach ($DosenTidakAktif as $total)
                                            <h6 class="font-extrabold mb-0">{{ $total->TotalDosen }}</h6>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="d-flex" style="gap: 10px;">
                                    <div class="stats-icon bg-warning">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted font-semibold">Dosen Ijin</h6>
                                        @foreach ($DosenIjin as $total)
                                            <h6 class="font-extrabold mb-0">{{ $total->TotalDosen }}</h6>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="d-flex" style="gap: 10px;">
                                    <div class="stats-icon purple">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted font-semibold">Total Jumlah Dosen</h6>
                                        @foreach ($TotalDosen as $total)
                                            <h6 class="font-extrabold mb-0">{{ $total->TotalDosen }}</h6>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 col-lg-3 col-md-12">
                <div class="card">
                    <div class="card-header pb-0" style="margin-bottom: 13px;">
                        <h4>Perbandingan Dosen</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-visitors-profile"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-4">List Dosen</h5>
                        <table class="table table-bordered table-striped" id="tableListDosen">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Nama Dosen</th>
                                    <th>Jumlah Mengajar Kelas</th>
                                    <th>Rencana Pertemuan</th>
                                    <th>Realisasi Pertemuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($ListDosenAktifitas as $DosenAktifitas)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>
                                            <button type="button"
                                                class="btn-detail-beban-dosen btn btn-sm btn-outline-primary"
                                                data-id="{{ $DosenAktifitas->id_dosen }}" data-bs-toggle="modal"
                                                data-bs-target="#ModalDetailDosen">Detail</button>
                                        </td>
                                        <td>{{ $DosenAktifitas->Nama_Dosen }}</td>
                                        <td>{{ $DosenAktifitas->Jumlah_Mengajar_Kelas }}</td>
                                        <td>{{ $DosenAktifitas->Rencana_Pertemuan }}</td>
                                        <td>{{ $DosenAktifitas->Realisasi_Pertemuan }}</td>
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

    <!-- Modal -->
    <div class="modal fade" id="ModalDetailDosen" tabindex="-1" aria-labelledby="ModalDetailDosenLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalDetailDosenLabel">Detail Beban Dosen</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card shadow">
                        <div class="card-body">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Nama Dosen</td>
                                        <td class="px-2">:</td>
                                        <td id="DTNamaDosen"></td>
                                    </tr>
                                    <tr>
                                        <td>NIDN</td>
                                        <td class="px-2">:</td>
                                        <td id="DTNidn"></td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td class="px-2">:</td>
                                        <td id="DTNip"></td>
                                    </tr>
                                    <tr>
                                        <td>Status Dosen</td>
                                        <td class="px-2">:</td>
                                        <td><span id="DTStatusDosen" style="font-weight: bold;"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <h6 class="mb-3">List Kelas Diajar</h6>
                            <table class="table table-bordered table-striped table-sm" id="tableDosenKelasDiajar">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NO</th>
                                        <th>NO</th>
                                        <th>NO</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <h6 class="mb-3">List Mahasiswa Bimbingan</h6>
                            <table class="table table-bordered table-striped table-sm"
                                id="tableDosenListMahasiswaBimbingan">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NO</th>
                                        <th>NO</th>
                                        <th>NO</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary rounded-0 btn-sm"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-pages')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#tableListDosen').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
            $('#tableDosenKelasDiajar').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
            $('#tableDosenListMahasiswaBimbingan').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });

            let TotalDosenCowok = {!! json_encode($TotalDosenCowok) !!};
            let TotalDosenCewek = {!! json_encode($TotalDosenCewek) !!};

            let optionsVisitorsProfile = {
                series: [TotalDosenCewek, TotalDosenCowok],
                labels: ["Perempuan", "Laki - laki"],
                colors: ["#435ebe", "#55c6e8"],
                chart: {
                    type: "donut",
                    width: "100%",
                    height: "200px",
                },
                legend: {
                    position: "bottom",
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: "30%",
                        },
                    },
                },
            };

            var chartVisitorsProfile = new ApexCharts(
                document.getElementById("chart-visitors-profile"),
                optionsVisitorsProfile
            );
            chartVisitorsProfile.render();

            $('.btn-detail-beban-dosen').click(function() {
                // Ambil data-id dari tombol
                let id = $(this).data('id');

                console.log(id);

                // AJAX call
                $.ajax({
                    url: '/api/get-detail-beban-dosen',
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}' // Sertakan CSRF token
                    },
                    success: function(response) {
                        console.log(response);
                        $('#DTNamaDosen').html(response.nama_dosen);
                        $('#DTNidn').html(response.nidn === '-' || response.nidn == null ||
                            response
                            .nidn === '' ?
                            '-' :
                            response.nidn);
                        $('#DTNip').html(
                            response.nip === '-' || response.nip == null || response.nip ===
                            '' ?
                            '-' :
                            response.nip
                        );
                        $('#DTStatusDosen').html(response.nama_status_aktif);
                        if (response.nama_status_aktif == 'Aktif') {
                            $('#DTStatusDosen').addClass('text-success');
                            $('#DTStatusDosen').removeClass('text-secondary');
                            $('#DTStatusDosen').removeClass('text-warning');
                        } else if (response.nama_status_aktif == 'Tidak Aktif') {
                            $('#DTStatusDosen').addClass('text-secondary');
                            $('#DTStatusDosen').removeClass('text-success');
                            $('#DTStatusDosen').removeClass('text-warning');
                        } else {
                            $('#DTStatusDosen').addClass('text-warning');
                            $('#DTStatusDosen').removeClass('text-secondary');
                            $('#DTStatusDosen').removeClass('text-success');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
@endsection

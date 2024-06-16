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
                                <button type="button"
                                    style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;"
                                    data-bs-toggle="modal" data-bs-target="#ModalListDosenAktif"><i
                                        class="bi bi-box-arrow-down-right"></i></button>
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
                                <button type="button"
                                    style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;"
                                    data-bs-toggle="modal" data-bs-target="#ModalListDosenTidakAktif"><i
                                        class="bi bi-box-arrow-down-right"></i></button>
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
                                <button type="button"
                                    style="position: absolute;bottom: 5px;right: 5px;border: 0px;background: transparent;"
                                    data-bs-toggle="modal" data-bs-target="#ModalListDosenIjin"><i
                                        class="bi bi-box-arrow-down-right"></i></button>
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
                                @foreach ($ListDosen as $Dosen)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>
                                            <form action="{{ route('detail-data-beban-dosen') }}" method="GET">
                                                <input type="hidden" name="id" id="id-dosen"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($Dosen->id_dosen) }}">
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-primary">Detail</button>
                                            </form>
                                        </td>
                                        <td>{{ $Dosen->Nama_Dosen }}</td>
                                        <td>{{ $Dosen->Jumlah_Mengajar_Kelas }}</td>
                                        <td>{{ $Dosen->Rencana_Pertemuan }}</td>
                                        <td>{{ $Dosen->Realisasi_Pertemuan }}</td>
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

    <!-- List Modal -->

    <!-- Modal Detail Beban Dosen -->
    <div class="modal fade" id="ModalDetailDosen" tabindex="-1" aria-labelledby="ModalDetailDosenLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalDetailDosenLabel">Detail Beban Dosen</h1>
                    <button type="button" class="btn-closeModalDetailDosen btn-close" aria-label="Close"></button>
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
                                        <th>Mata Kuliah</th>
                                        <th>Kode Mata Kuliah</th>
                                        <th>Prodi</th>
                                        <th>Kelas</th>
                                        <th>Jumlah SKS</th>
                                        <th>Rencana Pertemuan</th>
                                        <th>Realisasi Pertemuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Tabel body akan diisi dengan jQuery -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Tambahan untuk List Mahasiswa Bimbingan jika diperlukan -->
                    <div class="card shadow">
                        <div class="card-body">
                            <h6 class="mb-3">List Mahasiswa Bimbingan</h6>
                            <table class="table table-bordered table-striped table-sm"
                                id="tableDosenListMahasiswaBimbingan">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>NIM</th>
                                        <th>Prodi</th>
                                        <th>Pembimbing Ke</th>
                                        <th>Aktifitas Bimbingan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Tabel body akan diisi dengan jQuery -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn-closeModalDetailDosen btn btn-outline-secondary rounded-0 btn-sm">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal List Dosen Aktif -->
    <div class="modal fade" id="ModalListDosenAktif" tabindex="-1" aria-labelledby="ModalListDosenAktifLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalListDosenAktifLabel">List Dosen Aktif</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card shadow">
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-sm" id="tableListDosenAktif">
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
                                    @foreach ($ListDosenAktif as $Dosen)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>
                                                <form action="{{ route('detail-data-beban-dosen') }}" method="GET">
                                                    <input type="hidden" name="id" id="id-dosen"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($Dosen->id_dosen) }}">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-primary">Detail</button>
                                                </form>
                                            </td>
                                            <td>{{ $Dosen->Nama_Dosen }}</td>
                                            <td>{{ $Dosen->Jumlah_Mengajar_Kelas }}</td>
                                            <td>{{ $Dosen->Rencana_Pertemuan }}</td>
                                            <td>{{ $Dosen->Realisasi_Pertemuan }}</td>
                                        </tr>
                                        <?php $no++; ?>
                                    @endforeach
                                </tbody>
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

    <!-- Modal List Dosen Tidak Aktif -->
    <div class="modal fade" id="ModalListDosenTidakAktif" tabindex="-1" aria-labelledby="ModalListDosenTidakAktifLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalListDosenTidakAktifLabel">List Dosen Tidak Aktif</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card shadow">
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-sm" id="tableListDosenTidakAktif">
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
                                    @foreach ($ListDosenTidakAktif as $Dosen)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>
                                                <form action="{{ route('detail-data-beban-dosen') }}" method="GET">
                                                    <input type="hidden" name="id" id="id-dosen"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($Dosen->id_dosen) }}">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-primary">Detail</button>
                                                </form>
                                            </td>
                                            <td>{{ $Dosen->Nama_Dosen }}</td>
                                            <td>{{ $Dosen->Jumlah_Mengajar_Kelas }}</td>
                                            <td>{{ $Dosen->Rencana_Pertemuan }}</td>
                                            <td>{{ $Dosen->Realisasi_Pertemuan }}</td>
                                        </tr>
                                        <?php $no++; ?>
                                    @endforeach
                                </tbody>
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

    <!-- Modal List Dosen Ijin -->
    <div class="modal fade" id="ModalListDosenIjin" tabindex="-1" aria-labelledby="ModalListDosenIjinLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalListDosenIjinLabel">List Dosen Ijin</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card shadow">
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-sm" id="tableListDosenIjin">
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
                                    @foreach ($ListDosenIjin as $Dosen)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>
                                                <form action="{{ route('detail-data-beban-dosen') }}" method="GET">
                                                    <input type="hidden" name="id" id="id-dosen"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($Dosen->id_dosen) }}">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-primary">Detail</button>
                                                </form>
                                            </td>
                                            <td>{{ $Dosen->Nama_Dosen }}</td>
                                            <td>{{ $Dosen->Jumlah_Mengajar_Kelas }}</td>
                                            <td>{{ $Dosen->Rencana_Pertemuan }}</td>
                                            <td>{{ $Dosen->Realisasi_Pertemuan }}</td>
                                        </tr>
                                        <?php $no++; ?>
                                    @endforeach
                                </tbody>
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
            $('#tableListDosenAktif').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
            $('#tableListDosenTidakAktif').DataTable({
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });
            $('#tableListDosenIjin').DataTable({
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

                        // Mengisi detail dosen
                        $('#DTNamaDosen').html(response.DataDosen.nama_dosen);
                        $('#DTNidn').html(
                            response.DataDosen.nidn === '-' || response.DataDosen.nidn ==
                            null || response.DataDosen.nidn === '' ?
                            '-' :
                            response.DataDosen.nidn
                        );
                        $('#DTNip').html(
                            response.DataDosen.nip === '-' || response.DataDosen.nip ==
                            null || response.DataDosen.nip === '' ?
                            '-' :
                            response.DataDosen.nip
                        );
                        $('#DTStatusDosen').html(response.DataDosen.nama_status_aktif);

                        // Mengatur kelas status dosen berdasarkan status
                        if (response.DataDosen.nama_status_aktif == 'Aktif') {
                            $('#DTStatusDosen').addClass('text-success').removeClass(
                                'text-secondary text-warning');
                        } else if (response.DataDosen.nama_status_aktif == 'Tidak Aktif') {
                            $('#DTStatusDosen').addClass('text-secondary').removeClass(
                                'text-success text-warning');
                        } else {
                            $('#DTStatusDosen').addClass('text-warning').removeClass(
                                'text-secondary text-success');
                        }

                        // Mengisi tabel List Kelas Diajar
                        let tableRows1 = '';
                        response.ListKelasDiajar.forEach((kelas, index) => {
                            tableRows1 += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${kelas.NamaMatkul}</td>
                                    <td>${kelas.KodeMatkul}</td>
                                    <td>${kelas.NamaProdi}</td>
                                    <td>${kelas.Kelas}</td>
                                    <td>${kelas.JumlahSKS}</td>
                                    <td>${kelas.RencanaPertemuan}</td>
                                    <td>${kelas.RealisasiPertemuan}</td>
                                </tr>
                            `;
                        });
                        $('#tableDosenKelasDiajar tbody').html(tableRows1);

                        // Mengisi tabel List Mahasiswa Bimbingan
                        let tableRows2 = '';
                        response.ListMahasiswaBimbingan.forEach((mahasiswa, index) => {
                            tableRows2 += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${mahasiswa.NamaMahasiswa}</td>
                                    <td>${mahasiswa.Nim}</td>
                                    <td>${mahasiswa.ProdiMahasiswa}</td>
                                    <td>${mahasiswa.PembimbingKe}</td>
                                    <td>${mahasiswa.AktivitasBimbingan}</td>
                                </tr>
                            `;
                        });
                        $('#tableDosenListMahasiswaBimbingan tbody').html(tableRows2);

                        // Menampilkan modal
                        $("#ModalDetailDosen").css('z-index', '9999');
                        const detailModal = new bootstrap.Modal(document.getElementById(
                            'ModalDetailDosen'));
                        detailModal.show();

                        // Event untuk tombol close
                        $('.btn-closeModalDetailDosen').on('click', function() {
                            $('#ModalDetailDosen').modal('hide');
                            removeModalBackdrop();
                        });

                        // Fungsi untuk menghapus backdrop modal
                        function removeModalBackdrop() {
                            if ($('#ModalListDosenAktif').hasClass('show') || $(
                                    '#ModalListDosenTidakAktif').hasClass('show') || $(
                                    '#ModalListDosenIjin').hasClass('show')) {
                                $('.modal-backdrop').not(':first').remove();
                            } else {
                                $('.modal-backdrop').remove();
                                $('body').css('overflow', 'auto');
                            }
                        }

                        // Event ketika modal ditutup
                        $('#ModalDetailDosen').on('hidden.bs.modal', function() {
                            removeModalBackdrop();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
@endsection

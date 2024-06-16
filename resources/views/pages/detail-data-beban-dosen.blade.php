@extends('layouts.main')

@section('title-page')
    <title>Data Beban Dosen - Visualisasi Data Mahasiswa</title>
@endsection

@section('container-content')
    <div class="page-heading d-flex justify-content-between w-100">
        @if (session('user'))
            <div class="wrapper-head-titile">
                <h3>Welcome back, <span style="text-transform: capitalize;">{{ session('user')['username'] }}</span></h3>
                <p><strong><a href="{{ route('data-beban-dosen') }}">Data Beban Dosen</a> / Detail Beban Dosen</strong></p>
            </div>
            {{-- {{ dd(session('user')) }} --}}
        @else
            <h3>User not logged in</h3>
        @endif
    </div>
    <div class="page-content">
        <div class="row">
            <dic class="col">
                <div class="card">
                    <div class="card-body">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Nama Dosen</td>
                                    <td class="px-2">:</td>
                                    <td>{{ $DataDosen->nama_dosen }}</td>
                                </tr>
                                <tr>
                                    <td>NIDN</td>
                                    <td class="px-2">:</td>
                                    <td>{{ $DataDosen->nidn }}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td class="px-2">:</td>
                                    <td>{{ $DataDosen->nip }}</td>
                                </tr>
                                <tr>
                                    <td>Status Dosen</td>
                                    <td class="px-2">:</td>
                                    <td><span class="badge badge-sm bg-success">{{ $DataDosen->nama_status_aktif }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </dic>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
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
                                <?php $no = 1; ?>
                                @foreach ($ListKelasDiajar as $kelasDiajar)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $kelasDiajar->NamaMatkul }}</td>
                                        <td>{{ $kelasDiajar->KodeMatkul }}</td>
                                        <td>{{ $kelasDiajar->NamaProdi }}</td>
                                        <td>{{ $kelasDiajar->Kelas }}</td>
                                        <td>{{ $kelasDiajar->JumlahSKS }}</td>
                                        <td>{{ $kelasDiajar->RencanaPertemuan }}</td>
                                        <td>{{ $kelasDiajar->RealisasiPertemuan }}</td>
                                    </tr>
                                    <?php $no++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">List Mahasiswa Bimbingan</h6>
                        <table class="table table-bordered table-striped table-sm" id="tableDosenListMahasiswaBimbingan">
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
                                <?php $no = 1; ?>
                                @foreach ($ListMahasiswaBimbingan as $mahasiswa)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $mahasiswa->NamaMahasiswa }}</td>
                                        <td>{{ $mahasiswa->Nim }}</td>
                                        <td>{{ $mahasiswa->ProdiMahasiswa }}</td>
                                        <td>{{ $mahasiswa->PembimbingKe }}</td>
                                        <td>{{ $mahasiswa->AktivitasBimbingan }}</td>
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
@endsection

@section('script-pages')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
@endsection

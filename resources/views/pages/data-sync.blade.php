@extends('layouts.main')

@section('title-page')
    <title>Data Synchronization - Visualisasi Data Mahasiswa</title>
@endsection

@section('container-content')
    <div class="page-heading d-flex justify-content-between w-100">
        @if (session('user'))
            <div class="wrapper-head-titile">
                <h3>Hello, <span style="text-transform: capitalize;">{{ session('user')['username'] }}</span></h3>
                <p><strong>Data Synchronization/</strong></p>
            </div>
            {{-- {{ dd(session('user')) }} --}}
        @else
            <h3>User not logged in</h3>
        @endif
    </div>


    <div class="page-content" style="height: 65vh;border-radius: 20px;">
        <div id="wrapper-btn-all" class="d-flex" style="gap: 20px;">

            {{-- <div id="card-generate-auto-table" class="card" style="width: max-content;">
                <div class="card-header pb-0">
                    <div class="wrapper-btn-sync d-flex" style="align-items: center;gap: 10px;">
                        <button type="button" id="btn-generate-auto-table" class="btn btn-outline-primary mb-2">Generate
                            Auto Table</button>
                        <img src="{{ asset('Images/sync-loading.gif') }}" alt="sync-data-loading"
                            id="generate-auto-table-loading" style="margin-top: -10px;height: 60px;opacity: 0;">
                    </div>
                </div>
                <div id="wrapper-loading-generate-auto-table" class="card-body" style="height: 0px !important;">
                    <div id="progress-generate-auto-table" class="progress" style="border-radius: 20px;opacity: 0;"
                        role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0"
                        aria-valuemax="100">
                        <div id="progress-bar-generate-auto-table" class="progress-bar"
                            style="width: 0%;border-radius: 20px;"></div>
                    </div>
                    <p id="wrapper-text-progress0" class="m-0 p-0 mt-2"
                        style="font-size: 14px;opacity: 0.5;overflow: hidden;opacity: 0;"><span><span
                                id="countProgress0"></span>/<span id="countTotalProgress0"></span></span> <span
                            id="nameTable0"></span></p>
                </div>
            </div> --}}

            <div id="card-sync-data-master" class="card" style="width: max-content;">
                <div class="card-header pb-0">
                    <div class="wrapper-btn-sync d-flex" style="align-items: center;gap: 10px;">
                        <button type="button" id="btn-master-data-sync" class="btn btn-outline-primary mb-2">Master Data
                            Synchronization</button>
                        <img src="{{ asset('Images/sync-loading.gif') }}" alt="sync-data-loading"
                            id="sync-data-master-loading" style="margin-top: -10px;height: 60px;opacity: 0;">
                    </div>
                </div>
                <div id="wrapper-loading-data-master" class="card-body" style="height: 0px !important;">
                    <div id="progress-data-master" class="progress" style="border-radius: 20px;opacity: 0;"
                        role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0"
                        aria-valuemax="100">
                        <div id="progress-bar-data-master" class="progress-bar" style="width: 10%;border-radius: 20px;">
                        </div>
                    </div>
                    <p id="wrapper-text-progress1" class="m-0 p-0 mt-2"
                        style="font-size: 14px;opacity: 0.5;overflow: hidden;opacity: 0;"><span><span
                                id="countProgress1"></span>/<span id="countTotalProgress1"></span></span> <span
                            id="nameTable1"></span></p>
                </div>
            </div>

            <div id="card-sync-data-semester" class="card" style="width: max-content;">
                <div class="card-header pb-0">
                    <div class="wrapper-btn-sync d-flex" style="align-items: center;gap: 10px;">
                        <button type="button" id="btn-semester-data-sync" class="btn btn-outline-primary mb-2">Semester
                            Data Synchronization</button>
                        <img src="{{ asset('Images/sync-loading.gif') }}" alt="sync-data-loading"
                            id="sync-data-semester-loading" style="margin-top: -10px;height: 60px;opacity: 0;">
                    </div>
                </div>
                <div id="wrapper-loading-data-semester" class="card-body" style="height: 0px !important;">
                    <div id="progress-data-semester" class="progress" style="border-radius: 20px;opacity: 0;"
                        role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0"
                        aria-valuemax="100">
                        <div id="progress-bar-data-semester" class="progress-bar" style="width: 10%;border-radius: 20px;">
                        </div>
                    </div>
                    <p id="wrapper-text-progress2" class="m-0 p-0 mt-2"
                        style="font-size: 14px;opacity: 0.5;overflow: hidden;opacity: 0;"><span><span
                                id="countProgress2"></span>/<span id="countTotalProgress2"></span></span> <span
                            id="nameTable2"></span></p>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script-pages')
    <script>
        $(document).ready(function() {

            // $("#btn-generate-auto-table").click(function() {
            //     $(this).prop("disabled", true);
            //     $("#wrapper-btn-all").removeClass("d-flex");
            //     $("#btn-semester-data-sync").prop("disabled", true);
            //     $("#btn-master-data-sync").prop("disabled", true);
            //     $("#card-generate-auto-table").css({
            //         "width": "100%",
            //         "transition": "opacity 1s ease-in-out"
            //     });
            //     $("#wrapper-loading-generate-auto-table").css({
            //         "height": "max-content",
            //         "transition": "opacity 1s ease-in-out"
            //     });
            //     $("#generate-auto-table-loading, #progress-generate-auto-table, #wrapper-text-progress0")
            //         .css({
            //             "opacity": "1",
            //             "transition": "opacity 1s ease-in-out"
            //         });

            //     //Generate Data Master Table List
            //     var tableArray = [

            //         //id_periode
            //         "GetListMahasiswa", "GetKRSMahasiswa", "GetRekapKRSMahasiswa",
            //         "GetRekapKHSMahasiswa", "GetAktivitasMengajarDosen", "GetRiwayatNilaiMahasiswa",
            //         "GetMahasiswaBimbinganDosen", "GetRekapIPSMahasiswa", "GetListBimbingMahasiswa",
            //         "GetDetailPerkuliahanMahasiswa",

            //         //id_semester
            //         "GetAktivitasKuliahMahasiswa", "GetListKurikulum", "GetDosenPengajarKelasKuliah",

            //         //id_periode_keluar
            //         "GetListMahasiswaLulusDO", "GetDetailMahasiswaLulusDO"

            //     ];

            //     var count = 0;
            //     var delay = 1000; // Delay antara setiap permintaan (dalam milidetik)

            //     setTimeout(function() {

            //         $("#countTotalProgress0").html(tableArray.length);

            //         tableArray.forEach(function(item, index) {
            //             setTimeout(function() {
            //                 $.ajax({
            //                     url: 'api/test-api',
            //                     method: 'POST',
            //                     dataType: 'json',
            //                     data: {
            //                         table: item
            //                     },
            //                     success: function(response) {
            //                         if (response.data.status ===
            //                             "success") {
            //                             count++;
            //                             var persentase = (count /
            //                                 tableArray.length) * 100;
            //                             $('#progress-bar-generate-auto-table')
            //                                 .css('width', persentase +
            //                                     '%');
            //                             $("#countProgress0").html(
            //                                 count);
            //                             $("#nameTable0").html(response
            //                                 .data.name_table);
            //                         }

            //                         if (count == tableArray.length) {
            //                             setTimeout(function() {
            //                                 $("#nameTable0")
            //                                     .html(
            //                                         "All Table Successfully Created!"
            //                                     );
            //                             }, 2000);
            //                             $("#generate-auto-table-loading")
            //                                 .addClass("d-none");
            //                             $("#btn-semester-data-sync")
            //                                 .prop("disabled", false);
            //                             $("#btn-master-data-sync").prop(
            //                                 "disabled", false);
            //                         }
            //                     },
            //                     error: function(xhr, status, error) {
            //                         console.error(error);
            //                     }
            //                 });
            //             }, index * delay); // Delay yang bertambah setiap iterasi
            //         });
            //     }, 2000);

            // });

            $("#btn-master-data-sync").click(function() {
                $(this).prop("disabled", true);
                $("#wrapper-btn-all").removeClass("d-flex");
                $("#btn-semester-data-sync").prop("disabled", true);
                $("#card-sync-data-master").css({
                    "width": "100%",
                    "transition": "opacity 1s ease-in-out"
                });
                $("#wrapper-loading-data-master").css({
                    "height": "max-content",
                    "transition": "opacity 1s ease-in-out"
                });
                $("#sync-data-master-loading, #progress-data-master, #wrapper-text-progress1").css({
                    "opacity": "1",
                    "transition": "opacity 1s ease-in-out"
                });

                //Sync Data Master List Get
                var tableArray = [
                    "GetProdi", "GetFakultas", "GetDetailKurikulum", "GetListKelasKuliah",
                    "GetDetailKelasKuliah", "GetListPeriodePerkuliahan", "GetDetailPeriodePerkuliahan",
                    "GetListDosen", "GetRekapJumlahDosen", "GetListPenugasanDosen",
                    "GetRekapJumlahMahasiswa", "GetDataTerhapus", "GetListMataKuliah",
                    "GetDetailMataKuliah", "GetBiodataMahasiswa",
                    "GetPerhitunganSKS", "GetListPrestasiMahasiswa", "GetDosenPembimbing",
                    "GetJenisAktivitasMahasiswa", "GetJenisKeluar", "GetStatusMahasiswa"
                ];

                var count = 0;
                var delay = 1000; // Delay antara setiap permintaan (dalam milidetik)

                setTimeout(function() {

                    $("#countTotalProgress1").html(tableArray.length);

                    tableArray.forEach(function(item, index) {
                        setTimeout(function() {
                            $.ajax({
                                url: 'api/sync-data-master',
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    table: item,
                                },
                                success: function(response) {
                                    console.log(response);

                                    if (response.data.status ===
                                        "success") {
                                        count++;
                                        var persentase = (count /
                                            tableArray.length) * 100;
                                        $('#progress-bar-data-master')
                                            .css('width', persentase +
                                                '%');
                                        $("#countProgress1").html(
                                            count);
                                        $("#nameTable1").html(response
                                            .data.name_table +
                                            "<strong> Success</strong>"
                                        );
                                    }

                                    if (count == tableArray.length) {
                                        setTimeout(function() {
                                            $("#nameTable1")
                                                .html(
                                                    "All Data Master Successfully Sync!"
                                                );
                                        }, 2000);
                                        $("#sync-data-master-loading")
                                            .addClass("d-none");
                                        $("#btn-semester-data-sync")
                                            .prop("disabled", false);
                                        $("#btn-generate-auto-table")
                                            .prop(
                                                "disabled", false);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error(error);
                                }
                            });
                        }, index * delay); // Delay yang bertambah setiap iterasi
                    });
                }, 2000);
            });

            $("#btn-semester-data-sync").click(function() {
                $(this).prop("disabled", true);
                $("#wrapper-btn-all").removeClass("d-flex");
                $("#btn-master-data-sync").prop("disabled", true);
                $("#card-sync-data-semester").css({
                    "width": "100%",
                    "transition": "opacity 1s ease-in-out"
                });
                $("#wrapper-loading-data-semester").css({
                    "height": "max-content",
                    "transition": "opacity 1s ease-in-out"
                });
                $("#sync-data-semester-loading, #progress-data-semester, #wrapper-text-progress2").css({
                    "opacity": "1",
                    "transition": "opacity 1s ease-in-out"
                });

                //Sync Data Semester List Get
                var tableArray = [
                    //id_periode
                    "GetListMahasiswa", "GetKRSMahasiswa", "GetRekapKRSMahasiswa",
                    "GetRekapKHSMahasiswa", "GetAktivitasMengajarDosen", "GetRiwayatNilaiMahasiswa",
                    "GetMahasiswaBimbinganDosen", "GetRekapIPSMahasiswa", "GetListBimbingMahasiswa",
                    "GetDetailPerkuliahanMahasiswa",

                    //id_semester
                    "GetListAktivitasMahasiswa", "GetAktivitasKuliahMahasiswa", "GetListKurikulum",
                    "GetDosenPengajarKelasKuliah",

                    //id_periode_keluar
                    "GetListMahasiswaLulusDO", "GetDetailMahasiswaLulusDO"
                ];

                var count = 0;
                var delay = 1000; // Delay antara setiap permintaan (dalam milidetik)

                setTimeout(function() {

                    $("#countTotalProgress2").html(tableArray.length);

                    tableArray.forEach(function(item, index) {
                        setTimeout(function() {
                            $.ajax({
                                url: 'api/sync-data-semester',
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    table: item,
                                },
                                success: function(response) {
                                    console.log(response);

                                    if (response.data.status ===
                                        "success") {
                                        count++;
                                        var persentase = (count /
                                            tableArray.length) * 100;
                                        $('#progress-bar-data-semester')
                                            .css('width', persentase +
                                                '%');
                                        $("#countProgress2").html(
                                            count);
                                        $("#nameTable2").html(response
                                            .data.name_table +
                                            "<strong> Success</strong>"
                                        );
                                    }

                                    if (count == tableArray.length) {
                                        setTimeout(function() {
                                            $("#nameTable2")
                                                .html(
                                                    "All Data Semester Successfully Sync!"
                                                );
                                        }, 2000);
                                        $("#sync-data-semester-loading")
                                            .addClass("d-none");
                                        $("#btn-master-data-sync")
                                            .prop("disabled", false);
                                        $("#btn-generate-auto-table")
                                            .prop(
                                                "disabled", false);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error(error);
                                }
                            });
                        }, index * delay); // Delay yang bertambah setiap iterasi
                    });
                }, 2000);
            });

        });
    </script>
@endsection

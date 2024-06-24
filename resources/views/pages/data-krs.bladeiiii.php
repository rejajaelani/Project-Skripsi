@extends('layouts.main')

@section('title-page')
    <title>Data KRS - Visualisasi Data Mahasiswa</title>
@endsection

@section('container-content')
    <div class="page-heading d-flex justify-content-between w-100">
        @if (session('user'))
            <div class="wrapper-head-titile">
                <h3>Welcome back, <span style="text-transform: capitalize;">{{ session('user')['username'] }}</span></h3>
                <p><strong>Data Mahasiswa / <a href="{{ route('data-krs') }}">Data KRS</a></strong></p>
            </div>
            {{-- {{ dd(session('user')) }} --}}
        @else
            <h3>User not logged in</h3>
        @endif
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Already KRS</h6>
                                        <h6 class="font-extrabold mb-0">112.000</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldClose-Square"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Not yet KRS</h6>
                                        <h6 class="font-extrabold mb-0">183.000</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldActivity"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Paid</h6>
                                        <h6 class="font-extrabold mb-0">80.000</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon red mb-2">
                                            <i class="iconly-boldBookmark"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Unpaid</h6>
                                        <h6 class="font-extrabold mb-0">112</h6>
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
                                <h4>Jumlah Mahasiswa KRS <span class="badge badge-sm bg-secondary">Per-Prodi</span></h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-profile-visit"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                Simple Datatable
                            </div>
                            <div class="card-body">
                                <table class="table" id="table1">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>City</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Graiden</td>
                                            <td>vehicula.aliquet@semconsequat.co.uk</td>
                                            <td>076 4820 8838</td>
                                            <td>Offenburg</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Dale</td>
                                            <td>fringilla.euismod.enim@quam.ca</td>
                                            <td>0500 527693</td>
                                            <td>New Quay</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nathaniel</td>
                                            <td>mi.Duis@diam.edu</td>
                                            <td>(012165) 76278</td>
                                            <td>Grumo Appula</td>
                                            <td>
                                                <span class="badge bg-danger">Inactive</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Darius</td>
                                            <td>velit@nec.com</td>
                                            <td>0309 690 7871</td>
                                            <td>Ways</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Oleg</td>
                                            <td>rhoncus.id@Aliquamauctorvelit.net</td>
                                            <td>0500 441046</td>
                                            <td>Rossignol</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kermit</td>
                                            <td>diam.Sed.diam@anteVivamusnon.org</td>
                                            <td>(01653) 27844</td>
                                            <td>Patna</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Jermaine</td>
                                            <td>sodales@nuncsit.org</td>
                                            <td>0800 528324</td>
                                            <td>Mold</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ferdinand</td>
                                            <td>gravida.molestie@tinciduntadipiscing.org</td>
                                            <td>(016977) 4107</td>
                                            <td>Marlborough</td>
                                            <td>
                                                <span class="badge bg-danger">Inactive</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kuame</td>
                                            <td>Quisque.purus@mauris.org</td>
                                            <td>(0151) 561 8896</td>
                                            <td>Tresigallo</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deacon</td>
                                            <td>Duis.a.mi@sociisnatoquepenatibus.com</td>
                                            <td>07740 599321</td>
                                            <td>Karapınar</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Channing</td>
                                            <td>tempor.bibendum.Donec@ornarelectusante.ca</td>
                                            <td>0845 46 49</td>
                                            <td>Warrnambool</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Aladdin</td>
                                            <td>sem.ut@pellentesqueafacilisis.ca</td>
                                            <td>0800 1111</td>
                                            <td>Bothey</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cruz</td>
                                            <td>non@quisturpisvitae.ca</td>
                                            <td>07624 944915</td>
                                            <td>Shikarpur</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Keegan</td>
                                            <td>molestie.dapibus@condimentumDonecat.edu</td>
                                            <td>0800 200103</td>
                                            <td>Assen</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ray</td>
                                            <td>placerat.eget@sagittislobortis.edu</td>
                                            <td>(0112) 896 6829</td>
                                            <td>Hofors</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Maxwell</td>
                                            <td>diam@dapibus.org</td>
                                            <td>0334 836 4028</td>
                                            <td>Thane</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Carter</td>
                                            <td>urna.justo.faucibus@orci.com</td>
                                            <td>07079 826350</td>
                                            <td>Biez</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Stone</td>
                                            <td>velit.Aliquam.nisl@sitametrisus.com</td>
                                            <td>0800 1111</td>
                                            <td>Olivar</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Berk</td>
                                            <td>fringilla.porttitor.vulputate@taciti.edu</td>
                                            <td>(0101) 043 2822</td>
                                            <td>Sanquhar</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Philip</td>
                                            <td>turpis@euenimEtiam.org</td>
                                            <td>0500 571108</td>
                                            <td>Okara</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kibo</td>
                                            <td>feugiat@urnajustofaucibus.co.uk</td>
                                            <td>07624 682306</td>
                                            <td>La Cisterna</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bruno</td>
                                            <td>elit.Etiam.laoreet@luctuslobortisClass.edu</td>
                                            <td>07624 869434</td>
                                            <td>Rocca d"Arce</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Leonard</td>
                                            <td>blandit.enim.consequat@mollislectuspede.net</td>
                                            <td>0800 1111</td>
                                            <td>Lobbes</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Hamilton</td>
                                            <td>mauris@diam.org</td>
                                            <td>0800 256 8788</td>
                                            <td>Sanzeno</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Harding</td>
                                            <td>Lorem.ipsum.dolor@etnetuset.com</td>
                                            <td>0800 1111</td>
                                            <td>Obaix</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Emmanuel</td>
                                            <td>eget.lacus.Mauris@feugiatSednec.org</td>
                                            <td>(016977) 8208</td>
                                            <td>Saint-Remy-Geest</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Mahasiswa KRS</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-data-krs-paid"></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Mahasiswa KRS</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-data-krs-gendre"></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Haven't done KRS yet</h4>
                    </div>
                    <div class="card-content pb-4">
                        <div class="recent-message d-flex px-4 py-3">
                            <div class="avatar avatar-lg">
                                <img src="{{ asset('assets/images/faces/4.jpg') }}">
                            </div>
                            <div class="name ms-4">
                                <h5 class="mb-1">Hank Schrader</h5>
                                <h6 class="text-muted mb-0">@johnducky</h6>
                            </div>
                        </div>
                        <div class="recent-message d-flex px-4 py-3">
                            <div class="avatar avatar-lg">
                                <img src="{{ asset('assets/images/faces/5.jpg') }}">
                            </div>
                            <div class="name ms-4">
                                <h5 class="mb-1">Dean Winchester</h5>
                                <h6 class="text-muted mb-0">@imdean</h6>
                            </div>
                        </div>
                        <div class="recent-message d-flex px-4 py-3">
                            <div class="avatar avatar-lg">
                                <img src="{{ asset('assets/images/faces/1.jpg') }}">
                            </div>
                            <div class="name ms-4">
                                <h5 class="mb-1">John Dodol</h5>
                                <h6 class="text-muted mb-0">@dodoljohn</h6>
                            </div>
                        </div>
                        <div class="px-4">
                            <button class='btn btn-block btn-xl btn-outline-primary font-bold mt-3'>View All</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

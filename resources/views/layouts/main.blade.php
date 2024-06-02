    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @yield('title-page')

        <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}">
        <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/png">

        <link rel="stylesheet" href="{{ asset('assets/css/shared/iconly.css') }}">
        {{-- CSS MANUAL --}}
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    </head>

    <body>
        <div id="loading-box">
            <div class="wrapper-loading-text">
                <div class="spinner"></div>
            </div>
        </div>

        <div id="app">

            @include('partials.sidebar')

            <div id="main" class='layout-navbar'>

                @include('partials.navbar')

                <div id="main-content">

                    @yield('container-content')

                    @include('partials.footer')
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/js/app.js') }}"></script>

        <!-- Need: Apexcharts -->
        <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>

        <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

        {{-- Data Table --}}
        <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
        <script src="{{ asset('assets/js/pages/datatables.js') }}"></script>

        {{-- Sweet Alert --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {

                $('#loading-box').fadeOut();


                // $("#btn-master-data-sync").click(function() {
                //     $(this).prop("disabled", true);
                //     $("#wrapper-btn-all").removeClass("d-flex");
                //     $("#btn-semester-data-sync").prop("disabled", true);
                //     $("#card-sync-data-master").css({
                //         "width": "100%",
                //         "transition": "opacity 1s ease-in-out"
                //     });
                //     $("#wrapper-loading-data-master").css({
                //         "height": "max-content",
                //         "transition": "opacity 1s ease-in-out"
                //     });
                //     $("#sync-data-master-loading, #progress-data-master, #wrapper-text-progress1").css({
                //         "opacity": "1",
                //         "transition": "opacity 1s ease-in-out"
                //     });
                // });

                // $("#btn-semester-data-sync").click(function() {
                //     $(this).prop("disabled", true);
                //     $("#wrapper-btn-all").removeClass("d-flex");
                //     $("#btn-master-data-sync").prop("disabled", true);
                //     $("#card-sync-data-semester").css({
                //         "width": "100%",
                //         "transition": "opacity 1s ease-in-out"
                //     });
                //     $("#wrapper-loading-data-semester").css({
                //         "height": "max-content",
                //         "transition": "opacity 1s ease-in-out"
                //     });
                //     $("#sync-data-semester-loading, #progress-data-semester, #wrapper-text-progress2").css({
                //         "opacity": "1",
                //         "transition": "opacity 1s ease-in-out"
                //     });
                // });

            });
        </script>

        @yield('script-pages')

    </body>

    </html>

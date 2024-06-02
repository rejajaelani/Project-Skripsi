<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visualisasi Data Mahasiswa INSTIKI | Login</title>

    {{-- CSS Bootstrap v5.3.2 --}}
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    {{-- CSS MANUAL --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- ICON FONT AWESOME CDN --}}
    <script src="https://kit.fontawesome.com/1320d40dae.js" crossorigin="anonymous"></script>

</head>

<body class="page-login">

    <div id="loading-box">
        <div class="wrapper-loading-text">
            <div class="spinner"></div>
        </div>
    </div>

    <div class="title-text shadow-sm">
        <a href="./" class="d-flex align-items-center justify-content-center"
            style="gap: 10px;text-decoration: none;color: #000;">
            <p class="p-0 m-0">Visualisasi Data Mahasiswa</p> <span class="badge badge-sm bg-primary">BETA</span>
        </a>
    </div>
    <div class="container d-flex align-items-center">
        <div class="row w-100">
            <div class="col d-flex justify-content-center">
                <div class="logo-instiki shadow-sm">
                    <img src="{{ asset('images/Logo-Instiki.png') }}" alt="Logo_Instiki">
                </div>
                <div class="form-login card shadow-sm border-0">
                    <div class="card-body">
                        @if ($errors->any())
                            <button type="button" id="btnMsgError" class="btn btn-primary d-none"
                                data-bs-toggle="modal" data-bs-target="#msgError">
                                Launch demo modal
                            </button>
                            <div class="modal fade" id="msgError" tabindex="-1" aria-labelledby="msgErrorLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h1 class="modal-title fs-5" id="msgErrorLabel">Login Failed</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-danger p-3 ps-5 m-0">
                                                <ul class="p-0 m-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li class="p-0 m-0">{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- @if ($errors->any())
                            <div class="msg-error">
                                <div class="alert alert-danger p-2 ps-4 m-0">
                                    <ul class="p-0 m-0">
                                        @foreach ($errors->all() as $error)
                                            <li class="p-0 m-0" style="font-size: 12px !important;">{{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif --}}
                        <div class="logo-user">
                            <i class="fa-solid fa-user-tie"></i> <strong>LOG<span class="text-danger">IN</span></strong>
                        </div>
                        <form action="{{ route('process.login') }}" method="post">
                            @csrf
                            <hr class="p-0 m-0 mb-2">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" class="btn-login btn btn-outline-primary"><i
                                    class="fa-solid fa-right-to-bracket"></i> Sign In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(window).on('load', function() {
                setTimeout(function() {
                    $('#loading-box').fadeOut();
                    $('#btnMsgError').click();
                }, 1000);
            });


        });
    </script>

</body>

</html>

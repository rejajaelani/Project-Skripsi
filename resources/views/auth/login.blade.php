<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Visualisasi Data Mahasiswa Dashboard</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/png">
    {{-- CSS MANUAL --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- ICON FONT AWESOME CDN --}}
    <script src="https://kit.fontawesome.com/1320d40dae.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="loading-box">
        <div class="wrapper-loading-text">
            <div class="spinner"></div>
        </div>
    </div>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <h1 class="auth-title"><i class="fa-solid fa-right-to-bracket"></i> Log in.</h1>
                    <p class="auth-subtitle mb-5" style="font-size: 22px;line-height: 27px;">Log in dashboard
                        visualisasi data mahasiswa with your data that has been notified by the admin.</p>
                    @if ($errors->any())
                        <div class="msg-error">
                            <div class="alert alert-danger p-2 ps-4 m-0 mb-3">
                                <ul class="p-0 m-0">
                                    @foreach ($errors->all() as $error)
                                        <li class="p-0 m-0" style="font-size: 12px !important;">{{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <form action="{{ route('process.login') }}" method="post">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" id="username" name="username" value="{{ old('username') }}"
                                class="form-control form-control-xl" placeholder="Username">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" id="password" name="password" value="{{ old('password') }}"
                                class="form-control form-control-xl" placeholder="Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Remember me
                            </label>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                        <!-- </form>
            <div class="text-center mt-5 text-lg fs-4">
                <p class="text-gray-600">Don't have an account? <a href="auth-register.html" class="font-bold">Sign
                        up</a>.</p>
                <p><a class="font-bold" href="auth-forgot-password.html">Forgot password?</a>.</p>
            </div> -->
                        <div class="mt-5 pt-5 d-flex align-items-center justify-content-start" style="gap: 5px;">
                            <a href="https://rejajaelani.my.id/" target="_blank"
                                style="font-size: 10px !important;">Develop By Abdul Reja Jaelani</a>
                            <p class="p-0 m-0">|</p>
                            <a href="https://zuramai.github.io/mazer/" target="_blank"
                                class="d-flex align-items-center justify-content-center"
                                style="gap: 5px;font-size: 10px !important;"><span>@Using</span> <img
                                    src="{{ asset('assets/images/logo/logo.svg') }}" style="height: 12px !important;"
                                    alt="Logo"> <span>Template</span></a>
                        </div>
                        <a href="https://laravel.com/" target="_blank" style="font-size: 10px !important;">Laravel
                            v10.10</a>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <img class="img-auth-right shadow" src="{{ asset('Images/Logo-Instiki.png') }}" alt="Logo Stiki">
                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            $(window).on('load', function() {
                setTimeout(function() {
                    $('#loading-box').fadeOut();
                }, 1000);
            });

            var storedUsername = localStorage.getItem('username');
            var storedPassword = localStorage.getItem('password');

            if (storedUsername && storedPassword) {
                $('#username').val(storedUsername);
                $('#password').val(storedPassword);
                $('#flexCheckDefault').prop('checked', true);
            }
            if ($(flexCheckDefault).is(':checked')) {
                $('#username').keyup(function() {
                    localStorage.setItem('username', $(this).val());
                });
                $('#password').keyup(function() {
                    localStorage.setItem('password', $(this).val());
                });
            };
            $('#flexCheckDefault').change(function() {
                if ($(this).is(':checked')) {
                    $('#username').keyup(function() {
                        localStorage.setItem('username', $(this).val());
                    });
                    $('#password').keyup(function() {
                        localStorage.setItem('password', $(this).val());
                    });
                    localStorage.setItem('username', $('#username').val());
                    localStorage.setItem('password', $('#password').val());
                } else {
                    localStorage.removeItem('username');
                    localStorage.removeItem('password');
                }
            });
        });
    </script>
</body>

</html>

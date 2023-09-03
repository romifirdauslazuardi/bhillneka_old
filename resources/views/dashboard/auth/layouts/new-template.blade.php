<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/dreampos/assets/img/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/dreampos/assets/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/dreampos/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dreampos/assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/dreampos/assets/css/style.css') }}">

</head>

<body class="account-page">

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">
                <div class="login-content">
                    <div class="login-userset">
                        <a href="{{ route('dashboard.auth.login.index') }}" class="login-logo logo-normal">
                            <img src="{{ !empty(\SettingHelper::settings('dashboard', 'logo_icon')) ? asset(\SettingHelper::settings('dashboard', 'logo_icon')) : asset('assets/dreampos/assets/img/logo.png') }}"
                                alt="img">
                        </a>
                        @yield('content')
                        <div class="form-sociallink">
                            <ul>
                                <li class="text-center" style="width: 100%">
                                    <a href="{{ route('dashboard.auth.google.index') }}">
                                        <img src="{{ asset('assets/dreampos/assets/img/icons/google.png') }}"
                                            class="me-2" alt="google">
                                        Google
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="login-img">
                    <img src="{{ asset('assets/dreampos/assets/img/login.jpg') }}" alt="img">
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets/dreampos/assets/js/jquery-3.6.0.min.js') }}"></script>

    <!-- Feather Icon JS -->
    <script src="{{ asset('assets/dreampos/assets/js/feather.min.js') }}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/dreampos/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/dreampos/assets/js/script.js') }}"></script>

</body>

</html>

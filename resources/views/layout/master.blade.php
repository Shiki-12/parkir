<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('layout/assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('layout/assets/img/favicon.png') }}">
    <title>
        SIJA Parking - @yield('title', $title ?? '')
    </title>
    <link href="{{ asset('layout/assets/css/open-sans.css') }}" rel="stylesheet" />
    <link href="{{ asset('layout/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('layout/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('layout/assets/fontawesome/css/all.min.css') }}" />
    <link id="pagestyle" href="{{ asset('layout/assets/css/soft-ui-dashboard.min.css') }}" rel="stylesheet" />
    <style>

        .fa,
        .fas,
        .far,
        .fal,
        .fab,
        i[class^="fa-"],
        i[class*=" fa-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "FontAwesome", sans-serif !important;
            font-weight: 900 !important;
            -webkit-font-smoothing: antialiased !important;
            -moz-osx-font-smoothing: grayscale !important;
            display: inline-block !important;
            text-rendering: auto !important;
            color: #2c3e50 !important;
            font-size: 0.95rem !important;
            line-height: 1 !important;
        }


        .nav-link .fa,
        .dropdown-item .fa,
        .navbar .fa {
            color: inherit !important;
        }


        .sidenav-toggler-line {
            display: block;
            width: 18px;
            height: 2px;
            background-color: rgba(0, 0, 0, 0.6) !important;
            margin: 3px 0 !important;
            border-radius: 2px;
        }
    </style>
    @yield('styles')
</head>

<body class="g-sidenav-show  bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0 d-flex align-items-center" href="{{ url('/') }}">
                <div class="icon icon-shape icon-sm">
                    <img id="logo-image" src="parkir.png" class="navbar-brand-img h-100" alt="main_logo">
                </div>
                <span class="ms-1 font-weight-bold">SIJA Parking</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">

        @yield('menu')
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        @include('layout.navbar')

        @yield('content')

    </main>
    <script src="{{ asset('layout/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('layout/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('layout/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('layout/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script src="{{ asset('layout/assets/js/soft-ui-dashboard.min.js?v=1.0.7') }}"></script>
    <script src="{{ asset('layout/assets/js/sweetalert2.all.min.js') }}"></script>
    @yield('scripts')
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/styles.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('backend/assets/img/favicon.png') }}" />
{{--    <link rel="stylesheet" href="{{ asset('backend/assets/css/simple-datatables.min.css') }}" />--}}
    <link rel="stylesheet" href="{{ asset('backend/assets/js/litepicker.js') }}" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
</head>

<body class="nav-fixed">

    @include('backend.admin.layouts.inc.admin-navbar')

    <div id="layoutSidenav">
        @include('backend.admin.layouts.inc.admin-sidebar')
        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
            @include('backend.admin.layouts.inc.admin-footer')
        </div>
    </div>

    <script src="{{ asset('backend/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/scripts.js') }}"></script>
    <script src="{{ asset('backend/assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/chart-area-demo.js') }}"></script>
    <script src="{{ asset('backend/assets/js/chart-bar-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('backend/assets/js/datatables-simple-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js" crossorigin="anonymous"></script>
    <script src="{{ asset('backend/assets/js/litepicker.js') }}"></script>
</body>
</html>

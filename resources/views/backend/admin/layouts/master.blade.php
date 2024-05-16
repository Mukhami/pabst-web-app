<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/styles.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
{{--    <link rel="stylesheet" href="{{ asset('backend/assets/css/simple-datatables.min.css') }}" />--}}
    <link rel="stylesheet" href="{{ asset('backend/assets/js/litepicker.js') }}" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
    @yield('styles')
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{ asset('backend/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('backend/assets/js/datatables-simple-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js" crossorigin="anonymous"></script>
    <script src="{{ asset('backend/assets/js/litepicker.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    @if (session('error'))
        <script>
            toastr["error"]("{{ session('error') }}", "Sorry, something went wrong", {
                closeButton: true,
                progressBar: true,
                timeOut: 10000
            });
        </script>
    @elseif(session('success'))
        <script>
            toastr["success"]("{{ session('success') }}", "Success", {
                closeButton: true,
                progressBar: true,
                timeOut: 10000
            });
        </script>
    @elseif(session('status'))
        <script>
            console.log("{{ session('status') }}");
            toastr["success"]("{{ session('status') }}", "Success", {
                closeButton: true,
                progressBar: true,
                timeOut: 10000
            });
        </script>
    @elseif(session('warning'))
        <script>
            toastr["warning"]("{{ session('warning') }}", "Warning", {
                closeButton: true,
                progressBar: true,
                timeOut: 10000
            });
        </script>
    @elseif(session('info'))
        <script>
            toastr["info"]("{{ session('info') }}", "Info", {
                closeButton: true,
                progressBar: true,
                timeOut: 10000
            });
        </script>
    @endif
    @yield('scripts')


</body>
</html>

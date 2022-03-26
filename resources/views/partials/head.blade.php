<head>
    <title>Kasir App</title>
    <meta name="description" content="Dashboard">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Your website">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('nura-admin/assets/images/favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('nura-admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Font Awesome CSS -->
    <link href="{{ asset('nura-admin/assets/font-awesome/css/all.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link href="{{ asset('nura-admin/assets/css/style.css') }}" rel="stylesheet" type="text/css" />

    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="{{ asset('nura-admin/assets/plugins/chart.js/Chart.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('nura-admin/assets/plugins/datatables/datatables.min.css') }}" />
    <!-- END CSS for this page -->

    <!-- BEGIN CSS for this page -->
    <link href="{{ asset('nura-admin/assets/plugins/datetimepicker/css/daterangepicker.css') }}" rel="stylesheet" />
    <!-- END CSS for this page -->


    <link href="{{ asset('nura-admin/assets/plugins/jquery.filer/css/jquery.filer.css') }}" rel="stylesheet" />
    <link href="{{ asset('nura-admin/assets/plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/select2-bootstrap4.css') }}" rel="stylesheet"/>

    <script src="{{ asset('js/jquery.min.js') }}" ></script>
    <script src="{{ asset('js/select2.min.js') }}" ></script>

    @yield('custom_css')
</head>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>| Admin Board of StepViet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <!-- Plugin CSS -->
    <link href="{{ asset('libs/jquery-vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css">

    <!-- App CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css">
</head>

<body class="authentication-bg authentication-bg-pattern">

<div class="container">
    @yield('content')
</div>


<script src="{{ asset('js/vendor.min.js') }}"></script>

<!-- App js -->

<script src="{{ asset('js/app.min.js') }}"></script>


</body>

</html>
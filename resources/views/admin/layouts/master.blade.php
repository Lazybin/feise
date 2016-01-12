<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>App Name - @yield('title')</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ url('../resources/assets/vendor/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{ url('../resources/assets/vendor/metisMenu/dist/metisMenu.min.css') }}" rel="stylesheet">

    @yield("customercss")

    <!-- Custom CSS -->
    <link href="{{ url('../resources/assets/vendor/startbootstrap-sb-admin-2/dist/css/sb-admin-2.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ url('../resources/assets/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>
<body>
    <div id="wrapper">
        @yield("navbar")

        @yield("content")
    </div>

    <!-- jQuery -->
    <script src="{{ url('../resources/assets/vendor/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ url('../resources/assets/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ url('../resources/assets/vendor/metisMenu/dist/metisMenu.min.js') }}"></script>

    @yield("customerjs")



    <!-- Custom Theme JavaScript -->
    <script src="{{ url('../resources/assets/vendor/startbootstrap-sb-admin-2/dist/js/sb-admin-2.js') }}"></script>
</body>
</html>
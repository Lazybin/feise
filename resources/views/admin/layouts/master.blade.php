<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>App Name - @yield('title')</title>
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="{{ url('../resources/assets/vendor/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- MetisMenu CSS -->
    <link href="{{ url('../resources/assets/vendor/metisMenu/dist/metisMenu.min.css') }}" rel="stylesheet">

    <!-- Timeline CSS -->
    <link rel="stylesheet" href="{{ url('../resources/assets/vendor/startbootstrap-sb-admin-2/dist/css/timeline.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ url('../resources/assets/vendor/startbootstrap-sb-admin-2/dist/css/sb-admin-2.css') }}">

    <!-- Morris Charts CSS -->
    <link href="{{ url('../resources/assets/vendor/morrisjs/morris.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link rel="stylesheet" type="text/css" href="{{ url('../resources/assets/vendor/font-awesome/css/font-awesome.css') }}">

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
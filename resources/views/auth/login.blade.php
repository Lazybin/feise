<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>App Name - 登录</title>
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
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">登录</h3>
                </div>
                <div class="panel-body">
                    <form role="form" method="POST" action="{{ url('/admin/login') }}">
                        <fieldset>
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <input type="email" autofocus="" name="email" placeholder="E-mail" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="password" value="" name="password" placeholder="Password" class="form-control">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="Remember Me" name="remember">记住我
                                </label>
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <button class="btn btn-lg btn-success btn-block" type="submit">登录</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>





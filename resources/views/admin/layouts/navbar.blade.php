@section('navbar')
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html">SB Admin v2.0</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">

            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="{{url('admin/logout')}}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="{{url('/home/')}}/"><i class="fa fa-dashboard fa-fw"></i> 控制台</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa  fa-gift fa-fw"></i> 商品管理<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{{url('/home_manage/')}}">首页展示管理</a>
                            </li>
                            <li>
                                <a href="{{url('/goods/')}}">商品管理</a>
                            </li>
                            <li>
                                <a href="{{url('/subjects/')}}">专题管理</a>
                            </li>
                            <li>
                                <a href="{{url('/themes/')}}">主题管理</a>
                            </li>
                            <li>
                                <a href="{{url('/category/')}}">分类管理</a>
                            </li>
                            <li>
                                <a href="{{url('/activity_goods/')}}">活动商品管理</a>
                            </li>
                            <li>
                                <a href="{{url('/activity_classification/')}}">活动分类管理</a>
                            </li>
                            <li>
                                <a href="{{url('/free_post/')}}">包邮分类管理</a>
                            </li>
                            <li>
                                <a href="{{url('/conversion_goods/')}}">兑换商品管理</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-key fa-fw"></i> 权限管理<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{{url('/permission/')}}">管理员管理</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fa  fa-gear fa-fw"></i> 系统配置<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{{url('/boot_page/')}}">启动页图片配置</a>
                            </li>
                            <li>
                                <a href="{{url('/gift_token_setting/')}}">礼券设置</a>
                            </li>
                            <li>
                                <a href="{{url('/banner/')}}">banner设置</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>
@endsection
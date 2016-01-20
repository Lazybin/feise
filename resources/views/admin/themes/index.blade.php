@extends('admin.layouts.master')
@section('title', ' 控制台')

@include('admin.layouts.navbar')
@section('customercss')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- DataTables CSS -->
    <link href="{{ url('../resources/assets/vendor/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ url('../resources/assets/vendor/datatables-responsive/css/dataTables.responsive.css') }}" rel="stylesheet">
    <link href="{{ url('../resources/assets/vendor/bootstrap-fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
    @endsection

    @section('customerjs')
            <!-- DataTables JavaScript -->
    <script src="{{ url('../resources/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('../resources/assets/vendor/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ url('/js/bootbox.min.js') }}"></script>
    <script src="{{ url('/js/ueditor.config.js') }}"></script>
    <script src="{{ url('/js/ueditor.all.min.js') }}"></script>
    <script src="{{ url('../resources/assets/vendor/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script>
        var baseUrl="{{url('/')}}";
        var arrChooseGoods=[];
        var ue = UE.getEditor('container',{
            toolbars: [
                ['source', 'undo', 'redo','bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc','simpleupload','insertimage']
            ],
            initialFrameHeight:320  //初始化编辑器高度,默认320
        });

        var $cover = $("#coverImage");
        $cover.fileinput({
            rowseClass: "btn btn-primary",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            overwriteInitial: true
        });

        var $head = $("#headImage");
        $head.fileinput({
            rowseClass: "btn btn-primary",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            overwriteInitial: true
        });

        var tableChoose=$('#dataTables-choose').DataTable({
            responsive: true,
            "dom": 'rtip',
            "processing": true,
            "iDisplayLength": 10,
            "autoWidth": false,
            "searching": false,
            "ordering": false,
            "language": {
                "lengthMenu": "每页显示 _MENU_ 条",
                "zeroRecords": "暂无记录",
                "info": "正在显示第_PAGE_页，总共_PAGES_页",
                "infoEmpty": "",
                "loadingRecords": "加载中...",
                "processing":     "处理中...",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "paginate": {
                    "next":       "下一页",
                    "previous":   "上一页"
                }
            },
            "columnDefs": [
                { //给每个单独的列设置不同的填充，或者使用aoColumns也行           {
                    "targets": -1,
                    "mData": null,
                    "searchable": false,
                    "orderable": false,
                    'sClass':'align-center',
                    "mRender": function (data, type, full)
                    {
                        return '<button type="button"  class="btn btn-primary btn-xs">删除</button>';
                    }
                },
                {
                    "targets":0,
                    "mData": 'id'
                },
                {
                    "targets": 1,
                    'sClass':'align-center',
                    "mData": 'name'
                }

            ]

        });

        var tableGoods=$('#dataTables-goods').DataTable({
            responsive: true,
            "dom": 'rtip',
            "processing": true,
            "iDisplayLength": 10,
            "autoWidth": false,
            "searching": false,
            "ordering": false,
            "language": {
                "lengthMenu": "每页显示 _MENU_ 条",
                "zeroRecords": "暂无记录",
                "info": "正在显示第_PAGE_页，总共_PAGES_页",
                "infoEmpty": "",
                "loadingRecords": "加载中...",
                "processing":     "处理中...",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "paginate": {
                    "next":       "下一页",
                    "previous":   "上一页"
                }
            },
            "columnDefs": [
                { //给每个单独的列设置不同的填充，或者使用aoColumns也行           {
                    "targets": -1,
                    "mData": null,
                    "searchable": false,
                    "orderable": false,
                    'sClass':'align-center',
                    "mRender": function (data, type, full)
                    {
                        return '<button type="button" onclick="onChooseClick(\''+full.id+'\',\''+full.name+'\')" class="btn btn-primary btn-xs">选择</button>';
                    }
                },
                {
                    "targets":0,
                    "mData": 'id'
                },
                {
                    "targets": 1,
                    'sClass':'align-center',
                    "mData": 'name'
                }

            ]

        });
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true,
                "language": {
                    "lengthMenu": "每页显示 _MENU_ 条",
                    "zeroRecords": "没找到匹配的值",
                    "info": "正在显示第_PAGE_页，总共_PAGES_页",
                    "infoEmpty": "暂无记录",
                    "loadingRecords": "加载中...",
                    "processing":     "处理中...",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "paginate": {
                        "next":       "下一页",
                        "previous":   "上一页"
                    }
                },
                "iDisplayLength": 5,
                "lengthMenu" : [[5, 10, 20, 50, -1], [5, 10, 20, 50, "全部"]],
                "processing": true,
                "autoWidth": false,
                "serverSide": true,
                "searching": false,
                "ordering": false,
                "ajax": "{{url('/')}}/themes/index",
                "columnDefs": [
                    { //给每个单独的列设置不同的填充，或者使用aoColumns也行           {
                        "targets": -1,
                        "mData": null,
                        "searchable": false,
                        "orderable": false,
                        'sClass':'align-center',
                        "mRender": function (data, type, full)
                        {
                            var id = full.id;
                            return '<button type="button" onclick="onEditClick(\''+id+'\')" class="btn btn-primary btn-xs">编辑</button>'+
                                    '<button type="button" onclick="onDelete(\''+id+'\')" class="btn btn-danger btn-xs">删除</button>';
                        }
                    },
                    {
                        "targets": 0,
                        'sClass':'align-center',
                        "mData": 'id'
                    },
                    {
                        "targets":1,
                        "mData": 'title'
                    },
                    {
                        "targets": 2,
                        'sClass':'align-center',
                        "mData": 'category.name'
                    },
                    {
                        "targets": 3,
                        'sClass':'align-center',
                        "mData": 'type'
                    },
                    {
                        "targets": 4,
                        'sClass':'align-center',
                        "mData": 'created_at'
                    }

                ]
            });
            onTypeChange();

        });
        function onAddClick(){
            $("#titleModel").html('添加主题');
            $("#parentCategory").val(-1);

            $("#type").val(0);
            $("#title").val('');
            $("#intro").val('');
            onTypeChange();

            $('#chooseGoods').html('');
            $("#id").val(-1);
            initCategory(0,-1);
            ue.setContent('');

            $("#subhead").val('');

            $cover.fileinput("refresh", {
                initialPreview:[]
            });

            $head.fileinput("refresh", {
                initialPreview:[]
            });

            tableGoods.clear().draw();
            tableChoose.clear().draw();

            $('#themesForm').attr('action',baseUrl+'/themes/store');
            $('#adminModel').modal('show');
        }

        function onEditClick(id){
            $.ajax({
                url: "{{url('/')}}/themes/detail/"+id,
                async: true,
                type: "GET",
                dataType:'json',
                success: function(recv){
                    if(recv.meta.code=='0'){
                        var val=recv.meta.error;
                        bootbox.alert(val, function(){
                        });
                    }else if(recv.meta.code=='1'){
                        $("#title").val(recv.meta.data.title);
                        $("#subhead").val(recv.meta.data.subhead);

                        $("#type").val(recv.meta.data.type);
                        onTypeChange();
                        $("#parentCategory").val(recv.meta.data.category.pid);
                        initCategory(recv.meta.data.category.pid,recv.meta.data.category.id);

                        tableGoods.ajax.url("{{url('/')}}/goods/index?category_id="+recv.meta.data.category.id).load();
                        tableChoose.clear().draw();
                        $.each(recv.meta.data.goods, function (key, item) {
                            onChooseClick(item.id,item.name);
                        });
                        $("#intro").val('');
                        if(recv.meta.data.type!=0){
                            ue.setContent(recv.meta.data.themes_description);
                        }

                        $cover.fileinput("refresh", {
                            initialPreview:['<img src="{{url('/')}}'+recv.meta.data.cover+'" class="file-preview-image" >']
                        });

                        $head.fileinput("refresh", {
                            initialPreview:['<img src="{{url('/')}}'+recv.meta.data.head_image+'" class="file-preview-image" >']
                        });
                        $('#themesForm').attr('action',baseUrl+'/themes/update/'+id);
                        $("#titleModel").html('修改主题');
                        $('#adminModel').modal('show');
                    }
                    return true;
                }
            });
        }

        function reload(){
            window.location.reload();
        }

        function onDelete(id){
            $.ajax({
                url: "{{url('/')}}/themes/delete/"+id,
                async: true,
                type: "DELETE",
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(recv){
                    if(recv.meta.code=='0'){
                        var val=recv.meta.error;
                        bootbox.alert(val, function(){
                        });
                    }else if(recv.meta.code=='1'){
                        window.location.reload();
                    }
                    return true;
                }
            });
        }
//        function onChooseDeleteClick(){
//            console.log($(this).closest("tr"));
//            tableChoose.row( $(this).parents('tr') )
//                    .remove()
//                    .draw();
//        }

        $('#dataTables-choose tbody').on( 'click', 'button', function () {
            tableChoose.row( $(this).parents('tr') ).remove().draw();
            deal_choose_data();
        } );

        function deal_choose_data(){
            var str=''
            tableChoose.data().each( function (d) {
                str+= d.id+',';
            } );
            $("#chooseGoods").val(str);
        }



        function onChooseClick(id,name){
            var t ={};
            t.id=id;
            t.name=name;
            tableChoose.row.add( t ).draw();
            deal_choose_data();
        }
        function onTypeChange(){
            var type=$("#type").val();
            if(type==0){
                $("#div1").css('display','none');
            }else{
                $("#div1").css('display','inline');
            }
        }

        function initCategory(pid,id){
            var category = $("#category");
            var str_html='';
            if(id==-1){
                str_html='<option value="-1" selected>请选择分类</option>';
            }else{
                str_html='<option value="-1">请选择分类</option>';
            }
            if(pid!=0&&pid!=-1) {
                $.ajax({
                    url: "{{url('/')}}/category/index?length=100&pid=" + pid,
                    async: true,
                    type: "get",
                    dataType: 'json',
                    success: function (data) {
                        var rows=data.data;
                        $.each(rows, function (key, item) {
                            if(item['id']==id){
                                str_html += '<option value="' + item['id'] + '" selected>' + item['name'] + '</option>';
                            }else{
                                str_html += '<option value="' + item['id'] + '">' + item['name'] + '</option>';
                            }

                        });
                        category.html(str_html);
                    }
                });
            }else{
                category.html(str_html);
            }
        }
        function onParentCategoryChange(){
            var pid=$("#parentCategory").val();
            initCategory(pid,-1);
        }
        function onCategoryChange(){
            var category_id=$("#category").val();
            tableGoods.ajax.url("{{url('/')}}/goods/index?category_id="+category_id).load();

        }
    </script>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">主题管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        主题列表
                        <div style="margin-top: -5px;" class="btn-group pull-right">
                            <button onclick="onAddClick();"  class="btn btn-primary btn-circle">
                                <i class="glyphicon glyphicon-plus"></i>
                            </button>
                            <button onclick="reload()" class="btn btn-default btn-circle">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>名称</th>
                                    <th>类别</th>
                                    <th>类型</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->

                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
    </div>

    <!-- Modal dialog -->
    <div class="modal fade" id="adminModel">
        <form id="themesForm" enctype="multipart/form-data" class="row-border form-horizontal" method="post"  action="">
            {!! csrf_field() !!}
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="titleModel"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" name="title" placeholder="请输入标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">副标题</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="subhead" name="subhead" placeholder="请输入副标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">类别</label>
                            <div class="col-sm-4">
                                <select id="parentCategory" class="form-control" onchange="onParentCategoryChange()">
                                    <option value="-1" selected>请选择类别</option>
                                    @foreach($categories as $c)
                                        <option value="{{$c['id']}}">{{$c['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="category" id="category" class="form-control" onchange="onCategoryChange()">
                                    <option value="-1" selected>请选择类别</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">模式</label>
                            <div class="col-sm-4">
                                <select name="type" id="type" onchange="onTypeChange()" class="form-control">
                                    <option value="0">普通模式</option>
                                    <option value="1">图文模式</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">简介</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="intro" name="intro" rows="2"></textarea>
                            </div>
                        </div>
                        <div id="div1" class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">主题描述</label>
                            <div class="col-sm-10">
                                <script id="container" name="description" type="text/plain"></script>
                            </div>>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">封面图片</label>
                            <div class="col-sm-6">
                                <input id="coverImage" name="coverImage" type="file" class="file" data-preview-file-type="text" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">头部图片</label>
                            <div class="col-sm-6">
                                <input id="headImage" name="headImage" type="file" class="file" data-preview-file-type="text" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">已选择商品</label>
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control" name="chooseGoods" id="chooseGoods">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-choose">
                                    <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>名称</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">商品列表</label>
                            <div class="col-sm-8">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-goods">
                                    <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>名称</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn_first" >提交</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </form>
    </div><!-- /.modal -->
@endsection
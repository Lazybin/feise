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
    <script src="{{ url('../resources/assets/vendor/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script>
        var baseUrl="{{url('/')}}";

        var $cover = $("#coverImage");
        $cover.fileinput({
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
                    "mData": 'title'
                }

            ]

        });

        var tableThemes=$('#dataTables-themes').DataTable({
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
                        return '<button type="button" onclick="onChooseClick(\''+full.id+'\',\''+full.title+'\')" class="btn btn-primary btn-xs">选择</button>';
                    }
                },
                {
                    "targets":0,
                    "mData": 'id'
                },
                {
                    "targets": 1,
                    'sClass':'align-center',
                    "mData": 'title'
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
                "ajax": "{{url('/')}}/subjects/index",
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
                        "mData": 'created_at'
                    }

                ]
            });

        });
        function onAddClick(){
            $("#titleModel").html('添加专题');

            $("#title").val('');

            $('#chooseThemes').html('');
            $("#id").val(-1);


            $("#subhead").val('');

            $cover.fileinput("refresh", {
                initialPreview:[]
            });


            tableThemes.ajax.url("{{url('/')}}/themes/index").load();
            tableChoose.clear().draw();

            $('#subjectsForm').attr('action',baseUrl+'/subjects/store');
            $('#adminModel').modal('show');
        }

        function onEditClick(id){
            $.ajax({
                url: "{{url('/')}}/subjects/detail/"+id,
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


                        tableThemes.ajax.url("{{url('/')}}/themes/index").load();
                        tableChoose.clear().draw();
                        $.each(recv.meta.data.themes, function (key, item) {
                            onChooseClick(item.id,item.title);
                        });


                        $cover.fileinput("refresh", {
                            initialPreview:['<img src="{{url('/')}}'+recv.meta.data.cover+'" class="file-preview-image" >']
                        });


                        $('#subjectsForm').attr('action',baseUrl+'/subjects/update/'+id);
                        $("#titleModel").html('修改专题');
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
                url: "{{url('/')}}/subjects/delete/"+id,
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


        $('#dataTables-choose tbody').on( 'click', 'button', function () {
            tableChoose.row( $(this).parents('tr') ).remove().draw();
            deal_choose_data();
        } );

        function deal_choose_data(){
            var str=''
            tableChoose.data().each( function (d) {
                str+= d.id+',';
            } );
            $("#chooseThemes").val(str);
            console.log(str);
        }



        function onChooseClick(id,title){
            var t ={};
            t.id=id;
            t.title=title;
            tableChoose.row.add( t ).draw();
            deal_choose_data();
        }
    </script>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">专题管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        专题列表
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
        <form id="subjectsForm" enctype="multipart/form-data" class="row-border form-horizontal" method="post"  action="">
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
                            <label for="inputGoodsName" class="col-sm-2 control-label">封面图片</label>
                            <div class="col-sm-6">
                                <input id="coverImage" name="coverImage" type="file" class="file" data-preview-file-type="text" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">已选择主题</label>
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control" name="chooseThemes" id="chooseThemes">
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
                            <label for="inputGoodsName" class="col-sm-2 control-label">主题列表</label>
                            <div class="col-sm-8">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-themes">
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
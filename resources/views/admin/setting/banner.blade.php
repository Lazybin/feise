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
        var tableChoose=$('#dataTables-themes').DataTable({
            responsive: true,
            "dom": 'rtip',
            "processing": true,
            "iDisplayLength": 5,
            "autoWidth": false,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "ajax": "{{url('/')}}/themes/index",
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
                "iDisplayLength": 20,
                "lengthMenu" : [[5, 10, 20, 50, -1], [5, 10, 20, 50, "全部"]],
                "processing": true,
                "autoWidth": false,
                "serverSide": true,
                "searching": false,
                "ordering": false,
                "ajax": "{{url('/')}}/banner/index",
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
                        "targets":2,
                        "mData": 'banner_position',
                        "mRender": function (data, type, full)
                        {
                            if(data==0)
                                return '主页';
                            else
                                return '约惠';
                        }
                    },
                    {
                        "targets": 3,
                        'sClass':'align-center',
                        "mData": 'order'
                    },
                    {
                        "targets": 4,
                        'sClass':'align-center',
                        "mData": 'created_at'
                    }

                ]
            });

        });
        var $cover = $("#coverImage");
        $cover.fileinput({
            rowseClass: "btn btn-primary",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            overwriteInitial: true
        });
        var $detailImage = $("#detailImage");
        $detailImage.fileinput({
            rowseClass: "btn btn-primary",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            overwriteInitial: true
        });
        $("#input-id").fileinput({
            rowseClass: "btn btn-primary btn-block",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            overwriteInitial: true
        });
        $("#input-id2").fileinput({
            rowseClass: "btn btn-primary btn-block",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            overwriteInitial: true
        });
        function onAddClick(){
            $("#newTitle").val('');
            $("#newAction").val('');
            $("#banner_position").val(0);
            $("#newOrder").val('');
            $("#modelTitle").html('添加banner');
            $cover.fileinput("refresh", {
                initialPreview:[]
            });
            $detailImage.fileinput("refresh", {
                initialPreview:[]
            });

            $("#chooseShow").val('');
            $("#chooseId").val('');

            $("#type").val(0);
            onTypeChange();


            $('#bannerForm').attr('action',"{{url('/')}}/banner/store");
            $('#newBannerModel').modal('show');
        }

        function onEditClick(id){
            $.ajax({
                url: "{{url('/')}}/banner/detail/"+id,
                async: true,
                type: "GET",
                dataType:'json',
                success: function(recv){
                    if(recv.meta.code=='0'){
                        var val=recv.meta.error;
                        bootbox.alert(val, function(){
                        });
                    }else if(recv.meta.code=='1'){
                        $("#id").val(recv.meta.data.id);
                        $("#newTitle").val(recv.meta.data.title);
                        $("#banner_position").val(recv.meta.data.banner_position);
                        $("#newOrder").val(recv.meta.data.order);
                        $("#newAction").val(recv.meta.data.action);
                        $("#modelTitle").html('修改banner');
                        $("#type").val(recv.meta.data.type);
                        if(recv.meta.data.type==0){
                            $("#tableName").html('主题列表');
                            tableChoose.ajax.url("{{url('/')}}/themes/index").load();
                            onChooseClick(recv.meta.data.theme_item.id,recv.meta.data.theme_item.title);
                            $("#chooseDiv").css('display','inline');
                            $("#tableDiv").css('display','inline');
                            $("#divActive").css('display','none');
                        }else if(recv.meta.data.type==1){
                            $("#tableName").html('专题列表');
                            tableChoose.ajax.url("{{url('/')}}/subjects/index").load();
                            onChooseClick(recv.meta.data.subject_item.id,recv.meta.data.subject_item.title);
                            $("#chooseDiv").css('display','inline');
                            $("#tableDiv").css('display','inline');
                            $("#divActive").css('display','none');
                        }else{
                            $("#chooseDiv").css('display','none');
                            $("#tableDiv").css('display','none');
                            $("#divActive").css('display','inline');
                        }


                        $cover.fileinput("refresh", {
                            rowseClass: "btn btn-primary btn-block",
                            showCaption: false,
                            showRemove: false,
                            showUpload: false,
                            overwriteInitial: true,
                            initialPreview: [
                                '<img src="{{url('/')}}'+recv.meta.data.path+'" class="file-preview-image">'
                            ]
                        });

                        $detailImage.fileinput("refresh", {
                            rowseClass: "btn btn-primary btn-block",
                            showCaption: false,
                            showRemove: false,
                            showUpload: false,
                            overwriteInitial: true,
                            initialPreview: [
                                '<img src="{{url('/')}}'+recv.meta.data.detail_image+'" class="file-preview-image">'
                            ]
                        });
                        $('#bannerForm').attr('action','{{url("/")}}/banner/update/'+id);
                        $('#newBannerModel').modal('show');
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
                url: "{{url('/')}}/banner/delete/"+id,
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
        function onChooseClick(id,title){
            $("#chooseShow").val('当前选择，id:'+id+' '+title);
            $("#chooseId").val(id);
        }

        function onTypeChange(){
            var type=$("#type").val();
            $("#chooseShow").val('');
            $("#chooseId").val('');
            if(type==0){
                $("#tableName").html('主题列表');
                tableChoose.ajax.url("{{url('/')}}/themes/index").load();
                $("#chooseDiv").css('display','inline');
                $("#tableDiv").css('display','inline');
                $("#divActive").css('display','none');
            }else if(type==1){
                $("#tableName").html('专题列表');
                tableChoose.ajax.url("{{url('/')}}/subjects/index").load();
                $("#chooseDiv").css('display','inline');
                $("#tableDiv").css('display','inline');
                $("#divActive").css('display','none');
            }else{
                $("#chooseDiv").css('display','none');
                $("#tableDiv").css('display','none');
                $("#divActive").css('display','inline');
            }
        }


    </script>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">banner管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        banner列表
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
                                    <th>标题</th>
                                    <th>位置</th>
                                    <th>排序</th>
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
    <div class="modal fade" id="newBannerModel">
        <form enctype="multipart/form-data" id="bannerForm" class="row-border form-horizontal" method="post"  action="">
            {!! csrf_field() !!}
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modelTitle"></h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="newTitle" name="title" placeholder="请输入标题">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">位置</label>
                            <div class="col-sm-5">
                                <select name="banner_position" id="banner_position" class="form-control">
                                    <option value="0">首页</option>
                                    <option value="1">约惠</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName"  class="col-sm-2 control-label">类型</label>
                            <div class="col-sm-5">
                                <select name="type" id="type" onchange="onTypeChange()" class="form-control">
                                    <option value="0">主题</option>
                                    <option value="1">专题</option>
                                    <option value="2">活动</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">封面图片</label>
                            <div class="col-sm-10">
                                <input id="coverImage" name="coverImage" type="file" class="file" data-preview-file-type="text" >
                            </div>
                        </div>
                        <div id="chooseDiv" class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">当前选择</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="chooseShow"  disabled>
                                <input type="hidden" class="form-control" id="chooseId" name="item_id" >
                            </div>
                        </div>
                        <div id="tableDiv" class="form-group">
                            <label for="inputGoodsName" id="tableName" class="col-sm-2 control-label">主题列表</label>
                            <div class="col-sm-9">
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
                        <div id="divActive" class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">内容图片</label>
                            <div class="col-sm-10">
                                <input id="detailImage" name="detailImage" type="file" class="file" data-preview-file-type="text" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="newOrder" name="order" value="1" placeholder="请输入排序">
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

    <!-- Modal dialog -->
    <div class="modal fade" id="editBannerModel">
        <form enctype="multipart/form-data" class="row-border" method="post"  action="{{url('/')}}/banner/update">
            {!! csrf_field() !!}
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">添加banner</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>标题</label>
                            <input type="text" class="form-control" id="editTitle" name="title" placeholder="请输入标题">
                            <input type="hidden" class="form-control" id="id" name="id">
                        </div>
                        <div class="form-group">
                            <label>图片</label>
                            <input id="input-id2" name="input-id2" type="file" class="file" data-preview-file-type="text" >
                        </div>
                        <div class="form-group">
                            <label>排序</label>
                            <input type="text" class="form-control" id="editOrder" name="order" value="1" placeholder="请输入排序">
                        </div>
                        <div class="form-group">
                            <label>动作</label>
                            <input type="text" class="form-control" id="editAction" name="action"  placeholder="请输入动作">
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
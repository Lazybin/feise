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
                        "targets": 2,
                        'sClass':'align-center',
                        "mData": 'order'
                    },
                    {
                        "targets": 3,
                        'sClass':'align-center',
                        "mData": 'created_at'
                    }

                ]
            });

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
                        $("#editTitle").val(recv.meta.data.title);
                        $("#editOrder").val(recv.meta.data.order);
                        $("#editAction").val(recv.meta.data.action);

                        $("#input-id2").fileinput("refresh", {
                            rowseClass: "btn btn-primary btn-block",
                            showCaption: false,
                            showRemove: false,
                            showUpload: false,
                            overwriteInitial: true,
                            initialPreview: [
                                '<img src="{{url('/')}}'+recv.meta.data.path+'" class="file-preview-image">'
                            ]
                        });
                        $('#editBannerModel').modal('show');
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
            <div class="col-lg-5">
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
        <form enctype="multipart/form-data" class="row-border" method="post"  action="{{url('/')}}/banner/store">
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
                            <input type="text" class="form-control" id="newTitle" name="title" placeholder="请输入标题">
                        </div>
                        <div class="form-group">
                            <label>图片</label>
                            <input id="input-id" name="input-id" type="file" class="file" data-preview-file-type="text" >
                        </div>
                        <div class="form-group">
                            <label>排序</label>
                            <input type="text" class="form-control" id="newOrder" name="order" value="1" placeholder="请输入排序">
                        </div>
                        <div class="form-group">
                            <label>动作</label>
                            <input type="text" class="form-control" id="newAction" name="action"  placeholder="请输入动作">
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
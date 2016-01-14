@extends('admin.layouts.master')
@section('title', ' 控制台')

@include('admin.layouts.navbar')
@section('customercss')
<meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- DataTables CSS -->
<link href="{{ url('../resources/assets/vendor/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}" rel="stylesheet">

<!-- DataTables Responsive CSS -->
<link href="{{ url('../resources/assets/vendor/datatables-responsive/css/dataTables.responsive.css') }}" rel="stylesheet">
@endsection

@section('customerjs')
        <!-- DataTables JavaScript -->
<script src="{{ url('../resources/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('../resources/assets/vendor/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ url('/js/bootbox.min.js') }}"></script>
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
            "ajax": "{{url('/')}}/permission/index",
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
                    "mData": 'name'
                },
                {
                    "targets": 2,
                    'sClass':'align-center',
                    "mData": 'email'
                },
                {
                    "targets": 3,
                    'sClass':'align-center',
                    "mData": 'created_at'
                }

            ]
        });

    });
    function onAddClick(){
        $("#name").val('');
        $("#email").val('');
        $("#password").val('');
        $("#title").html('添加管理员');
        $("#id").val(-1);

        $('#adminModel').modal('show');
    }

    function onEditClick(id){
        $.ajax({
            url: "{{url('/')}}/permission/detail/"+id,
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
                    $("#name").val(recv.meta.data.name);
                    $("#email").val(recv.meta.data.email);
                    $("#password").val('');
                    $("#title").html('修改管理员');
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
            url: "{{url('/')}}/permission/delete/"+id,
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

    function onSubmitAdmin(){

        var name=$("#name").val();
        var email=$("#email").val();
        var password=$("#password").val();
        var id=$("#id").val();
        var subUrl='';
        if(id!=-1){
            subUrl= "{{url('/')}}/permission/update/"+id;
        }else{
            subUrl= "{{url('/')}}/permission/store";
        }
        $.ajax({
            url: subUrl,
            async: true,
            type: "POST",
            dataType:'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {name:name, email:email,password:password},
            success: function(recv){
                if(recv.meta.code=='0')
                {
                    var val=recv.meta.error;
                    bootbox.alert(val, function() {
                    });
                }
                else if(recv.meta.code=='1')
                {
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
                <h1 class="page-header">管理员管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        管理员列表
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
                                    <th>用户名</th>
                                    <th>账号</th>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="title"></h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">昵称</label>
                            <input type="text" class="form-control" id="name" placeholder="请输入昵称">

                            <input type="hidden" class="form-control" id="id">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">电子邮箱</label>
                            <input type="email" class="form-control" id="email" placeholder="请输入电子邮箱">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">密码</label>
                            <input type="password" class="form-control" id="password" placeholder="请输入密码">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn_first" data-dismiss="modal" onclick="onSubmitAdmin()">提交</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
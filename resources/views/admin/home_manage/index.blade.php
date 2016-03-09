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
    <script src="{{ url('/js/jquery.validate.min.js') }}"></script>
    <script>
        var baseUrl="{{url('/')}}";


        var tableChoose=$('#dataTables-themes').DataTable({
            responsive: true,
            "dom": 'rtip',
            "processing": true,
            "iDisplayLength": 10,
            "autoWidth": false,
            "searching": false,
            "ordering": false,
            "serverSide": true,
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

        var validator;
        $(document).ready(function() {

            $.validator.addMethod("valueNotEquals", function(value, element, arg){
                return arg != value;
            }, "Value must not equal arg.");

            validator = $( "#subjectsForm" ).validate({
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                },
                errorClass: 'help-block',
                ignore: ['item_id'],
                errorPlacement: function(error, element) {
                    if (element[0].type === "radio") {
                        error.appendTo(element.parent().parent());
                    }
                    else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    sort: "required",
                    type: {valueNotEquals: "-1"},
                    item_id:"required"

                },
                messages: {
                    sort: "请输入排序",
                    type: { valueNotEquals: "请选择类型" },
                    item_id:"请选择主题或者专题"
                }
            });

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
                "ajax": "{{url('/')}}/home_manage/index",
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
                        "mData": 'item.title'
                    },
                    {
                        "targets":2,
                        "mData": 'type'
                    },
                    {
                        "targets":3,
                        "mData": 'sort'
                    },
                    {
                        "targets": 4,
                        'sClass':'align-center',
                        "mData": 'created_at'
                    }

                ]
            });

        });
        function onAddClick(){
            $("#titleModel").html('添加首页项');

            $("#type").val(-1);
            $("#sort").val('');

            $('#chooseThemes').html('');
            $("#id").val(-1);

            $("#chooseShow").val('');
            $("#chooseId").val('');

            tableChoose.clear().draw();

            $('#subjectsForm').attr('action',baseUrl+'/home_manage/store');
            $('#adminModel').modal('show');
        }

        function onEditClick(id){
            $.ajax({
                url: "{{url('/')}}/home_manage/detail/"+id,
                async: true,
                type: "GET",
                dataType:'json',
                success: function(recv){
                    if(recv.meta.code=='0'){
                        var val=recv.meta.error;
                        bootbox.alert(val, function(){
                        });
                    }else if(recv.meta.code=='1'){
                        $("#type").val(recv.meta.data.type);

                        if(recv.meta.data.type==0){
                            $("#tableName").html('专题列表');
                            tableChoose.ajax.url("{{url('/')}}/subjects/index").load();
                        }else{
                            $("#tableName").html('主题列表');
                            tableChoose.ajax.url("{{url('/')}}/themes/index").load();
                        }

                        onChooseClick(recv.meta.data.item.id,recv.meta.data.item.title);

                        $("#sort").val(recv.meta.data.sort);
                        $('#subjectsForm').attr('action',baseUrl+'/home_manage/update/'+id);
                        $("#titleModel").html('修改首页项');
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
                url: "{{url('/')}}/home_manage/delete/"+id,
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
                $("#tableName").html('专题列表');
                tableChoose.ajax.url("{{url('/')}}/subjects/index").load();
            }else{
                $("#tableName").html('主题列表');
                tableChoose.ajax.url("{{url('/')}}/themes/index").load();
            }
        }
    </script>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">首页管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        显示列表
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
                                    <th>类型</th>
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
                            <label for="inputGoodsName" class="col-sm-2 control-label">类型</label>
                            <div class="col-sm-6">
                                <select name="type" id="type" onchange="onTypeChange()" class="form-control">
                                    <option value="-1">请选择类型</option>
                                    <option value="0">专题</option>
                                    <option value="1">主题</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sort" name="sort" value="1" placeholder="请输入排序">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">当前选择</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="chooseShow"  disabled>
                                <input type="hidden" class="form-control" id="chooseId" name="item_id" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" id="tableName" class="col-sm-2 control-label">主题列表</label>
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
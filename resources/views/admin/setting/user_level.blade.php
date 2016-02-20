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
                "iDisplayLength": 20,
                "lengthMenu" : [[5, 10, 20, 50, -1], [5, 10, 20, 50, "全部"]],
                "processing": true,
                "autoWidth": false,
                "serverSide": true,
                "searching": false,
                "ordering": false,
                "ajax": "{{url('/')}}/user_level/index",
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
                            return '<button type="button" onclick="onEditClick(\''+id+'\')" class="btn btn-primary btn-xs">编辑</button>';
//                                    '<button type="button" onclick="onDelete(\''+id+'\')" class="btn btn-danger btn-xs">删除</button>';
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
                    },
                    {
                        "targets": 2,
                        'sClass':'align-center',
                        "mRender": function (data, type, full)
                        {
                            return full['sum_lowest']+'--'+full['sum_highest'];
                        }
                    }


                ]
            });

        });

        function onAddClick(){
            $("#name").val('');
            $("#sum_lowest").val('');
            $("#sum_highest").val('');
            $("#modelTitle").html('添加会员等级');
            $('#bannerForm').attr('action',"{{url('/')}}/user_level/store");
            $('#newBannerModel').modal('show');
        }

        function onEditClick(id){
            $.ajax({
                url: "{{url('/')}}/user_level/detail/"+id,
                async: true,
                type: "GET",
                dataType:'json',
                success: function(recv){
                    if(recv.meta.code=='0'){
                        var val=recv.meta.error;
                        bootbox.alert(val, function(){
                        });
                    }else if(recv.meta.code=='1'){
                        $("#name").val(recv.meta.data.name);
                        $("#sum_lowest").val(recv.meta.data.sum_lowest);
                        $("#sum_highest").val(recv.meta.data.sum_highest);
                        $("#modelTitle").html('修改会员等级');

                        $('#bannerForm').attr('action','{{url("/")}}/user_level/update/'+id);
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
                url: "{{url('/')}}/user_level/delete/"+id,
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
                <h1 class="page-header">会员等级管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        会员等级列表
                        {{--<div style="margin-top: -5px;" class="btn-group pull-right">--}}
                            {{--<button onclick="onAddClick();"  class="btn btn-primary btn-circle">--}}
                                {{--<i class="glyphicon glyphicon-plus"></i>--}}
                            {{--</button>--}}
                            {{--<button onclick="reload()" class="btn btn-default btn-circle">--}}
                                {{--<i class="fa fa-refresh"></i>--}}
                            {{--</button>--}}
                        {{--</div>--}}
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>名称</th>
                                    <th>金额范围</th>
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
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modelTitle"></h4>
                    </div>
                    <div class="modal-body">

                        {{--<div class="form-group">--}}
                            {{--<label for="inputGoodsName" class="col-sm-2 control-label">等级名称</label>--}}
                            {{--<div class="col-sm-10">--}}
                                {{--<input type="text" class="form-control" id="name" name="name" placeholder="请输入等级名称">--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">金额区间</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="sum_lowest" name="sum_lowest" placeholder="请输入最低金额">
                            </div>
                            <div class="col-sm-1">
                                <label>--</label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="sum_highest" name="sum_highest" placeholder="请输入最高金额">
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
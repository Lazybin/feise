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

        function onTypeChange(){
            var type=$("#type").val();
            if(type==0){
                $("#div2").css('display','none');
                $("#div1").css('display','inline');
            }else{
                $("#div2").css('display','inline');
                $("#div1").css('display','none');
            }
        }

        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true,
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
                "iDisplayLength": 10,
                "lengthMenu" : [[5, 10, 20, 50, -1], [5, 10, 20, 50, "全部"]],
                "processing": true,
                "autoWidth": false,
                "serverSide": true,
                "searching": false,
                "ordering": false,
                "ajax": "{{url('/')}}/home_navigation/index",
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
                            return '<button type="button" onclick="onEditClick(\''+id+'\')" class="btn btn-primary btn-xs">编辑</button>&nbsp;';
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
                        "mData": 'subhead'
                    },
                    {
                        "targets":3,
                        "mData": 'type',
                        "mRender": function (data, type, full)
                        {
                            if(data==0)
                                return '图片模式';
                            else{
                                return '网页模式';
                            }
                        }
                    },
                    {
                        "targets": 4,
                        'sClass':'align-center',
                        "mData": 'sort'
                    }

                ]
            });

        });
        function onEditClick(id){
            $.ajax({
                url: "{{url('/')}}/home_navigation/detail/"+id,
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
                        if(recv.meta.data.type==0){
                            $cover.fileinput("refresh", {
                            initialPreview:['<img src="{{url('/')}}'+recv.meta.data.path+'" class="file-preview-image" >']
                            });
                        }else{
                            $("#action").val(recv.meta.data.action);
                        }



                        {{--$head.fileinput("refresh", {--}}
                            {{--initialPreview:['<img src="{{url('/')}}'+recv.meta.data.head_image+'" class="file-preview-image" >']--}}
                        {{--});--}}
                        $('#themesForm').attr('action',baseUrl+'/home_navigation/update/'+id);
                        $("#titleModel").html('修改');
                        $('#categoryModel').modal('show');
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
                <h1 class="page-header">首页按钮管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        按钮列表
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>标题</th>
                                    <th>副标题</th>
                                    <th>类型</th>
                                    <th>排序</th>
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



    <div class="modal fade" id="categoryModel">
        <form id="themesForm" enctype="multipart/form-data" class="row-border form-horizontal" method="post"  action="">
            {!! csrf_field() !!}
            <div class="modal-dialog">
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
                            <label for="inputGoodsName" class="col-sm-2 control-label">类型</label>
                            <div class="col-sm-10">
                                <select name="type" id="type" onchange="onTypeChange()" class="form-control">
                                    <option value="0">图片</option>
                                    <option value="1">网页</option>
                                </select>
                            </div>
                        </div>
                        <div id="div1" class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">封面图片</label>
                            <div class="col-sm-6">
                                <input id="coverImage" name="coverImage" type="file" class="file" data-preview-file-type="text" >
                            </div>
                        </div>
                        <div id="div2" class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">网页链接</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="action" name="action" placeholder="请输入网页链接">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sort" name="sort" placeholder="请输入排序" value="1">
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
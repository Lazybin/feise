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
    <script src="{{ url('/js/ueditor.config.js') }}"></script>
    <script src="{{ url('/js/ueditor.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var ue = UE.getEditor('container',{
                toolbars: [
                    ['source', 'undo', 'redo','bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc','simpleupload','insertimage']
                ],
                initialFrameHeight:320  //初始化编辑器高度,默认320
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
                "ajax": "{{url('/')}}/goods/index",
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
                        "mData": 'price'
                    },
                    {
                        "targets": 3,
                        'sClass':'align-center',
                        "mData": 'original_price'
                    },
                    {
                        "targets": 4,
                        'sClass':'align-center',
                        "mData": 'use_coupon'
                    },
                    {
                        "targets": 5,
                        'sClass':'align-center',
                        "mData": 'coupon_amount'
                    },
                    {
                        "targets": 6,
                        'sClass':'align-center',
                        "mData": 'express_way'
                    },
                    {
                        "targets": 7,
                        'sClass':'align-center',
                        "mData": 'express_fee'
                    },
                    {
                        "targets": 8,
                        'sClass':'align-center',
                        "mData": 'returned_goods'
                    },
                    {
                        "targets": 9,
                        'sClass':'align-center',
                        "mData": 'description'
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

        function onSubmit(){

            var container=$("#container").val();
            console.log(container);

            {{--var name=$("#name").val();--}}
            {{--var email=$("#email").val();--}}
            {{--var password=$("#password").val();--}}
            {{--var id=$("#id").val();--}}
            {{--var subUrl='';--}}
            {{--if(id!=-1){--}}
                {{--subUrl= "{{url('/')}}/permission/update/"+id;--}}
            {{--}else{--}}
                {{--subUrl= "{{url('/')}}/permission/store";--}}
            {{--}--}}
            {{--$.ajax({--}}
                {{--url: subUrl,--}}
                {{--async: true,--}}
                {{--type: "POST",--}}
                {{--dataType:'json',--}}
                {{--headers: {--}}
                    {{--'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
                {{--},--}}
                {{--data: {name:name, email:email,password:password},--}}
                {{--success: function(recv){--}}
                    {{--if(recv.meta.code=='0')--}}
                    {{--{--}}
                        {{--var val=recv.meta.error;--}}
                        {{--bootbox.alert(val, function() {--}}
                        {{--});--}}
                    {{--}--}}
                    {{--else if(recv.meta.code=='1')--}}
                    {{--{--}}
                        {{--window.location.reload();--}}
                    {{--}--}}
                    {{--return true;--}}
                {{--}--}}
            {{--});--}}
        }
    </script>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">商品管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        商品列表
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
                                    <th>价格</th>
                                    <th>原价</th>
                                    <th>礼券抵用</th>
                                    <th>快递方式</th>
                                    <th>快递费用</th>
                                    <th>七天退货</th>
                                    <th>商品描述</th>
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
        <form enctype="multipart/form-data" class="row-border form-horizontal" method="post"  action="{{url('/')}}/goods/store">
            {!! csrf_field() !!}
            <div class="modal-dialog" style="width:720px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="title"></h4>
                    </div>
                    <div class="modal-body">
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">商品名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="请输入商品名称">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">价格</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="price" name="price" placeholder="请输入商品价格">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">原价</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="original_price" name="original_price" placeholder="请输入商品原价">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">礼券抵用</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio"  name="use_coupon" id="useCouponRadios1" value="1">启用
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="use_coupon" id="useCouponRadios2" value="0">禁用
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">抵用金额</label>
                                <div class="col-sm-10">
                                    <input type="text" value="0" class="form-control" id="coupon_amount" name="coupon_amount" placeholder="请输入抵用金额">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">快递方式</label>
                                <div class="col-sm-10">
                                    <select class="form-control">
                                        <option value="1">免邮</option>
                                        <option value="2">普通快递</option>
                                        <option value="3">EMS快递</option>
                                        <option value="4">新疆、青海、西藏等地区</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">快递费用</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="express_fee" value="0" name="express_fee" placeholder="请输入快递费用">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">七天退货</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio"  name="returned_goods" id="returnedGoodsRadios1" value="1">支持
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="returned_goods" id="returnedGoodsRadios2" value="0">不支持
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">商品描述</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">商品详情</label>
                                <div class="col-sm-10">
                                    <script id="container" name="detailed_introduction" type="text/plain"></script>
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
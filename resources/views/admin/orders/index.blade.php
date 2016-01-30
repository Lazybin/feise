@extends('admin.layouts.master')
@section('title', ' 订单管理')

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
                "ajax": "{{url('/')}}/orders/index",
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
                            if(data.status==1){
                                return '<button type="button" onclick="onConfirmClick(\''+id+'\')" class="btn btn-primary btn-xs">确认发货</button>';
                            }else{
                                return '';
                            }

                        }
                    },
                    {
                        "targets": 0,
                        'sClass':'align-center',
                        "mData": 'id'
                    },
                    {
                        "targets": 1,
                        'sClass':'align-center',
                        "mData": 'user_id'
                    },
                    {
                        "targets": 2,
                        "mData": 'out_trade_no'
                    },
                    {
                        "targets": 3,
                        'sClass':'align-center',
                        "mData": 'consignee'
                    },
                    {
                        "targets": 4,
                        'sClass':'align-center',
                        "mData": 'shipping_address'
                    },
                    {
                        "targets": 5,
                        'sClass':'align-center',
                        "mData": 'mobile'
                    },
                    {
                        "targets": 6,
                        'sClass':'align-center',
                        "mData": 'total_fee'
                    },
                    {
                        "targets": 7,
                        'sClass':'align-center',
                        "mData": 'status',
                        "mRender": function (data, type, full)
                        {
                            switch(data){
                                case 0:
                                    return "待支付";
                                case 1:
                                    return "已支付";
                                case 2:
                                    return "取消";
                                case 3:
                                    return "已发货";
                                case 4:
                                    return "客户已签收，交易完成";
                            }
                        }
                    }

                ]
            });

        });


        function onConfirmClick(id){
            bootbox.confirm({
                size: 'small',
                message: "确认已经发货？",
                buttons: {
                    cancel: {
                        label: "取消"
                    },
                    confirm:{
                        label: "确认"
                    }
                },
                callback: function(result){
                    if(result==true){
                        var subUrl= "{{url('/')}}/orders/update/"+id;
                        $.ajax({
                            url: subUrl,
                            async: true,
                            type: "POST",
                            dataType:'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {status:3},
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
                }
            });
        }
    </script>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">订单管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        订单列表
                    </div>
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>用户id</th>
                                    <th>订单号</th>
                                    <th>收货人</th>
                                    <th>收货地址</th>
                                    <th>收货人电话</th>
                                    <th>支付金额</th>
                                    <th>状态</th>
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
    <div class="modal fade" id="categoryModel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="title"></h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">名称</label>
                            <input type="text" class="form-control" id="name" placeholder="请输入昵称">

                            <input type="hidden" class="form-control" id="id">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">排序</label>
                            <input type="text" class="form-control" id="sort" placeholder="请输入排序" value="1">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn_first" data-dismiss="modal" onclick="onSubmit()">提交</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection
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
        var t_tables;
        $(document).ready(function() {
            t_tables=$('#dataTables-example').DataTable({
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
                "lengthChange": false,
                "iDisplayLength": 10,
                "lengthMenu" : [[5, 10, 20, 50, -1], [5, 10, 20, 50, "全部"]],
                "processing": true,
                "autoWidth": false,
                "serverSide": true,
                "searching": false,
                "ordering": false,
                "ajax": {
                    "url": "{{url('/')}}/orders/index",
                    "data": function ( d ) {
                        d.number = $("#search_number").val();

                        var checkbox1 = document.getElementById('presell');//
                        if(checkbox1.checked){
                            d.presell = 1;
                        }else{
                            d.presell = 0;
                        }
                        d.status=$("#status").val();
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
                            var id = full.out_trade_no;
                            var ret='';
                            if(data.status==1){
                                ret+= '<button type="button" onclick="onAddClick(\''+id+'\')" class="btn btn-primary btn-xs">确认发货</button>&nbsp;';
                            }
                            ret+='<button type="button" onclick="onDetailClick(\''+full.id+'\')" class="btn btn-info btn-xs">查看详情</button>';
                            return ret;

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
                                    return "<font color='blue'>待支付</font>";
                                case 1:
                                    var ret="<font color='orange'>已支付，待发货</font>";
                                    if(full.is_remind==1)
                                        ret+=",<font color='red'>提醒发货</font>";
                                    ret+="";
                                    return ret;
                                case 2:
                                    return "<font >取消</font>";
                                case 3:
                                    return "<font color='purple'>已发货</font>";
                                case 4:
                                    return "<font color='green'>客户已签收，交易完成</font>";
                                case 5:
                                    return "<font color='red'>申请退款</font>";
                                case 6:
                                    return "<font color='red'>申请退款</font>";
                                case 7:
                                    return "<font >退款成功</font>";
                            }
                        }
                    }

                ]
            });

        });

        function onSearchClick(){
            t_tables.draw();
        }

        function onAddClick(id){
            $("#out_trade_no").val(id);
            $("#express_number").val('');
            $("#express_company_name").val('');
            $("#title").html('发货');

            $('#categoryModel').modal('show');
        }


        function onConfirmClick(){
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
                        var out_trade_no=$("#out_trade_no").val();
                        var express_number=$("#express_number").val();
                        var express_company_name=$("#express_company_name").val();
                        var subUrl= "{{url('/')}}/orders/update/"+out_trade_no;
                        $.ajax({
                            url: subUrl,
                            async: true,
                            type: "POST",
                            dataType:'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {status:3,express_company_name:express_company_name,express_number:express_number},
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
        function onDetailClick(id){
            $.ajax({
                url: "{{url('/')}}/orders/detail/"+id,
                async: true,
                type: "GET",
                dataType:'json',
                success: function(recv){
                    if(recv.meta.code=='0'){
                        var val=recv.meta.error;
                        bootbox.alert(val, function(){
                        });
                    }else if(recv.meta.code=='1'){
                        $("#out_trade_no_detail").html(recv.meta.data.out_trade_no);
                        $("#consignee_detail").html(recv.meta.data.consignee);
                        $("#shipping_address_detail").html(recv.meta.data.shipping_address);
                        $("#mobile_detail").html(recv.meta.data.mobile);
                        $("#total_fee_detail").html(recv.meta.data.total_fee);
                        $("#shipping_fee_detail").html(recv.meta.data.shipping_fee);
                        var status='';

                        switch(recv.meta.data.status){
                            case 0:
                                status="<font color='blue'>待支付</font>";
                                break;
                            case 1:
                                var ret="<font color='orange'>已支付，待发货</font>";
                                if(recv.meta.data.is_remind==1)
                                    ret+=",<font color='red'>提醒发货</font>";
                                ret+="";
                                status= ret;
                                break;
                            case 2:
                                status= "<font >取消</font>";
                                break;
                            case 3:
                                status= "<font color='purple'>已发货</font>";
                                break;
                            case 4:
                                status= "<font color='green'>客户已签收，交易完成</font>";
                                break;
                            case 5:
                                status= "<font color='red'>申请退款</font>";
                                break;
                            case 6:
                                status= "<font color='red'>申请退款</font>";
                                break;
                            case 7:
                                status= "<font >退款成功</font>";
                                break;
                        }
                        $("#status_detail").html(status);

                        $("#orderGoodsList tbody").html("");
                        $.each(recv.meta.data.goods_list,function(k,v){
                            var properties=eval(v.properties);
                            console.log(properties);
                            var str='';
                            $.each(properties,function(z,x){
                                str+= x.name+':'+ x.value+' ';
                            });
                            var presell;
                            if(v.goods.is_presell==1){
                                presell='是';
                            }else{
                                presell='否';
                            }
                            $("#orderGoodsList").append("<tr><td>"+v.goods.id+"</td><td>"+v.goods.name+"</td><td>"+str+"</td><td>"+v.num+"</td><td>"+presell+"</td></tr>")
                        });

                        $('#orderDetailModal').modal('show');

                    }
                    return true;
                }
            });
        }

        function onSubmit(){
            onConfirmClick();
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
                    {{--<div class="panel-body" style="padding-bottom:0;">--}}
                        {{--<div class="btn-toolbar">--}}
                            {{--<button onclick="onAddClick();" class="btn btn-primary" ><i class="fa fa-sign-in  fa-fw"> </i>发货</button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row form-group">
                            <div class="col-md-3" style="padding-right: 0;">
                                <label for="inputGoodsName" style="padding-right: 0;padding-left: 0" class="col-sm-3 control-label">订单号</label>
                                <div class="col-sm-9" style="padding-left: 0">
                                    <input class="form-control" id="search_number" type="text" />
                                </div>
                            </div>
                            <div class="col-md-2" style="padding-right: 0;padding-left: 0;">
                                <div class="col-sm-12" style="padding-left: 0">
                                    <select id="status" class="form-control" >
                                        <option value="-1" selected>状态</option>
                                        <option value="0">待支付</option>
                                        <option value="1">已支付，待发货</option>
                                        <option value="2">取消</option>
                                        <option value="3">已发货</option>
                                        <option value="4">客户已签收，交易完成</option>
                                        <option value="5">申请退款</option>
                                        <option value="7">退款成功</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1" style="padding-right: 0;padding-left: 0;">
                                <div class="col-sm-12" style="padding-left: 0;padding-right: 0">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="presell"> 预售
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding-right: 0;padding-left: 0;">
                                <button type="button" onclick="onSearchClick()" class="btn btn-primary">搜索</button>
                            </div>
                        </div>

                    </div>
                    <div class="panel-body" style="padding-top: 0;">
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="title"></h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">快递单号</label>
                            <input type="hidden" class="form-control" id="out_trade_no">
                            <input type="text" class="form-control" id="express_number" placeholder="请输入快递单号">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">快递公司</label>
                            <input type="text" class="form-control" id="express_company_name" placeholder="请输入快递公司名称">
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


    <!-- Modal dialog -->
    <div class="modal fade" id="orderDetailModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" >订单详情</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <input type="hidden" id="detailId" />

                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td colspan="7">订单单号：<span id="out_trade_no_detail"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            </tr>
                            <tr>
                                <td>收货人</td>
                                <td><span id="consignee_detail"></span></td>
                            </tr>
                            <tr>
                                <td>收货地址</td>
                                <td><span id="shipping_address_detail"></span></td>
                            </tr>
                            <tr>
                                <td>联系电话</td>
                                <td><span id="mobile_detail"></span></td>
                            </tr>
                            <tr>
                                <td>支付金额</td>
                                <td><span id="total_fee_detail"></span></td>
                            </tr>
                            <tr>
                                <td>快递费</td>
                                <td><span id="shipping_fee_detail"></span></td>
                            </tr>
                            <tr>
                                <td>订单状态</td>
                                <td><span id="status_detail"></span></td>
                            </tr>
                            </tbody>
                        </table>

                        <table id="orderGoodsList" class="table table-bordered">
                            <thead>
                            <tr align="center">
                                <td colspan="5">商品列表</td>
                            </tr>
                            <tr align="center">
                                <td>id</td>
                                <td>名称</td>
                                <td>属性</td>
                                <td>数量</td>
                                <td>预售</td>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection
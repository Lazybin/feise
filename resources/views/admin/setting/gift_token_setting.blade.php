@extends('admin.layouts.master')
@section('title', ' 控制台')

@include('admin.layouts.navbar')
@section('customercss')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('customerjs')
    <script src="{{ url('/js/bootbox.min.js') }}"></script>
    <script>
        function onEditClick(id){
            $.ajax({
                url: "{{url('/')}}/gift_token_setting/detail/"+id,
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
                        $("#sum").val(recv.meta.data.sum);
                        if(recv.meta.data.status==1){
                            $("#statusRadios1").attr("checked","checked");
                        }else{
                            $("#statusRadios2").attr("checked","checked");
                        }
                        $("#modelTitle").html('修改礼券设置');

                        $('#bannerForm').attr('action','{{url("/")}}/gift_token_setting/update/'+id);
                        $('#newBannerModel').modal('show');
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
                <h1 class="page-header">礼券设置</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        赠送开关
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>名称</th>
                                    <th>赠送金额</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($giftTokenSettings as $v)
                                    <tr><td>{{ $v['name'] }}</td>
                                        <td>{{ $v['sum'] }}</td>
                                        <td><?php if($v['status']==0) echo '关闭';else echo '开启'; ?></td>
                                        <td>
                                            <button type="button" onclick="onEditClick('{{$v['id']}}')" class="btn btn-primary btn-xs">编辑</button>
                                        </td>
                                    </tr>
                                @endforeach
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

                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">赠送金额</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sum" name="sum" placeholder="请输入赠送金额">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputGoodsName" class="col-sm-2 control-label">状态</label>
                            <div class="col-sm-9">
                                <label class="radio-inline radio">
                                    <input type="radio"  name="status" id="statusRadios1" value="1" required>开启
                                </label>
                                <label class="radio-inline radio">
                                    <input type="radio" name="status" id="statusRadios2" value="0" required>关闭
                                </label>
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
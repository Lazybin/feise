@extends('admin.layouts.master')
@section('title', ' 控制台')

@include('admin.layouts.navbar')
@section('customercss')
    <link href="{{ url('/bootstrap_switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet" />
@endsection
@section('customerjs')
    <script src="{{ url('/bootstrap_switch/dist/js/bootstrap-switch.min.js') }}"></script>
    <script>
        $("input[type=\"checkbox\"]").bootstrapSwitch();
        $("input[type=\"checkbox\"]").on('switchChange.bootstrapSwitch', function(event, state) {
            var id=this.attributes['data-id'].value;
            var status=state==true?1:0;
            $.ajax({
                url: "{{url('/')}}/gift_token_setting/update/"+id,
                async: true,
                type: "GET",
                dataType:'json',
                data: {status:status},
                success: function(recv){
//                    if(recv.meta.code=='0'){
//                        var val=recv.meta.error;
//                        bootbox.alert(val, function(){
//                        });
//                    }else if(recv.meta.code=='1'){
//                        window.location.reload();
//                    }
                    return true;
                }
            });
        });
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
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($giftTokenSettings as $v)
                                    <tr><td>{{ $v['name'] }}</td>
                                        <td>
                                            <input type="checkbox" data-id="{{$v['id']}}" data-on-text="开" data-off-text="关" @if($v['status'] == 1) checked @endif>
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


@endsection
@extends('admin.layouts.master')
@section('title', ' 控制台')

@include('admin.layouts.navbar')
@section('customercss')
    <link href="{{ url('../resources/assets/vendor/bootstrap-fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
@endsection
@section('customerjs')
    <script src="{{ url('../resources/assets/vendor/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script>
        $("#input-id").fileinput({
            rowseClass: "btn btn-primary btn-block",
            showCaption: false,
            showRemove: false,
            showUpload: false
        });
    </script>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">启动页管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-5">
                <form enctype="multipart/form-data" class="form-horizontal row-border" method="post"  action="{{url('/')}}/boot_page/store">
                    {!! csrf_field() !!}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            启动页图片
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-md-10">
                                    <img height="121px" id="img_cover" width="166px"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10">
                                    <input id="input-id" name="input-id" type="file" class="file" data-preview-file-type="text" >
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary btn_first" data-dismiss="modal" onclick="onSubmitAdmin()">提交</button>
                        </div>
                    </div>
                    <!-- /.panel -->
                </form>
            </div>
            <!-- /.col-lg-12 -->
        </div>
    </div>


@endsection
@extends('admin.layouts.master')
@section('title', ' 控制台')

@include('admin.layouts.navbar')
@section('customerjs')
    <script src="{{ url('/plupload/plupload.full.min.js') }}"></script>
    <script type="text/javascript">
        var siteurl="{{ url('/') }}/";
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'imgpic', // you can pass in id...
            container: document.getElementById('uploadimg'), // ... or DOM Element itself
            url : siteurl +'/upload/upload_image',
            flash_swf_url : siteurl + '/plupload/Moxie.swf',
            silverlight_xap_url : siteurl + '/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '1.9mb',
                mime_types: [
                    {title : "Image files", extensions : "jpg,gif,png"}
                ]
            },
            init: {
                PostInit: function() {
                    document.getElementById('filelist').innerHTML = '';
                },
                FilesAdded: function(up, files) {
                    if(files.length > 1){
                        alert("最多可以选择一张图片");
                        return false;
                    }
                    plupload.each(files, function(file) {
                        document.getElementById('filelist').innerHTML = '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                    });
                    uploader.start();
                },

                UploadProgress: function(up, file) {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                },
                FileUploaded:function(uploader,file,responseObject)
                {
                    $("#img_cover").attr("src",img_url+responseObject['response']);
                    $("#pic").val(responseObject['response']);
                },

                Error: function(up, err) {
                    document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
                }
            }
        });

        uploader.init();


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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        启动页图片
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form class="form-horizontal row-border" method="post"  action="">
                            <div class="form-group">
                                <div class="col-md-10">
                                    <img height="121px" id="img_cover" width="166px"  />
                                    <div class="rz_form_tx_input" id="uploadimg">
                                        <input type="hidden" id="pic" name="pic" >
                                        <input type="button" class="btn" value="上传图片" id="imgpic"/>
                                    </div>
                                    <div id="filelist" class="filelist"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.panel-body -->
                    <div class="panel-footer">
                        <button type="button" class="btn btn-primary btn_first" data-dismiss="modal" onclick="onSubmitAdmin()">提交</button>
                    </div>
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
    </div>


@endsection
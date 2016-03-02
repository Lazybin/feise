@extends('admin.layouts.master')
@section('title', ' 控制台')

@include('admin.layouts.navbar')
@section('customercss')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- DataTables CSS -->
    <link href="{{ url('../resources/assets/vendor/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ url('../resources/assets/vendor/datatables-responsive/css/dataTables.responsive.css') }}" rel="stylesheet">
    <link href="{{ url('/css/jquery.tagsinput.min.css') }}" rel="stylesheet">
    <link href="{{ url('../resources/assets/vendor/bootstrap-fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
    @endsection

    @section('customerjs')
            <!-- DataTables JavaScript -->
    <script src="{{ url('../resources/assets/vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('../resources/assets/vendor/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ url('/js/ueditor.config.js') }}"></script>
    <script src="{{ url('/js/ueditor.all.min.js') }}"></script>
    <script src="{{ url('/js/jquery.tagsinput.min.js') }}"></script>
    <script src="{{ url('../resources/assets/vendor/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ url('/js/bootbox.min.js') }}"></script>
    <script>
        var baseUrl="{{url('/')}}";
        var ue = UE.getEditor('container',{
            toolbars: [
                ['source', 'undo', 'redo','bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc','simpleupload','insertimage']
            ],
            initialFrameHeight:320  //初始化编辑器高度,默认320
        });

        var $input = $("#uploadImages");
        $input.fileinput({
            uploadUrl: "{{url('/upload_file')}}", // server upload action
            uploadAsync: true,
            showUpload: false, // hide upload button
            showRemove: false, // hide remove button
            minFileCount: 1,
            maxFileCount: 5,
            deleteUrl:"{{url('/upload_file')}}"
        }).on("filebatchselected", function(event, files) {
            // trigger upload method immediately after files are selected
            $input.fileinput("upload");
        }).on('filedeleted', function(event, key) {
            var str=$("#images").val();
            str=str.replace(key+',','');
            $("#images").val(str);
            console.log($("#images").val());
        }).on('fileuploaded', function(event, data, id, index) {
            var image_id=data.response.initialPreviewConfig[0].key;

            var str=$("#images").val();
            str+=image_id+',';
            $("#images").val(str);
            console.log($("#images").val());
        });

        var $cover = $("#coverImage");
        $cover.fileinput({
            rowseClass: "btn btn-primary",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            overwriteInitial: true
        });

        var $evaluationImage = $("#evaluationPersonImage");
        $evaluationImage.fileinput({
            rowseClass: "btn btn-primary",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            overwriteInitial: true
        });


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
                "iDisplayLength": 10,
                "lengthMenu" : [[5, 10, 20, 50, -1], [5, 10, 20, 50, "全部"]],
                "processing": true,
                "autoWidth": false,
                "serverSide": true,
                "searching": false,
                "ordering": false,
                "ajax": "{{url('/')}}/goods/index?show_putaway=1",
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
                            var ret= '<button type="button" onclick="onEditClick(\''+id+'\')" class="btn btn-primary btn-xs">编辑</button>&nbsp;';
                            if(full.is_putaway==1){
                                ret+='<button type="button" onclick="onPutawayClick(\''+id+'\',0,this)" class="btn btn-warning btn-xs">下架</button>&nbsp;';
                            }else{
                                ret+='<button type="button" onclick="onPutawayClick(\''+id+'\',1,this)" class="btn btn-info btn-xs">上架</button>&nbsp;';
                            }
                            ret+='<button type="button" onclick="onDelete(\''+id+'\')" class="btn btn-danger btn-xs">删除</button>';
                            return ret;
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
                        "mData": 'use_coupon',
                        "mRender": function (data, type, full)
                        {
                            if(data==0)
                                return '禁用';
                            else
                                return '启用';
                        }
                    },
                    {
                        "targets": 5,
                        'sClass':'align-center',
                        "mData": 'coupon_amount'
                    },
                    {
                        "targets": 6,
                        'sClass':'align-center',
                        "mData": 'express_way',
                        "mRender": function (data, type, full)
                        {
                            switch(data){
                                case 1:
                                    return '免邮';
                                case 2:
                                    return '普通快递';
                                case 3:
                                    return 'EMS快递';
                                case 4:
                                    return '新疆、青海、西藏等地区';
                                default:
                                    return '';
                            }
                        }
                    },
                    {
                        "targets": 7,
                        'sClass':'align-center',
                        "mData": 'express_fee'
                    },
                    {
                        "targets": 8,
                        'sClass':'align-center',
                        "mData": 'returned_goods',
                        "mRender": function (data, type, full)
                        {
                            if(data==0)
                                return '禁用';
                            else
                                return '启用';
                        }
                    }

                ]
            });

        });
        function onAddClick(){
            $("#name").val('');
            $("#parentCategory").val(-1);
            $("#activityClassification").val(-1);
            $("#freePost").val(-1);
            $("#price").val('');
            $("#original_price").val('');

            $("#useCouponRadios1").attr("checked","checked");
            $("#isTaobaokeRadios2").attr("checked","checked");
            $("#isPresellRadios2").attr("checked","checked");
            $("#putawayRadios1").attr("checked","checked");

            $('#isPutawayDiv').css("display","inline");

            $("#presell_time").val('');
            $("#platform").val(-1);
            $("#taobaoke_url").val('');
            $("#coupon_amount").val('');
            $("#num").val('');
            $("#express_way").val(1);
            $("#express_fee").val(0);
            $("#returnedGoodsRadios1").attr("checked","checked");

            $("#images").val('');

            $input.fileinput("refresh", {
                initialPreview:[],
                initialPreviewConfig:[]
            });

            $evaluationImage.fileinput("refresh", {
                initialPreview:[]
            });

            $cover.fileinput("refresh", {
                initialPreview:[]
            });

            initCategory(0,-1);

            $("#evaluation_content").val('');
            $("#description").val('');
            ue.setContent('');
            $("#propertyContainer").html('');

            $("#title").html('添加商品');



            $('#goodsForm').attr('action',baseUrl+'/goods/store');

            $('#adminModel').modal('show');
        }

        function onPutawayClick(id,status,obj){
            $.ajax({
                url: "{{url('/')}}/goods/update_putaway?id="+id+"&status="+status,
                async: true,
                type: "GET",
                dataType:'json',
                success: function(recv){
                    if(recv.meta.code=='0'){
                        var val=recv.meta.error;
                        bootbox.alert(val, function(){
                        });
                    }else if(recv.meta.code=='1'){
                        var ret= '<button type="button" onclick="onEditClick(\''+id+'\')" class="btn btn-primary btn-xs">编辑</button>&nbsp;';
                        if(status==1){
                            ret+='<button type="button" onclick="onPutawayClick(\''+id+'\',0,this)" class="btn btn-warning btn-xs">下架</button>&nbsp;';
                        }else{
                            ret+='<button type="button" onclick="onPutawayClick(\''+id+'\',1,this)" class="btn btn-info btn-xs">上架</button>&nbsp;';
                        }
                        ret+='<button type="button" onclick="onDelete(\''+id+'\')" class="btn btn-danger btn-xs">删除</button>';

                        $(obj).parent().html(ret);
                        //return ret;
                    }
                    return true;
                }
            });
        }

        function onEditClick(id){
            $.ajax({
                url: "{{url('/')}}/goods/detail/"+id,
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
                        $("#parentCategory").val(recv.meta.data.category.pid);
                        initCategory(recv.meta.data.category.pid,recv.meta.data.category.id);
                        $("#price").val(recv.meta.data.price);
                        $("#num").val(recv.meta.data.num);
                        $("#original_price").val(recv.meta.data.original_price);
                        if(recv.meta.data.use_coupon==1){
                            $("#useCouponRadios1").attr("checked","checked");
                        }else{
                            $("#useCouponRadios2").attr("checked","checked");
                        }

                        if(recv.meta.data.is_presell==1){
                            $("#isPresellRadios1").attr("checked","checked");
                        }else{
                            $("#isPresellRadios2").attr("checked","checked");
                        }

                        $("#presell_time").val(recv.meta.data.presell_time);
                        $("#coupon_amount").val(recv.meta.data.coupon_amount);
                        $("#express_way").val(recv.meta.data.express_way);
                        $("#express_fee").val(recv.meta.data.express_fee);
                        if(recv.meta.data.returned_goods==1){
                            $("#returnedGoodsRadios1").attr("checked","checked");
                        }else{
                            $("#returnedGoodsRadios2").attr("checked","checked");
                        }

                        if(recv.meta.data.is_taobaoke==1){
                            $("#isTaobaokeRadios1").attr("checked","checked");
                        }else{
                            $("#isTaobaokeRadios2").attr("checked","checked");
                        }

                        if(recv.meta.data.is_putaway==1){
                            $("#putawayRadios1").attr("checked","checked");
                        }else{
                            $("#putawayRadios2").attr("checked","checked");
                        }

                        $('#isPutawayDiv').css("display","none");

                        $("#platform").val(recv.meta.data.platform);
                        $("#taobaoke_url").val(recv.meta.data.taobaoke_url);

                        $("#activityClassification").val(recv.meta.data.activityClassification);

                        $("#freePost").val(recv.meta.data.freePost);

                        $("#description").val(recv.meta.data.goods_description);
                        $("#evaluation_content").val(recv.meta.data.evaluation_content);

                        ue.setContent(recv.meta.data.detailed_introduction);

                        var str='';

                        var initialPreview=[];
                        var initialPreviewConfig=[];
                        $.each(recv.meta.data.images, function (key, item) {
                            initialPreview.push('<img src="'+item.path+'" class="file-preview-image" >');
                            var t={
                                url:"{{url('/')}}"+"/upload_file/delete",
                                key:item.image_id
                            };
                            initialPreviewConfig.push(t);

                            str+=item.image_id+',';
                        });
                        $("#images").val(str);
                        $input.fileinput("refresh", {
                            initialPreview:initialPreview,
                            initialPreviewConfig:initialPreviewConfig
                        });


                        $cover.fileinput("refresh", {
                            initialPreview:['<img src="{{url('/')}}'+recv.meta.data.cover+'" class="file-preview-image" >']
                        });

                        $evaluationImage.fileinput("refresh", {
                            initialPreview:['<img src="{{url('/')}}'+recv.meta.data.evaluation_person_image+'" class="file-preview-image" >']
                        });


                        var propertyContainer = $("#propertyContainer");
                        var str_html='';
                        var tags=[];

                        $.each(recv.meta.data.properties, function (key, item) {

                            str_html +='<div class="form-group">';
                            str_html +='<label class="col-sm-2 control-label">'+item['name']+'</label>';
                            str_html +='<div class="col-sm-10">';
                            var value='';
                            $.each(item.values, function (k, v) {
                               value+= v.value+',';
                            });
                            value=value.substring(0,value.length-1);
                            if(item['type']==0){
                                str_html+='<input value="'+value+'" type="text" id="property_'+item['id']+'" name="property_'+item['id']+'" value="" />';
                                tags.push("property_"+item['id']);
                            }else{
                                str_html +='<input value="'+value+'" type="text" class="form-control" id="property_'+item['id']+'" name="property_'+item['id']+'" placeholder="请输入'+item['name']+'">';
                            }
                            str_html +='</div></div>';
                        });
                        propertyContainer.html(str_html);
                        $.each(tags,function(k,v){
                            $('#'+v).tagsInput({
                                'width':'700px',
                                'height':'42px',
                                'defaultText':'添加选项'
                            });
                        });

                        $("#title").html('修改商品');

                        $('#goodsForm').attr('action',baseUrl+'/goods/update/'+id);
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
                url: "{{url('/')}}/goods/delete/"+id,
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

        function initCategory(pid,id){
            var category = $("#category");
            var str_html='';
            if(id==-1){
                str_html='<option value="-1" selected>请选择分类</option>';
            }else{
                str_html='<option value="-1">请选择分类</option>';
            }
            if(pid!=0&&pid!=-1) {
                $.ajax({
                    url: "{{url('/')}}/category/index?length=100&pid=" + pid,
                    async: true,
                    type: "get",
                    dataType: 'json',
                    success: function (data) {
                        var rows=data.data;
                        $.each(rows, function (key, item) {
                            if(item['id']==id){
                                str_html += '<option value="' + item['id'] + '" selected>' + item['name'] + '</option>';
                            }else{
                                str_html += '<option value="' + item['id'] + '">' + item['name'] + '</option>';
                            }

                        });
                        category.html(str_html);
                    }
                });
            }else{
                category.html(str_html);
            }
        }
        function initPropertyContainer(category_id){
            var propertyContainer = $("#propertyContainer");
            var str_html='';
            var tags=[];
            if(category_id!=0&&category_id!=-1) {
                $.ajax({
                    url: "{{url('/')}}/category/get_property/" + category_id,
                    async: true,
                    type: "get",
                    dataType: 'json',
                    success: function (data) {
                        var rows=data.data;
                        $.each(rows, function (key, item) {

                            str_html +='<div class="form-group">';
                            str_html +='<label class="col-sm-2 control-label">'+item['name']+'</label>';
                            str_html +='<div class="col-sm-10">';
                            if(item['type']==0){
                                str_html+='<input  type="text" id="property_'+item['id']+'" name="property_'+item['id']+'" value="" />';
                                tags.push("property_"+item['id']);
                            }else{
                                str_html +='<input type="text" class="form-control" id="property_'+item['id']+'" name="property_'+item['id']+'" placeholder="请输入'+item['name']+'">';
                            }
                            str_html +='</div></div>';
                        });
                        propertyContainer.html(str_html);
                        $.each(tags,function(k,v){
                            $('#'+v).tagsInput({
                                'width':'700px',
                                'height':'42px',
                                'defaultText':'添加选项'
                            });
                        });
                    }
                });
            }else{
                propertyContainer.html(str_html);
            }
        }

        function onParentCategoryChange(){
            var pid=$("#parentCategory").val();
            initCategory(pid,-1);
        }

        function onCategoryChange(){
            var category_id=$("#category").val();
            initPropertyContainer(category_id);
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
                                    <th>抵用金额</th>
                                    <th>快递方式</th>
                                    <th>快递费用</th>
                                    <th>七天退货</th>
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
        <form id="goodsForm" enctype="multipart/form-data" class="row-border form-horizontal" method="post"  action="">
            {!! csrf_field() !!}
            <div class="modal-dialog modal-lg">
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
                                <label class="col-sm-2 control-label">类别</label>
                                <div class="col-sm-4">
                                    <select id="parentCategory" class="form-control" onchange="onParentCategoryChange()">
                                        <option value="-1" selected>请选择类别</option>
                                        @foreach($categories as $c)
                                        <option value="{{$c['id']}}">{{$c['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <select name="category" id="category" class="form-control" onchange="onCategoryChange()">
                                        <option value="-1" selected>请选择类别</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">活动类别</label>
                                <div class="col-sm-4">
                                    <select id="activityClassification" name="activityClassification"  class="form-control" >
                                        <option value="-1" selected>请选择类别</option>
                                        @foreach($activity_classification as $ac)
                                            <option value="{{$ac['id']}}">{{$ac['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="col-sm-6" style="color:red">(如该商品不是活动商品请勿选择活动类别)</span>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">包邮类别</label>
                                <div class="col-sm-4">
                                    <select id="freePost" name="freePost"  class="form-control" >
                                        <option value="-1" selected>请选择类别</option>
                                        @foreach($free_post as $fe)
                                            <option value="{{$fe['id']}}">{{$fe['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="col-sm-6" style="color:red">(如该商品不是包邮活动商品请勿选择类别)</span>
                            </div>
                            <div id="propertyContainer">

                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">价格</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="price" name="price" placeholder="请输入商品价格">
                                </div>
                                <label for="inputGoodsName" class="col-sm-1 control-label">原价</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="original_price" name="original_price" placeholder="请输入商品原价">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">礼券抵用</label>
                                <div class="col-sm-3">
                                    <label class="radio-inline">
                                        <input type="radio"  name="use_coupon" id="useCouponRadios1" value="1">启用
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="use_coupon" id="useCouponRadios2" value="0">禁用
                                    </label>
                                </div>
                                <label for="inputGoodsName" class="col-sm-2 control-label">抵用金额</label>
                                <div class="col-sm-4">
                                    <input type="text" value="0" class="form-control" id="coupon_amount" name="coupon_amount" placeholder="请输入抵用金额">
                                </div>
                            </div>
                            <div class="form-group" id="isPutawayDiv" >
                                <label for="inputGoodsName" class="col-sm-2 control-label">是否上架</label>
                                <div class="col-sm-3">
                                    <label class="radio-inline">
                                        <input type="radio"  name="is_putaway" id="putawayRadios1" value="1">是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_putaway" id="putawayRadios2" value="0">否
                                    </label>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">淘宝客商品</label>
                                <div class="col-sm-3">
                                    <label class="radio-inline">
                                        <input type="radio"  name="is_taobaoke" id="isTaobaokeRadios1" value="1">是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_taobaoke" id="isTaobaokeRadios2" value="0">不是
                                    </label>
                                </div>
                                <label for="inputGoodsName" class="col-sm-2 control-label">平台</label>
                                <div class="col-sm-4">
                                    <select name="platform" id="platform" class="form-control" >
                                        <option value="-1" selected>请选择平台</option>
                                        <option value="1">淘宝</option>
                                        <option value="2">京东</option>
                                        <option value="3">天猫</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">预售</label>
                                <div class="col-sm-3">
                                    <label class="radio-inline">
                                        <input type="radio"  name="is_presell" id="isPresellRadios1" value="1">是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_presell" id="isPresellRadios2" value="0">不是
                                    </label>
                                </div>
                                <label for="inputGoodsName" class="col-sm-2 control-label">预售期</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="presell_time" name="presell_time" placeholder="请输入预售期">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">淘宝客链接</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="taobaoke_url" name="taobaoke_url" placeholder="请输入淘宝客链接">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">快递方式</label>
                                <div class="col-sm-4">
                                    <select name="express_way" id="express_way" class="form-control">
                                        <option value="1">免邮</option>
                                        <option value="2">普通快递</option>
                                        <option value="3">EMS快递</option>
                                        <option value="4">新疆、青海、西藏等地区</option>
                                    </select>
                                </div>
                                <label for="inputGoodsName" class="col-sm-1 control-label">费用</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="express_fee" value="0" name="express_fee" placeholder="请输入快递费用">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">15天退货</label>
                                <div class="col-sm-3">
                                    <label class="radio-inline">
                                        <input type="radio"  name="returned_goods" id="returnedGoodsRadios1" value="1">支持
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="returned_goods" id="returnedGoodsRadios2" value="0">不支持
                                    </label>
                                </div>
                                <label for="inputGoodsName" class="col-sm-2 control-label">库存</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="num" name="num" placeholder="请输入商品库存">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">评测师</label>
                                <div class="col-sm-6">
                                    <input id="evaluationPersonImage" name="evaluationPersonImage" type="file" class="file" data-preview-file-type="text" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">评测内容</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="evaluation_content" name="evaluation_content" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">封面图片</label>
                                <div class="col-sm-6">
                                    <input id="coverImage" name="coverImage" type="file" class="file" data-preview-file-type="text" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoodsName" class="col-sm-2 control-label">展示图片</label>
                                <div class="col-sm-10">
                                    <input id="uploadImages" name="uploadImages[]" type="file" multiple class="file-loading">
                                    <input id="images" name="images" type="hidden" value="">
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
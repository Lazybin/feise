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
    <script src="{{ url('/js/bootbox.min.js') }}"></script>
    <script>

        var tableProperty=$('#dataTables-property').DataTable({
            responsive: true,
            "lengthMenu" : [[5, 10, 20, 50, -1], [5, 10, 20, 50, "全部"]],
            "processing": true,
            "iDisplayLength": 10,
            "autoWidth": false,
            "searching": false,
            "ordering": false,
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
                        return '<button type="button" onclick="onEditPropertyClick(\''+id+'\')" class="btn btn-primary btn-xs">编辑</button>&nbsp;'+
                                '<button type="button" onclick="onCategoryPropertyDelete(\''+id+'\')" class="btn btn-danger btn-xs">删除</button>';

                    }
                },
                {
                    "targets":0,
                    "mData": 'name'
                },
                {
                    "targets": 1,
                    'sClass':'align-center',
                    "mData": 'type',
                    "mRender": function (data, type, full)
                    {
                        switch (data){
                            case 0:
                                return '选项';
                            case 1:
                                return '数字';
                            default :
                                return '';
                        }
                    }
                }

            ]

        });
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
                "ajax": "{{url('/')}}/category/index?pid={{$pid}}",
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
                            var pid=full.pid;
                            if(pid==0){
                                return '<a class="btn btn-success btn-xs" href="{{url('/category?pid=')}}'+id+'" role="button">查看子项</a>&nbsp;'+
                                        '<button type="button" onclick="onEditClick(\''+id+'\')" class="btn btn-primary btn-xs">编辑</button>&nbsp;'+
                                        '<button type="button" onclick="onDelete(\''+id+'\')" class="btn btn-danger btn-xs">删除</button>';
                            }else{
                                return '<button type="button" onclick="onEditClick(\''+id+'\')" class="btn btn-primary btn-xs">编辑</button>&nbsp;'+
                                        '<button type="button" onclick="onEditPropertyModelClick(\''+id+'\')" class="btn btn-primary btn-xs">编辑属性</button>&nbsp;'+
                                        '<button type="button" onclick="onDelete(\''+id+'\')" class="btn btn-danger btn-xs">删除</button>';
                            }

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
                        "mData": 'sort'
                    }

                ]
            });

        });
        function onAddClick(){
            $("#name").val('');
            $("#title").html('添加分类');
            $("#id").val(-1);

            $('#categoryModel').modal('show');
        }

        function onEditClick(id){
            $.ajax({
                url: "{{url('/')}}/category/detail/"+id,
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
                        $("#sort").val(recv.meta.data.sort);
                        $("#title").html('修改分类');
                        $('#categoryModel').modal('show');
                    }
                    return true;
                }
            });
        }

        function onEditPropertyModelClick(id){
            $('#categoryPropertyModel').modal('show');
            $('#category_id').val(id);
            tableProperty.ajax.url("{{url('/')}}/category/get_property/"+id).load();
        }


        function onCategoryPropertyDelete(id){
            $.ajax({
                url: "{{url('/')}}/category/delete_property/"+id,
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
                        tableProperty.ajax.reload();
                    }
                    return true;
                }
            });
        }

        function onAddPropertyClick(){
            $("#propertyName").val('');
            $("#type").val('');
            $("#propertyTitle").html('添加属性');
            $("#id").val(-1);

            $('#newCategoryPropertyModel').modal('show');
        }

        function onEditPropertyClick(id){
            $.ajax({
                url: "{{url('/')}}/category/property_detail/"+id,
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
                        $("#propertyName").val(recv.meta.data.name);
                        $("#propertyType").val(recv.meta.data.type);
                        $("#title").html('修改属性');
                        $('#newCategoryPropertyModel').modal('show');
                    }
                    return true;
                }
            });
        }



        function onDelete(id){
            $.ajax({
                url: "{{url('/')}}/category/delete/"+id,
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

        function onPropertySubmit(){

            var name=$("#propertyName").val();
            var type=$("#propertyType").val();
            var id=$("#id").val();
            var category_id=$('#category_id').val();
            var subUrl='';
            if(id!=-1){
                subUrl= "{{url('/')}}/category/property_update/"+id;
            }else{
                subUrl= "{{url('/')}}/category/store_property";
            }
            $.ajax({
                url: subUrl,
                async: true,
                type: "POST",
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {name:name, type:type,category_id:category_id},
                success: function(recv){
                    if(recv.meta.code=='0')
                    {
                        var val=recv.meta.error;
                        bootbox.alert(val, function() {
                        });
                    }
                    else if(recv.meta.code=='1')
                    {
                        tableProperty.ajax.reload();
                    }
                    return true;
                }
            });
        }

        function onSubmit(){

            var name=$("#name").val();
            var sort=$("#sort").val();
            var id=$("#id").val();
            var pid='{{$pid}}';
            var subUrl='';
            if(id!=-1){
                subUrl= "{{url('/')}}/category/update/"+id;
            }else{
                subUrl= "{{url('/')}}/category/store";
            }
            $.ajax({
                url: subUrl,
                async: true,
                type: "POST",
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {name:name, sort:sort,pid:pid},
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
    </script>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">分类管理</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        分类列表
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body" style="padding-bottom:0;">
                        <div class="btn-toolbar">
                            @if($pid!='0')
                            <a class="btn btn-default" href="{{url('/category')}}" role="button"><i class="fa fa-arrow-left fa-fw"> </i>返回</a>
                            @endif
                            <button onclick="onAddClick();" class="btn btn-primary" ><i class="fa fa-plus fa-fw"> </i>添加</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>分类名称</th>
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

    <!-- Modal dialog -->
    <div class="modal fade" id="categoryPropertyModel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="onAddPropertyClick();" class="btn btn-primary" ><i class="fa fa-plus fa-fw"> </i>添加</button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="title"></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="category_id" name="category_id" />
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-property">
                            <thead>
                            <tr>
                                <th>名称</th>
                                <th>类型</th>
                                <th>操作</th>
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

    <!-- Modal dialog -->
    <div class="modal fade" id="newCategoryPropertyModel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="propertyTitle"></h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">名称</label>
                            <input type="text" class="form-control" id="propertyName" placeholder="请输入名称">

                            <input type="hidden" class="form-control" id="id">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">类别</label>
                            <select name="propertyType" id="propertyType" class="form-control">
                                <option value="0">选项</option>
                                <option value="1">数字</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn_first" data-dismiss="modal" onclick="onPropertySubmit()">提交</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
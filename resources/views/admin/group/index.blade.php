@extends('admin.layouts.app')
@section('css')
    @parent
    <style type="text/css">
        .table {
            margin-bottom: 20px !important;
        }

        table, tr, th, td {
            text-align: center;
        }

        tr:last-child {
            border-bottom: 1px solid #ddd;
        }

        .pagination {
            margin-top: 0;
        }

        .dataTables_info {
            padding: 10px;
        }
    </style>
@endsection
@section('content')
    <section class="wrapper wrapper-margin-padding">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        角色列表
                    </header>

                    <div class="dataTables_wrapper form-inline">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="dataTables_filter pull-left col-sm-4">
                                    <label>
                                        <button class="btn btn-sm btn-danger" type="button" id="add-group-btn">
                                            <i class="icon-plus"></i> 新增
                                        </button>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-8">

                                <div class="dataTables_filter pull-left col-sm-4">
                                    <label>
                                        名称: <input type="text" value="{{ $serch }}" id="serch-text"
                                                   class="form-control" placeholder="角色名称">
                                    </label>
                                </div>

                                <div class="dataTables_filter pull-left col-sm-4">
                                    <label>
                                        <button class="btn btn-sm btn-info btn-serch" type="button">
                                            <i class="icon-search"></i> 搜索
                                        </button>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <table class="table table-striped border-top" id="sample_1">
                            <thead>
                            <tr>
                                <th class="hidden-phone">编号</th>
                                <th class="hidden-phone">角色名称</th>
                                <th class="hidden-phone">更新时间</th>
                                <th class="hidden-phone">操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($data->first())
                                @foreach($data->all() as $item)
                                    <tr class="odd gradeX">
                                        <td>{{ $item->id }}</td>
                                        <td class="hidden-phone">{{ $item->name }}</td>
                                        <td class="hidden-phone">{{ $item->updated_at }}</td>
                                        <td class="hidden-phone">
                                            <button class="btn btn-xs btn-primary btn-edit-group" title="编辑"
                                                    data-name="{{ $item->name }}" data-id="{{ $item->id }}">
                                                <i class="icon-edit"></i>
                                            </button>

                                            <button class="btn btn-xs btn-danger btn-del-group" title="删除"
                                                    data-name="{{ $item->name }}" data-id="{{ $item->id }}">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">没有找到您要的数据</td>
                                </tr>
                            @endif


                            </tbody>
                        </table>

                        <!-- Page start -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_info pull-left">
                                    一共 {{ $data->total() }} 条数据
                                </div>
                                <div class="dataTables_info pull-right">
                                    {!! $data->appends(['serch'=>$serch])->links() !!}
                                </div>
                            </div>
                        </div>
                        <!-- Page start -->
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>


    <!-- Model start-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add-group-modal"
         class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    <h4 class="modal-title">新增角色</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 control-label">角色名称</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="role-name" placeholder="角色昵称">
                                <p class="help-block" style="color: red;display: none;">messages.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="button" class="btn btn-primary" id="btn-sub-add-group">确定</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <!-- Model end -->
@endsection

@section('js')
    @parent
    <script>
        $(function () {
            $('#add-group-btn').on('click', function () {
                $('#add-group-modal').modal('show');
            });

            $('#btn-sub-add-group').on('click', function () {
                var name = $('#role-name').val();

                var url = "{{ route('admin.rights.group.create') }}";
                var data = {name: name, _token: "{{ csrf_token() }}"};
                $.post(url, data, function (e) {
                    if (e.status) {
                        location.reload(true);
                    } else {
                        layer.alert(e.message, {
                            icon: 2,
                            skin: 'layui-layer-lan',
                            anim: 4,
                            closeBtn: 0
                        });
                    }
                }, 'json');
            });

            $('.btn-del-group').on('click', function () {
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');

                var lay = layer.confirm('您确定删除该角色？？一旦删除将无法找回', {
                    skin: 'layui-layer-molv',
                    btn: ['确定', '放弃'] //按钮
                }, function () {
                    var url = "{{ route('admin.rights.group.delete') }}";
                    var data = {id: id, name: name, _token: "{{ csrf_token() }}"};

                    $.post(url, data, function (e) {
                        if (e.status) {
                            location.reload(true);
                        } else {
                            layer.alert(e.message, {
                                icon: 2,
                                skin: 'layui-layer-molv',
                                closeBtn: 0
                            });
                        }
                    }, 'json');
                }, function () {
                    layer.close(lay);
                });
            });

            $('.btn-serch').on('click', function () {
                var serch = $('#serch-text').val();

                location.href = "{{ route('admin.rights.group.index') }}?serch=" + serch;
            });

            $('.btn-edit-group').on('click', function () {
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');

                layer.open({
                    title: name,
                    scrollbar: false, //禁止页面滑动
                    type: 2,
                    area: ['600px', '600px'],
                    skin: ' layui-layer-molv', //加上边框
                    content: ["{{ route('admin.rights.group.info') }}?id=" + id, 'no']
                });
            });


        });
    </script>


@endsection
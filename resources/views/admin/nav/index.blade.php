@extends('admin.layouts.app')

@section('css')
    @parent
    <style type="text/css">
        table, tr, th, td {
            text-align: center;
        }
    </style>
@endsection


@section('content')

    <section class="wrapper wrapper-margin-padding">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        首页导航
                    </header>


                    <div class="dataTables_wrapper form-inline">

                        <div class="row">
                            <div class="col-sm-8">
                                <form method="get" action="{{ route('admin.demo.nav.index') }}">

                                    <div class="dataTables_filter pull-left col-sm-5">
                                        <label class="pull-left">
                                            <input type="text" value="{{ $title or '' }}" name="title"
                                                   class="form-control" placeholder="导航标题">
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-3">
                                        <label>
                                            <button class="btn btn-sm btn-info btn-serch" type="submit">
                                                <i class="icon-search"></i> 搜索
                                            </button>

                                            <a href="javascript:void(0);"
                                               class="btn btn-sm btn-success btn-add">
                                                <i class="icon-plus"></i> 新增
                                            </a>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table class="table table-striped border-top">
                            <thead>
                            <tr>
                                <th style="width:8px;">
                                    <input type="checkbox" class="group-checkable"
                                           data-set="#sample_1 .checkboxes"/>
                                </th>
                                <th class="hidden-phone">导航编号</th>
                                <th class="hidden-phone">导航标题</th>
                                <th class="hidden-phone">创建时间</th>
                                <th class="hidden-phone">更新时间</th>
                                <th class="hidden-phone">操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($data->first())
                                @foreach($data->all() as $item)
                                    <tr class="odd gradeX">
                                        <td><input type="checkbox" class="checkboxes" value="1"/></td>
                                        <td class="hidden-phone">{{ $item->id }}</td>
                                        <td class="hidden-phone">{{ $item->title }}</td>

                                        <td class="hidden-phone">{{ $item->created_at }}</td>
                                        <td class="hidden-phone">{{ $item->updated_at }}</td>
                                        <td class="hidden-phone">
                                            <a href="{{ route('admin.demo.nav.edit',['id'=>$item->id]) }}"
                                               target="_blank" title="详情" class="btn btn-xs btn-info btn-info"
                                               data-id="{{ $item->id }}">
                                                <i class="icon-edit"></i>
                                            </a>

                                            <a href="javascript:void(0);" target="_blank" title="删除"
                                               class="btn btn-xs btn-danger btn-del" data-id="{{ $item->id }}">
                                                <i class="icon-trash"></i>
                                            </a>
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
                        <div class="row" style="padding-bottom: 15px;">
                            <div class="col-sm-12">
                                <div class="dataTables_info pull-left">
                                    一共 {{ $data->total() }} 条数据
                                </div>
                                <div class="dataTables_info pull-right">
                                    {!! $data->appends(['title'=>$title])->links() !!}
                                </div>
                            </div>
                        </div>
                        <!-- Page start -->
                    </div>


                </section>
            </div>
        </div>
    </section>


    <div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1"
         id="create-nav-model" class="modal fade in">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    <h4 class="modal-title">新增首页导航</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 control-label">导航标题</label>
                            <div class="col-lg-10">
                                <input type="text" id="title" class="form-control" placeholder="填写导航标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="button" class="btn btn-success nav-add-btn">提&nbsp;交</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    @parent
    <script>
        $(function () {

            $('.btn-add').on('click', function () {
                $('#create-nav-model').modal('show');
            });

            $('.nav-add-btn').on('click', function () {
                var title = $('#title').val();

                if (!title.length) {
                    layer.alert('请填写导航标题', {
                        icon: 2,
                        skin: 'layui-layer-molv'
                    });
                    return false;
                }

                var url = "{{ route('admin.demo.nav.create.action') }}";
                var data = {title: title, _token: "{{ csrf_token() }}"};

                $.post(url, data, function (e) {

                    if (e.status) {
                        location.reload(true);
                    } else {
                        layer.alert(e.message, {
                            icon: 2,
                            skin: 'layui-layer-molv'
                        });
                        return false;
                    }

                }, 'json');
            });

            $('.btn-del').on('click', function () {
                var id = $(this).attr('data-id');

                var lay = layer.confirm('您确定删除该首页导航？？', {
                    title: "提示",
                    skin: 'layui-layer-molv',
                    btn: ['确定', '再想想'] //按钮
                }, function () {

                    var url = "{{ route('admin.demo.nav.delete.action') }}";
                    var data = {id: id, _token: "{{ csrf_token() }}"};

                    $.post(url, data, function (e) {
                        if (e.status) {
                            location.reload(true);
                        } else {
                            layer.alert(e.message, {
                                icon: 2,
                                skin: 'layui-layer-molv'
                            });
                            return false;
                        }
                    }, 'json');

                }, function () {
                    layer.close(lay);
                });
            });
        });
    </script>
@endsection

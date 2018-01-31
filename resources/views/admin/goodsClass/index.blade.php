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
                        商品分类管理
                    </header>

                    <div class="dataTables_wrapper form-inline">

                        <div class="row">
                            <div class="col-sm-8">
                                <form method="get" action="{{ route('admin.goods.class.index') }}">

                                    <div class="dataTables_filter pull-left col-sm-5">
                                        <label class="pull-left">
                                            <input type="text" value="{{ $name or '' }}" name="name"
                                                   class="form-control" placeholder="分类名称">
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-3">
                                        <label>
                                            <button class="btn btn-sm btn-info btn-serch" type="submit">
                                                <i class="icon-search"></i> 搜索
                                            </button>

                                            <button class="btn btn-sm btn-success btn-add" type="button">
                                                <i class="icon-plus"></i> 添加
                                            </button>
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
                                <th class="hidden-phone">分类编号</th>
                                <th class="hidden-phone">分类名称</th>
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
                                        <td class="hidden-phone">{{ $item->name }}</td>
                                        <td class="hidden-phone">{{ $item->updated_at }}</td>
                                        <td class="hidden-phone">
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
                                    {!! $data->appends(['name'=>$name])->links() !!}
                                </div>
                            </div>
                        </div>
                        <!-- Page start -->
                    </div>

                </section>
            </div>
        </div>
    </section>

    <div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="class-add-model"
         class="modal fade in">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    <h4 class="modal-title">商品分类</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal"
                          role="form" method="post" action="{{ route('admin.goods.class.create') }}">

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 control-label">分类名称</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" placeholder="分类名称" name="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-primary">登 录</button>
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
                $('#class-add-model').modal('show');
            });

            $('.btn-del').on('click', function () {
                var id = $(this).attr('data-id');

                var lay = layer.confirm('确定删除该商品分类？一旦删除有可能引发商品分类失效', {
                    btn: ['确定', '放弃'], //按钮
                    skin: 'layui-layer-molv'
                }, function () {
                    delClass(id);
                }, function () {
                    layer.close(lay);
                });
            });
        });

        function delClass(id) {

            var url = "{{ route('admin.goods.class.delete') }}";
            var data = {id: id, _token: "{{ csrf_token() }}"};

            $.post(url, data, function (e) {
                if (!e.status) {
                    layer.alert(e.message, {
                        icon: 2,
                        skin: 'layui-layer-molv'
                    });
                    return false;
                } else {
                    location.reload(true);
                }
            }, 'json');
        }
    </script>
@endsection

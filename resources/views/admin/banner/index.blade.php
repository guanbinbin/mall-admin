@extends('admin.layouts.app')

@section('css')
    @parent
    <style type="text/css">
        table, tr, th, td {
            text-align: center;
        }

        .img-relter {
            width: 60px;
            height: 24px;
        }
    </style>
@endsection


@section('content')

    <section class="wrapper wrapper-margin-padding">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        首页轮播
                    </header>

                    <div class="dataTables_wrapper form-inline">

                        <div class="row">
                            <div class="col-sm-8">
                                <form method="get" action="{{ route('admin.demo.banner.index') }}">

                                    <div class="dataTables_filter pull-left col-sm-5">
                                        <label class="pull-left">
                                            <input type="text" value="{{ $title or '' }}" name="title"
                                                   class="form-control" placeholder="轮播标题">
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-3">
                                        <label>
                                            <button class="btn btn-sm btn-info btn-serch" type="submit">
                                                <i class="icon-search"></i> 搜索
                                            </button>

                                            <a href="{{ route('admin.demo.banner.create') }}"
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
                                <th class="hidden-phone">轮播编号</th>
                                <th class="hidden-phone">轮播标题</th>
                                <th class="hidden-phone">轮播图片</th>
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
                                        <td class="hidden-phone">
                                            <img class="img-relter" src="{{ getCover($item->image)->path }}"/>
                                        </td>
                                        <td class="hidden-phone">{{ $item->created_at }}</td>
                                        <td class="hidden-phone">{{ $item->updated_at }}</td>
                                        <td class="hidden-phone">
                                            <a href="{{ route('admin.demo.banner.edit',['id'=>$item->id]) }}"
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
                                    <td colspan="7">没有找到您要的数据</td>
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

@endsection

@section('js')
    @parent
    <script>
        $(function () {
            $('.btn-del').on('click', function () {
                var self = $(this);

                var id = self.attr('data-id');

                var lay = layer.confirm('您确定删除该轮播信息？？', {
                    title: "提示",
                    skin: 'layui-layer-molv',
                    btn: ['确定', '再想想'] //按钮
                }, function () {
                    var url = "{{ route('admin.demo.banner.delete.action') }}";
                    var data = {id: id, _token: "{{ csrf_token() }}"};

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
        });
    </script>
@endsection

@extends('admin.layouts.app')

@section('css')
    @parent
    <style type="text/css">
        table, tr, th, td {
            text-align: center;
        }

        .img-avatar {
            width: 40px;
        }

        .m-bot15 {
            margin-bottom: 0;
        }
    </style>
@endsection


@section('content')

    <section class="wrapper wrapper-margin-padding">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        商品管理
                    </header>

                    <div class="dataTables_wrapper form-inline">

                        <div class="row">
                            <div class="col-sm-12">
                                <form method="get" action="{{ route('admin.goods.base.index') }}">
                                    <div class="dataTables_filter pull-left col-sm-2">
                                        <label class="pull-left">
                                            <input type="text" value="{{ $input['id'] or '' }}" name="id"
                                                   class="form-control" placeholder="商品编号">
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-2">
                                        <label class="pull-left">
                                            <input type="text" value="{{ $input['name'] or '' }}" name="name"
                                                   class="form-control" placeholder="商品名称">
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-2">
                                        <label class="pull-left">
                                            <select class="form-control m-bot15" name="goods_status">
                                                <option value="">全部</option>
                                                <option value="{{ \App\Models\GoodsModel::GOODS_UP }}"
                                                        @if($input['goods_status'] == \App\Models\GoodsModel::GOODS_UP)
                                                        selected @endif>上架
                                                </option>
                                                <option value="{{ \App\Models\GoodsModel::GOODS_BOTTOM }}"
                                                        @if($input['goods_status'] == \App\Models\GoodsModel::GOODS_BOTTOM)
                                                        selected @endif>下架
                                                </option>
                                            </select>
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-2">
                                        <label>
                                            <button class="btn btn-sm btn-info btn-serch" type="submit">
                                                <i class="icon-search"></i> 搜索
                                            </button>

                                            <a class="btn btn-sm btn-success btn-add"
                                               href="{{ route('admin.goods.base.create') }}">
                                                <i class="icon-plus"></i> 添加
                                            </a>

                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-right col-sm-3">
                                        <label>
                                            <a class="btn btn-sm btn-warning all-up">
                                                <i class="icon-long-arrow-up"></i> 上架
                                            </a>

                                            <a class="btn btn-sm btn-danger all-down"
                                               href="javascript:void(0);">
                                                <i class="icon-long-arrow-down"></i> 下架
                                            </a>

                                            <a class="btn btn-sm btn-success all-del">
                                                <i class="icon-trash"></i> 删除
                                            </a>

                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table class="table table-striped border-top" id="sample_1">
                            <thead>
                            <tr>
                                <th style="width:8px;">
                                    <input type="checkbox" class="group-checkable checkbox-all"/>
                                </th>
                                <th class="hidden-phone">商品编号</th>
                                <th class="hidden-phone">商品主图</th>
                                <th class="hidden-phone">商品名称</th>
                                <th class="hidden-phone">商品价格</th>
                                <th class="hidden-phone">货架状态</th>
                                <th class="hidden-phone">上传时间</th>
                                <th class="hidden-phone">更新时间</th>
                                <th class="hidden-phone">操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($data->first())
                                @foreach($data->all() as $item)
                                    <tr class="odd gradeX">
                                        <td><input type="checkbox" class="checkboxes" value="{{ $item->id }}"/></td>
                                        <td class="hidden-phone">{{ $item->id }}</td>
                                        <td class="hidden-phone">
                                            <a href="{{ getCover($item->image)->path }}" target="_blank">
                                                <img class="img img-responsive img-thumbnail img-avatar"
                                                     src="{{ getCover($item->image)->path }}">
                                            </a>
                                        </td>
                                        <td class="hidden-phone">{{ $item->name }}</td>
                                        <td class="hidden-phone">{{ $item->price }}</td>
                                        <td class="hidden-phone">
                                            @if($item->goods_status == \App\Models\GoodsModel::GOODS_BOTTOM)
                                                <button class="btn btn-xs btn-danger">下架</button>
                                            @endif

                                            @if($item->goods_status == \App\Models\GoodsModel::GOODS_UP)
                                                <button class="btn btn-xs btn-success">上架</button>
                                            @endif
                                        </td>
                                        <td class="hidden-phone">{{ $item->created_at }}</td>
                                        <td class="hidden-phone">{{ $item->updated_at }}</td>
                                        <td class="hidden-phone">
                                            <a href="{{ route('admin.goods.base.edit',['id'=>$item->id]) }}"
                                               class="btn btn-xs btn-primary btn-edit-group">
                                                <i class="icon-edit"></i>
                                            </a>

                                            @if($item->goods_status == \App\Models\GoodsModel::GOODS_BOTTOM)
                                                <a href="javascript:void(0);" title="上架"
                                                   class="btn btn-xs btn-warning goods-up" data-id="{{ $item->id }}">
                                                    <i class="icon-long-arrow-up"></i>
                                                </a>
                                            @endif

                                            @if($item->goods_status == \App\Models\GoodsModel::GOODS_UP)
                                                <a href="javascript:void(0);" title="下架"
                                                   class="btn btn-xs btn-danger goods-down" data-id="{{ $item->id }}">
                                                    <i class="icon-long-arrow-down"></i>
                                                </a>
                                            @endif
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
                    </div>
                    <!-- Page start -->
                    <div class="row" style="padding-bottom: 15px;">
                        <div class="col-sm-12">
                            <div class="dataTables_info pull-left">
                                一共 {{ $data->total() }} 条数据
                            </div>
                            <div class="dataTables_info pull-right">
                                {!! $data->appends(['id'=>$input['id'],'name'=>$input['name'],
                                'goods_status'=>$input['goods_status']])->links() !!}
                            </div>
                        </div>
                    </div>
                    <!-- Page start -->

                </section>
            </div>
        </div>
    </section>

@endsection

@section('js')
    @parent
    <script>
        $(function () {

            $('.goods-up').on('click', function () {
                var id = $(this).attr('data-id');

                var lay = layer.confirm('您确定上架该商品？？', {
                    title: "提示",
                    skin: 'layui-layer-molv',
                    btn: ['确定', '再想想'] //按钮
                }, function () {
                    var url = "{{ route('admin.goods.base.up') }}";
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

            $('.goods-down').on('click', function () {
                var id = $(this).attr('data-id');

                var lay = layer.confirm('您确定下架该商品？？', {
                    title: "提示",
                    skin: 'layui-layer-molv',
                    btn: ['确定', '再想想'] //按钮
                }, function () {
                    var url = "{{ route('admin.goods.base.down') }}";
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

            $('.checkbox-all').on('click', function () {
                if ($('.checkbox-all').is(':checked')) {
                    $('.checkboxes').each(function (key, val) {
                        $(val).attr('checked', true);
                    });
                } else {
                    $('.checkboxes').each(function (key, val) {
                        $(val).attr('checked', false);
                    });
                }
            });

            $('.all-up').on('click', function () {
                multi("{{ \App\Models\GoodsModel::GOODS_UP }}");

            });

            $('.all-down').on('click', function () {
                multi("{{ \App\Models\GoodsModel::GOODS_BOTTOM }}");

            });

            function multi(status) {

                var ids = new Array();
                $(".checkboxes:checked").each(function (key, val) {
                    ids[key] = $(val).val();
                });

                var url = "{{ route('admin.goods.base.multi') }}";
                var data = {ids: ids, action: status, _token: "{{ csrf_token() }}"};

                $.post(url, data, function (e) {
                    if (!e.status) {
                        layer.alert(e.message, {
                            icon: 2,
                            skin: 'layui-layer-molv',
                            closeBtn: 0
                        });
                    } else {
                        location.reload(true);
                    }
                }, 'json');
            }

            $('.all-del').on('click', function () {
                var ids = new Array();
                $(".checkboxes:checked").each(function (key, val) {
                    ids[key] = $(val).val();
                });

                var url = "{{ route('admin.goods.base.delete') }}";
                var data = {ids: ids, _token: "{{ csrf_token() }}"};

                $.post(url, data, function (e) {
                    if (!e.status) {
                        layer.alert(e.message, {
                            icon: 2,
                            skin: 'layui-layer-molv',
                            closeBtn: 0
                        });
                    } else {
                        location.reload(true);
                    }
                }, 'json');
            });

        });
    </script>
@endsection
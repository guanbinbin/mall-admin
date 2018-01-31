@extends('admin.layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('/static/tree/css/style.css') }}"/>
    <style type="text/css">
        .breadcrumb, .active {
            background-color: rgba(255, 255, 255, 0);
        }

        .text-right {
            text-align: right !important;
        }

        .m-bot15 {
            margin-bottom: 0;
        }

        .radio-inline + .radio-inline, .checkbox-inline + .checkbox-inline {
            margin-left: 0;
        }
    </style>

@endsection

@section('content')
    <section class="wrapper wrapper-margin-padding">
        <div class="row">
            <div class="col-lg-6">
                <section class="panel">
                    <header class="panel-heading">
                        权限列表
                    </header>

                    <div class="panel-body">

                        <div class="form-group">

                            <ul class="tree">

                            @foreach($allRule as $item)

                                @if(!isset($item['son']))
                                    <!-- 只有一级的菜单 -->
                                        <li>
                                            <a href="javascript:;">
                                                <label id="one04">
                                                    <i class="{{ $item['icon'] }}"></i>
                                                    <p>
                                                        {{ $item['name'] }} ({{ $item['route'] }})
                                                    </p>
                                                </label>
                                            </a>
                                        </li>
                                @else
                                    <!-- 有两级的菜单 -->
                                        <li>
                                            <a href="javascript:;">
                                                <label id="one01">
                                                    <i class="{{ $item['icon'] }}"></i>
                                                    <p>{{ $item['name'] }} ({{ $item['route'] }})</p>
                                                </label>
                                            </a>


                                            <ul>
                                                @foreach($item['son'] as $sonItem)
                                                    <li>
                                                        <a href="javascript:;">
                                                            <label id="two02">
                                                                <i></i>
                                                                <p>{{ $sonItem['name'] }} ({{ $sonItem['route'] }})</p>
                                                            </label>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </li>
                                    @endif
                                @endforeach

                            </ul>

                            <input type="hidden" name="" id="checkGroup" value="1,2,3,4"/>

                        </div>
                        <form class="form-horizontal" role="form">


                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button type="button" class="btn btn-danger">删&nbsp;除</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>


            <div class="col-lg-6">
                <section class="panel">
                    <header class="panel-heading">
                        权限详情
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-3 control-label text-right">父级</label>
                                <div class="col-lg-9">
                                    <select class="form-control" id="parent-id">
                                        <option value="0">Root</option>
                                        @foreach($rule as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword1" class="col-lg-3 control-label text-right">标题</label>
                                <div class="col-lg-9">
                                    <input type="text" id="rule-name" class="form-control" placeholder="输入标题">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword1" class="col-lg-3 control-label text-right">图标</label>
                                <div class="col-lg-9">

                                    <div class="input-group m-bot15">
                                        <input type="text" id="icon" class="form-control" placeholder="输入图标(Root必填)">
                                        <span class="input-group-btn">
                                        <button class="btn btn-info" id="more-icon" type="button">
                                            <a href="{{ route('admin.icon') }}" target="_blank"> 更多图标!</a>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword1" class="col-lg-3 control-label text-right">路径</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" placeholder="输入Route" id="route">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-lg-3 text-right" for="inputSuccess">角色</label>
                                <div class="col-lg-9">

                                    @foreach($role as $item)
                                        <label class="checkbox-inline">
                                            <input name="role" type="checkbox"
                                                   value="{{ $item->id }}"> {{ $item->name }}
                                        </label>
                                    @endforeach

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button type="button" class="btn btn-success" id="rule-add-btn">新&nbsp;增</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection

@section('js')
    @parent
    <script type="text/javascript" src="{{ asset('/static/tree/js/action.js') }}"></script>

    <script>
        $(function () {
            $('#rule-add-btn').on('click', function () {
                var parent = $('#parent-id option:selected').val();
                var name = $('#rule-name').val();
                var icon = $('#icon').val();
                var route = $('#route').val();
                var role = new Array();
                $("input[name='role']:checkbox:checked").each(function (key, val) {
                    role[key] = $(this).val();
                });

                if (!name.length) {
                    layer.alert('请填写菜单标题', {
                        icon: 2,
                        skin: 'layui-layer-molv'
                    });
                    return false;
                }

                if (!route.length) {
                    layer.alert('请填写菜单路径', {
                        icon: 2,
                        skin: 'layui-layer-molv'
                    });
                    return false;
                }

                if (!role.length) {
                    layer.alert('请选择菜单所属角色', {
                        icon: 2,
                        skin: 'layui-layer-molv'
                    });
                    return false;
                }

                var url = "{{ route('admin.rights.rule.create') }}";
                var data = {
                    parent_id: parent,
                    name: name,
                    icon: icon,
                    route: route,
                    role: role,
                    _token: "{{ csrf_token() }}"
                };

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


            });
        });
    </script>
@endsection
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
    </style>
@endsection

@section('content')
    <section class="wrapper wrapper-margin-padding">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        管理员列表
                    </header>

                    <div class="dataTables_wrapper form-inline">
                        <div class="row">
                            <div class="col-sm-12">
                                <form method="get" action="{{ route('admin.rights.users.index') }}">
                                    <div class="dataTables_filter pull-left col-sm-3">
                                        <label class="pull-left">
                                            编号: <input type="text" value="{{ $input['id'] or '' }}" name="id"
                                                       class="form-control" placeholder="管理员编号">
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-3">
                                        <label class="pull-left">
                                            名称: <input type="text" value="{{ $input['name'] or '' }}" name="name"
                                                       class="form-control" placeholder="管理员名称">
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-3">
                                        <label class="pull-left">
                                            邮箱: <input type="text" value="{{ $input['email'] or '' }}" name="email"
                                                       class="form-control" placeholder="管理员邮箱">
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-3">
                                        <label>
                                            <button class="btn btn-sm btn-info btn-serch" type="submit">
                                                <i class="icon-search"></i> 搜索
                                            </button>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table class="table table-striped border-top" id="sample_1">
                            <thead>
                            <tr>
                                <th style="width:8px;">
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes"/>
                                </th>
                                <th class="hidden-phone">管理员编号</th>
                                <th class="hidden-phone">管理员头像</th>
                                <th class="hidden-phone">管理员名称</th>
                                <th class="hidden-phone">管理员邮箱</th>
                                <th class="hidden-phone">更新时间</th>
                                <th class="hidden-phone">操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($data->first())
                                @foreach($data as $item)
                                    <tr class="odd gradeX">
                                        <td><input type="checkbox" class="checkboxes" value="1"/></td>
                                        <td class="hidden-phone">{{ $item->id }}</td>
                                        <td class="hidden-phone">
                                            <img class="img img-responsive img-thumbnail img-avatar"
                                                 src="{{ getCover($item->avatar)->path }}">
                                        </td>
                                        <td class="hidden-phone">{{ $item->name }}</td>
                                        <td class="hidden-phone">{{ $item->email }}</td>
                                        <td class="hidden-phone">{{ $item->updated_at }}</td>
                                        <td class="hidden-phone">
                                            <a href="{{ route('admin.rights.users.edit',['id'=>$item->id]) }}"
                                               target="_blank" class="btn btn-xs btn-primary btn-edit-group"
                                               title="详情">
                                                <i class="icon-eye-open"></i>
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
                                    {!! $data->links() !!}
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
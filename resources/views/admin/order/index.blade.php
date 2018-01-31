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
                        订单管理
                    </header>

                    <div class="dataTables_wrapper form-inline">

                        <div class="row">
                            <div class="col-sm-12">
                                <form method="get" action="{{ route('admin.order.base.index') }}">
                                    <div class="dataTables_filter pull-left col-sm-2">
                                        <label class="pull-left">
                                            <input type="text" value="{{ $input['order_sn'] or '' }}" name="order_sn"
                                                   class="form-control" placeholder="订单编号">
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-2">
                                        <label class="pull-left">
                                            <input type="text" value="{{ $input['pay_sn'] or '' }}" name="pay_sn"
                                                   class="form-control" placeholder="支付单号">
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-2">
                                        <label class="pull-left">
                                            <select class="form-control m-bot15" name="order_status">
                                                <option @if($input['order_status'] == 100)
                                                        selected
                                                        @endif value="100">
                                                    全部
                                                </option>
                                                <option @if($input['order_status'] == \App\Models\OrderModel::ORDER_CANCEL)
                                                        selected
                                                        @endif
                                                        value="{{ \App\Models\OrderModel::ORDER_CANCEL }}">
                                                    已取消
                                                </option>
                                                <option @if($input['order_status'] == \App\Models\OrderModel::ORDER_NORMAL)
                                                        selected
                                                        @endif
                                                        value="{{ \App\Models\OrderModel::ORDER_NORMAL }}">
                                                    未付款
                                                </option>
                                                <option @if($input['order_status'] == \App\Models\OrderModel::ORDER_ALREADY_PAY)
                                                        selected
                                                        @endif
                                                        value="{{ \App\Models\OrderModel::ORDER_ALREADY_PAY }}">
                                                    已付款
                                                </option>
                                                <option @if($input['order_status'] == \App\Models\OrderModel::ORDER_DELIVERY)
                                                        selected
                                                        @endif
                                                        value="{{ \App\Models\OrderModel::ORDER_DELIVERY }}">
                                                    已发货
                                                </option>
                                                <option @if($input['order_status'] == \App\Models\OrderModel::ORDER_GET)
                                                        selected
                                                        @endif
                                                        value="{{ \App\Models\OrderModel::ORDER_GET }}">
                                                    已收货
                                                </option>
                                                <option @if($input['order_status'] == \App\Models\OrderModel::ORDER_OVER)
                                                        selected
                                                        @endif
                                                        value="{{ \App\Models\OrderModel::ORDER_OVER }}">
                                                    已完成
                                                </option>
                                            </select>
                                        </label>
                                    </div>

                                    <div class="dataTables_filter pull-left col-sm-2">
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
                                <th class="hidden-phone">订单编号</th>
                                <th class="hidden-phone">支付单号</th>
                                <th class="hidden-phone">订单金额</th>
                                <th class="hidden-phone">购买人员</th>
                                <th class="hidden-phone">创建时间</th>
                                <th class="hidden-phone">订单状态</th>
                                <th class="hidden-phone">操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($data->first())
                                @foreach($data->all() as $item)
                                    <tr class="odd gradeX">
                                        <td class="hidden-phone">{{ $item->order_sn }}</td>
                                        <td class="hidden-phone">
                                            {{ $item->pay_sn }}
                                        </td>
                                        <td class="hidden-phone"><i class="icon-jpy"></i> {{ $item->order_amount }}</td>
                                        <td class="hidden-phone">{{ $item->buy_name }}</td>
                                        <td class="hidden-phone">{{ date('Y-m-d H:i:s',$item->create_time) }}</td>
                                        <td class="hidden-phone">

                                            @if($item->order_status == \App\Models\OrderModel::ORDER_CANCEL)
                                                <button class="btn btn-xs btn-danger">已取消</button>
                                            @endif

                                            @if($item->order_status == \App\Models\OrderModel::ORDER_NORMAL)
                                                <button class="btn btn-xs btn-danger">未付款</button>
                                            @endif

                                            @if($item->order_status == \App\Models\OrderModel::ORDER_ALREADY_PAY)
                                                <button class="btn btn-xs btn-success">已付款</button>
                                            @endif

                                            @if($item->order_status == \App\Models\OrderModel::ORDER_DELIVERY)
                                                <button class="btn btn-xs btn-info">已发货</button>
                                            @endif

                                            @if($item->order_status == \App\Models\OrderModel::ORDER_GET)
                                                <button class="btn btn-xs btn-warning">已收货</button>
                                            @endif

                                            @if($item->order_status == \App\Models\OrderModel::ORDER_OVER)
                                                <button class="btn btn-xs btn-success">已完成</button>
                                            @endif
                                        </td>
                                        <td class="hidden-phone">
                                            <a href="{{ route('admin.order.base.edit',['id'=>$item->id]) }}"
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
                                {!! $data->appends([
                                'order_sn'=>$input['order_sn'],'pay_sn'=>$input['pay_sn'],
                                'order_status'=>$input['order_status']
                                ])->links() !!}
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

        });
    </script>
@endsection
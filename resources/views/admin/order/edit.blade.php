@extends('admin.layouts.app')

@section('css')
    @parent
    <style type="text/css">
        .table th, .table td {
            text-align: center;
            vertical-align: middle !important;
        }

        .img {
            max-width: 50px;
            max-height: 50px;
        }

        .code {
            padding: 2px 4px;
            font-size: 90%;
            color: #FF6C60;
            white-space: nowrap;
            background-color: #f9f2f4;
            border-radius: 4px;
        }

        .dataTables_info {
            margin-bottom: 10px;
        }

        tr:last-child {
            border-bottom: 1px solid #ddd;
        }
    </style>
@endsection


@section('content')
    <section class="wrapper wrapper-margin-padding">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        订单详情
                    </header>
                    <div class="panel-body bio-graph-info">
                        <div class="row">
                            <div class="bio-row">
                                <p><span>订单编号 </span>: <code>{{ $data['order_sn'] }}</code></p>
                            </div>
                            <div class="bio-row">
                                <p><span>支付单号 </span>: <code>{{ $data['pay_sn'] }}</code></p>
                            </div>

                            <div class="bio-row">
                                <p><span>收货人员 </span>: {{ $address['name'] }}</p>
                            </div>
                            <div class="bio-row">
                                <p><span>收货地址 </span>: {{ $address['address'] }}</p>
                            </div>

                            <div class="bio-row">
                                <p><span>联系方式 </span>: {{ $address['phone'] }}</p>
                            </div>
                            <div class="bio-row">
                                <p><span>付款方式 </span>: 在线支付</p>
                            </div>

                            <div class="bio-row">
                                <p>
                                    <span>订单总价 </span>:
                                    <code>
                                        <i class="icon-jpy"></i> {{ $data['order_amount'] }}
                                    </code>
                                </p>
                            </div>
                            <div class="bio-row">
                                <p>
                                    <span>订单状态 </span>:
                                    @if($data->order_status == \App\Models\OrderModel::ORDER_CANCEL)
                                        <a class="btn btn-xs btn-danger">已取消</a>
                                    @endif

                                    @if($data->order_status == \App\Models\OrderModel::ORDER_NORMAL)
                                        <a class="btn btn-xs btn-danger">未付款</a>
                                    @endif

                                    @if($data->order_status == \App\Models\OrderModel::ORDER_ALREADY_PAY)
                                        <a class="btn btn-xs btn-success">已付款</a>
                                    @endif

                                    @if($data->order_status == \App\Models\OrderModel::ORDER_DELIVERY)
                                        <a class="btn btn-xs btn-info">已发货</a>
                                    @endif

                                    @if($data->order_status == \App\Models\OrderModel::ORDER_GET)
                                        <a class="btn btn-xs btn-warning">已收货</a>
                                    @endif

                                    @if($data->order_status == \App\Models\OrderModel::ORDER_OVER)
                                        <a class="btn btn-xs btn-success">已完成</a>
                                    @endif
                                </p>
                            </div>
                            <div class="bio-row">
                                <p>
                                    <span>订单操作 </span>:
                                    {{--@if($data['order_state'] == \App\Models\Order::ORDER_STATUS_NORMAL)--}}
                                    {{--<button class="btn btn-primary btn-xs pay-close" data-id="{{ $data['id'] }}">关闭交易--}}
                                    {{--</button>--}}
                                    {{--@endif--}}

                                    {{--@if($data['order_state'] == \App\Models\Order::ORDER_STATUS_ACCOUNT_PAID)--}}
                                    {{--<button class="btn btn-primary btn-xs deliver-goods">发货</button>--}}
                                    {{--@endif--}}

                                    {{--@if($data['order_state'] == \App\Models\Order::ORDER_STATUS_NORMAL)--}}
                                    {{--<button class="btn btn-primary btn-xs">更改地址</button>--}}
                                    {{--@endif--}}

                                    {{--@if($data['order_state'] == \App\Models\Order::ORDER_STATUS_NORMAL ||--}}
                                    {{--$data['order_state'] == \App\Models\Order::ORDER_STATUS_ACCOUNT_PAID)--}}
                                    {{--<button class="btn btn-primary btn-xs edit-shop-explain-btn">更新备注</button>--}}
                                    {{--@endif--}}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <section class="panel">
            <header class="panel-heading">
                商品信息
            </header>
            <div class="dataTables_wrapper form-inline">
                <table class="table table-hover border-top">
                    <thead>
                    <tr>
                        <th> 商品图片</th>
                        <th> 商品名称</th>
                        <th> 成交价格</th>
                        <th> 商品数量</th>
                        <th> 价格小记</th>
                        <th> 商品状态</th>
                        <th> 物流单号</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($goods->all() as $item)
                        <tr>
                            <td>
                                <img src="{{ getCover($item->goods_image)->path }}"
                                     class="img-thumbnail img-responsive img">
                            </td>
                            <td>{{ $item->goods_name }}</td>
                            <td><span class="code"><i class="icon-jpy"></i> {{ $item->goods_price }}</span></td>
                            <td>x {{ $item->goods_num }}</td>
                            <td>
                                <span class="code">
                                <i class="icon-jpy"></i> {{ ($item->goods_price * $item->goods_num) }}
                                </span>
                            </td>
                            <td>{{ $item->is_delivery ? '已发货':'待发货' }}</td>
                            <td>
                                <code data-id="{{ $item->code }}">
                                    {!!
                                    $item->logistics_no ? "<span class='logistics_no'>".$item->logistics_no.'</span>' : '暂无'
                                    !!}
                                </code>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="dataTables_info pull-right">
                            <h4>
                                运费：<code><i class="icon-jpy"></i> 0</code>
                                总金额: <code><i class="icon-jpy"></i> {{ $data['order_amount'] }}</code>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="panel">

            <header class="panel-heading">
                订单日志
            </header>

            <div class="panel-body profile-activity">

                @foreach($log->all() as &$item)
                    <div class="activity terques">
                        <span>
                            <i class="icon-shopping-cart"></i>
                        </span>
                        <div class="activity-desk">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="arrow"></div>
                                    <i class="fa fa-time"></i>
                                    <h4>{{ $item->created_at }}</h4>
                                    <p>{!! $item->msg !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </section>

@endsection

@section('js')
    @parent
    <script>
        $(function () {

        });
    </script>
@endsection
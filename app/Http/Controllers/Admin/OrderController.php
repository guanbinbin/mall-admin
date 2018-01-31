<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderGoodsModel;
use App\Models\OrderLogModel;
use App\Models\OrderModel;
use App\Models\UsersAddressModel;
use Illuminate\Http\Request;

class OrderController extends CommonController
{
    /**
     * 描述：订单中心
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $order_sn = $request->get('order_sn', false);
        $pay_sn = $request->get('pay_sn', false);
        $order_status = $request->get('order_status', 100);

        $OrderModel = new OrderModel();

        $data = $OrderModel->getLists($order_sn, $pay_sn, $order_status);

        $input = [
            'order_sn' => $order_sn, 'pay_sn' => $pay_sn, 'order_status' => $order_status
        ];

        return view('admin.order.index', ['data' => $data, 'input' => $input]);
    }

    /**
     * 描述：订单详情
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $id = $request->get('id', null);

        $OrderModel = new OrderModel();
        $OrderGoodsModel = new OrderGoodsModel();
        $OrderLogModel = new OrderLogModel();
        $UsersAddressModel = new UsersAddressModel();

        $data = $OrderModel->getFind($id, [
            'id', 'order_sn', 'pay_sn', 'order_amount', 'order_status', 'address_id'
        ]);
        $goodsData = $OrderGoodsModel->getOrderGoods($id);
        $logData = $OrderLogModel->getOrderLog($id);
        $addressData = $UsersAddressModel->getFind($data->address_id);

        return view('admin.order.edit', [
            'data' => $data, 'goods' => $goodsData, 'log' => $logData, 'address' => $addressData
        ]);

    }
}

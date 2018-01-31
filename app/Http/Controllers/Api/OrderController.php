<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\AddressAction;
use App\Http\Controllers\Api\Traits\OrderAction;
use App\Models\GoodsCartsModel;
use App\Models\OrderGoodsModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use App\Models\UsersAddressModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use OrderAction, AddressAction;

    /**
     * 描述：订单详情
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function details(Request $request)
    {
        $u_id = $request->get('u_id');

        $id = $request->get('id');

        $OrderModel = new OrderModel();
        $UsersAddressModle = new UsersAddressModel();

        $orderData = $OrderModel->getFind($id, [
            'id', 'buy_id', 'order_amount', 'order_sn', 'buy_name', 'address_id', 'create_time'
        ]);

        if ($u_id != $orderData->buy_id) {
            return ajaxReturn(false, '不是当前用户订单', []);
        }

        $orderData = $this->orderGoodsData($orderData);

        $addressInfo = $UsersAddressModle->getFind($orderData->address_id);

        $orderData->sex = $this->reverseSex($addressInfo->sex);
        $orderData->tel = $addressInfo->phone;
        $orderData->address = $addressInfo->address;

        $orderData->create_time = date('Y-m-d H:i:s', $orderData->create_time);

        return ajaxReturn(true, 'success', $orderData);
    }

    /**
     * 描述：我的订单列表
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {
        $u_id = $request->get('u_id');
        $type = $request->get('type', 'all');

        $type = $this->parsType($type);

        $OrderModel = new OrderModel();

        $orderData = $OrderModel->getUserOrder($u_id, $type);

        foreach ($orderData as &$item) {
            $item = $this->orderGoodsData($item);
            $item->totalAmount = $item->order_amount;
            $item->_id = $item->id;

            unset($item->order_amount);
            unset($item->id);
        }

        return ajaxReturn(false, 'success', $orderData);
    }

    /**
     * 描述：确认订单信息
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function confirm(Request $request)
    {
        $dbs = $request->get('dbs');
        $u_id = $request->get('u_id');

        $address = $dbs['address_id'];
        $items = $dbs['items'];

        $UserModel = new UserModel();

        DB::beginTransaction();
        try {
            $orderData = [
                'buy_id' => $u_id, 'buy_name' => $UserModel->getUserName($u_id), 'create_time' => time(),
                'address_id' => $address, 'goods_amount' => $this->calculateGoodsAmount($items),
            ];

            //订单金额暂定为商品总金额
            $orderData['order_amount'] = $orderData['goods_amount'];
            $orderInfo = $this->createOrder($orderData); //创建订单主数据

            //组合订单商品数据
            $goodsData = $this->calculateGoodsData($items, $orderInfo->id);
            $this->createOrderGoods($goodsData); //创建订单商品数据

            //创建订单成功，删除该购物车的商品数据
            $this->clearCarts($items);

            //创建订单日志信息
            $this->createOrderLog($orderInfo->id, $u_id);

            DB::commit();
            return ajaxReturn(true, 'success',
                ['pay_sn' => $orderInfo->pay_sn, 'order_amount' => $orderInfo->order_amount]);
        } catch (\Exception $exception) {

            DB::rollback();
            return ajaxReturn(false, $exception->getMessage());
        }
    }

    /**
     * 描述：获取购物车商品数据
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function getGoodsData(Request $request)
    {
        $u_id = $request->get('u_id');

        $GoodsCartModel = new GoodsCartsModel();

        $data = $GoodsCartModel->getCarts($u_id);

        $allAmount = 0;
        $goods = [];

        foreach ($data as $key => &$item) {
            $allAmount += $item->totalAmount;

            $goods[$key] = [
                'goods' => ['_id' => $item->id, 'name' => $item->name],
                'total' => $item->total, 'totalAmount' => $item->totalAmount
            ];
        }

        $allData = [
            'all_amount' => $allAmount,
            'goods' => $goods
        ];

        return ajaxReturn(true, 'success', $allData);
    }

    /**
     * 描述：获取用户默认地址信息
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function defaultAddress(Request $request)
    {
        $u_id = $request->get('u_id');
        $UsersAddressModel = new UsersAddressModel();

        $defaultData = $UsersAddressModel->getUserDefault($u_id);

        if (!$defaultData) {
            return ajaxReturn(false, '该用户没有设置默认地址');
        }

        $defaultData->sex = $this->reverseSex($defaultData->sex);

        return ajaxReturn(true, 'success', $defaultData);
    }

}

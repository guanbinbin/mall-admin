<?php
/**
 * Created by PhpStorm.
 * User：Jack Yang
 *
 * Date: 2018/1/19
 * Time: 上午10:20
 */

namespace App\Http\Controllers\Api\Traits;

use App\Models\GoodsCartsModel;
use App\Models\OrderGoodsModel;
use App\Models\OrderLogModel;
use App\Models\OrderModel;

trait OrderAction
{
    //TODO 组合订单商品数据
    private function orderGoodsData($item)
    {
        $OrderGoodsModel = new OrderGoodsModel();
        $goods = $OrderGoodsModel->getOrderGoodsFiled($item->id);
        foreach ($goods as $key => $i) {
            $item->items = array_add($item->items, $key, [
                'goods' => ['name' => $i->goods_name],
                'meta' => [
                    'total' => $i->goods_num, 'totalAmount' => $i->goods_price
                ]
            ]);

        }

        return $item;
    }

    /**
     * 描述：解析类型参数
     * User：Jack Yang
     *
     * @param $type
     * @return bool|mixed
     */
    private function parsType($type)
    {
        $OrderModel = new OrderModel();

        switch ($type) {
            case 'all':
                $type = false;
                break;
            case 'submitted';
                $type = $OrderModel::ORDER_NORMAL;
                break;
            case 'confirmed':
                $type = $OrderModel::ORDER_ALREADY_PAY;
                break;
            case 'delivery':
                $type = $OrderModel::ORDER_DELIVERY;
                break;
            case 'thegoods':
                $type = $OrderModel::ORDER_GET;
                break;
            case 'finished':
                $type = $OrderModel::ORDER_OVER;
                break;
            case 'canceled':
                $type = $OrderModel::ORDER_CANCEL;
                break;
            default:
                $type = false;
        }

        return $type;
    }

    /**
     * 描述：创建订单日志信息
     * User：Jack Yang
     *
     * @param $order_id
     * @param $u_id
     */
    private function createOrderLog($order_id, $u_id)
    {
        $OrderLogModel = new OrderLogModel();

        $map = [
            'order_id' => $order_id, 'action_time' => time(), 'type' => $OrderLogModel::TYPE_USERS,
            'u_id' => $u_id, 'msg' => '创建订单'
        ];

        $result = $OrderLogModel->createOrderLog($map);

        throw_unless($result, \Exception::class,
            '创建订单日志失败');
    }

    /**
     * 描述：清理购物车
     * User：Jack Yang
     *
     * @param $items
     */
    private function clearCarts($items)
    {
        $GoodsCartsModel = new GoodsCartsModel();

        foreach ($items as $goodsItem) {
            $int = $GoodsCartsModel->saveData($goodsItem['id'],
                ['status' => $GoodsCartsModel::STATUS_DELETE]);

            throw_unless($int, \Exception::class, '清理购物车失败');
        }
    }

    /**
     * 描述：创建订单商品数据
     * User：Jack Yang
     *
     * @param $data
     * @return mixed
     */
    private function createOrderGoods($data)
    {
        $OrderGoodsModel = new OrderGoodsModel();

        $result = $OrderGoodsModel->insert($data);
        throw_unless($result, \Exception::class, '创建订单商品数据失败');

        return $result;
    }

    /**
     * 描述：组合订单商品数据
     * User：Jack Yang
     *
     * @param $items
     * @param $order_id
     * @return array
     */
    private function calculateGoodsData($items, $order_id)
    {
        $GoodsCartsModel = new GoodsCartsModel();

        $goodsData = [];

        foreach ($items as &$goodsItem) {
            $goods = $GoodsCartsModel->getInfo($goodsItem['id'],
                ['g_id', 'name', 'price', 'total', 'thumb_url']);

            $goodsData[] = [
                'order_id' => $order_id, 'goods_id' => $goods->g_id, 'goods_name' => $goods->name,
                'goods_price' => $goods->price, 'goods_num' => $goods->total, 'goods_image' => $goods->thumb_url,
                'created_at' => time(), 'updated_at' => time()
            ];
        }

        return (array)$goodsData;
    }

    /**
     * 描述：计算订单中商品总金额
     * User：Jack Yang
     *
     * @param $items
     * @return int
     */
    protected function calculateGoodsAmount($items)
    {
        $GoodsCartsModel = new GoodsCartsModel();
        $goods_amount = 0;
        foreach ($items as $goodsItem) {
            $price = $GoodsCartsModel->getInfo($goodsItem['id'], ['price', 'total']);
            $goods_amount += $price['price'] * $price['total'];
        }

        return $goods_amount;
    }

    /**
     * 描述：创建主订单数据
     * User：Jack Yang
     *
     * @param $orderData
     * @return mixed
     */
    private function createOrder($orderData)
    {
        $OrderModel = new OrderModel();

        $data = $OrderModel->createData($orderData);

        throw_unless($data, \Exception::class, '主订单创建失败');

        return $data;
    }
}
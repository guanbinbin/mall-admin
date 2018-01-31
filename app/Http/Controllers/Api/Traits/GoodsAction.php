<?php

namespace App\Http\Controllers\Api\Traits;

use App\Models\GoodsCartsModel;
use App\Models\GoodsModel;
use Illuminate\Http\Request;

trait GoodsAction
{
    /**
     * 描述：执行加入购物车
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addShopCart(Request $request)
    {
        $goodsId = $request->get('id');

        $GoodsModel = new GoodsModel();
        $GoodsCartsModel = new GoodsCartsModel();

        $status = $GoodsCartsModel->cheackGoods($goodsId, $request->get('u_id'));
        if (!$status) {
            return ajaxReturn(true, '已在购物车');
        }

        $goodsInfo = $GoodsModel->findData($goodsId);

        $result = $GoodsCartsModel->createData([
            'u_id' => $request->get('u_id'), 'name' => $goodsInfo->name, 'g_id' => $goodsId,
            'price' => $goodsInfo->price, 'total' => 1, 'thumb_url' => $goodsInfo->image,
            'amount' => $goodsInfo->price, 'totalAmount' => $goodsInfo->price
        ]);

        if (!$result) {
            return ajaxReturn(false, '加入购物车失败');
        }

        return ajaxReturn(true, '加入购物车成功');
    }
}
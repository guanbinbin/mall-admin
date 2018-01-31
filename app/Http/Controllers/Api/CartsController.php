<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\CartsAction;
use App\Models\GoodsCartsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartsController extends Controller
{
    use CartsAction;

    /**
     * 描述：获取购物车数据
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndex(Request $request)
    {
        $u_id = $request->get('u_id');

        $GoodsCartsModel = new GoodsCartsModel();
        $data = $GoodsCartsModel->getCarts($u_id);

        $result = [];
        foreach ($data as $key => &$item) {
            $item->thumb_url = getCover($item->thumb_url)->path;

            $result[$key]['goods'] = $item;
        }

        return ajaxReturn(true, 'success', $result);
    }
}

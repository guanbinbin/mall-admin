<?php

namespace App\Http\Controllers\Api;

use App\Models\GoodsClassModel;
use App\Models\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ClassifyController extends Controller
{
    /**
     * 描述：获取分类页面数据
     * User：Jack Yang
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndex()
    {
        $classData = Cache::remember('GET_CLASS_ALL', 60 * 24 * 7, function () {
            $GoodsClassModel = new GoodsClassModel();
            return $GoodsClassModel->getAllClass();
        });

        $GoodsModel = new GoodsModel();
        $goodsData = $GoodsModel->getClassGoods($classData->first()->id);

        foreach ($goodsData as $item) {
            $item->image = getCover($item->image)->path;
        }

        return ajaxReturn(true, 'success', ['class' => $classData, 'goods' => $goodsData]);
    }

    /**
     * 描述：获取具体分类下的商品数据
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGoods(Request $request)
    {
        $id = $request->get('id');

        $GoodsModel = new GoodsModel();

        $goodsData = $GoodsModel->getClassGoods($id);

        foreach ($goodsData as $item) {
            $item->image = getCover($item->image)->path;
        }

        return ajaxReturn(true, 'success', $goodsData);
    }
}

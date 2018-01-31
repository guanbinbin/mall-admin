<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\GoodsAction;
use App\Models\GoodsImagesModel;
use App\Models\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    use GoodsAction;

    /**
     * 描述：商品详情
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetail(Request $request)
    {
        $id = $request->get('id');

        $GoodsModel = new GoodsModel();
        $GoodsImagesModel = new GoodsImagesModel();

        $data = $GoodsModel->findData($id);
        $images = $GoodsImagesModel->getGoodsImage($id);

        foreach ($images as &$item){
            $item->path = getCover($item->picture_id)->path;
            unset($item->id);
            unset($item->picture_id);
        }

        $data->remark = $data->detail;
        $data->images = $images->toArray();
        $data->total = count($images->toArray());

        unset($data->class_id);
        unset($data->stock);
        unset($data->detail);
        unset($data->image);

        return ajaxReturn('success', 'ok', $data);
    }
}

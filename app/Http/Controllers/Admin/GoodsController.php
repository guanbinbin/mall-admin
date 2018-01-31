<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\GoodsAction;
use App\Models\GoodsClassModel;
use App\Models\GoodsImagesModel;
use App\Models\GoodsModel;
use Illuminate\Http\Request;

class GoodsController extends CommonController
{
    use GoodsAction;

    /**
     * 描述：商品管理
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $input = [
            'name' => $request->get('name', null),
            'id' => $request->get('id', null),
            'goods_status' => $request->get('goods_status', null),
        ];

        $GoodsModel = new GoodsModel();
        $data = $GoodsModel->getGoodsList($input);

        return view('admin.goods.index', ['data' => $data, 'input' => $input]);
    }

    /**
     * 描述：新增商品信息
     * User：Jack Yang
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $GoodsClassModel = new GoodsClassModel();

        $classData = $GoodsClassModel->getAllClass();
        return view('admin.goods.create', ['class' => $classData]);
    }

    /**
     * 描述：商品编辑页面
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');

        $GoodsModel = new GoodsModel();
        $GoodsClassModel = new GoodsClassModel();
        $GoodsImageModel = new GoodsImagesModel();

        $data = $GoodsModel->findData($id);
        $classData = $GoodsClassModel->getAllClass();
        $imageData = $GoodsImageModel->getGoodsImage($id)->toArray();

        return view('admin.goods.edit', [
            'data' => $data, 'class' => $classData, 'image' => $imageData]);


    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\GoodsClassAction;
use App\Models\GoodsClassModel;
use Illuminate\Http\Request;

class GoodsClassController extends CommonController
{

    use GoodsClassAction;

    public function index(Request $request)
    {
        $name = $request->get('name');

        $GoodsClassModel = new GoodsClassModel();

        $data = $GoodsClassModel->getGoodsClass($name);

        return view('admin.goodsClass.index', ['data' => $data, 'name' => $name]);
    }
}

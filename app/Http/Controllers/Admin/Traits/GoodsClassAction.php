<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\GoodsClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait GoodsClassAction
{
    /**
     * 描述：添加商品分类
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $name = $request->get('name');

        if (!strlen($name)) {
            flsh('请输入商品分类名称', 'error');
            return back();
        }

        $GoodsClassModel = new GoodsClassModel();

        $status = $GoodsClassModel->checkName($name);
        if (!$status) {
            flsh('该商品分类已存在', 'error');
            return back();
        }

        DB::beginTransaction();
        try {
            $GoodsClassModel->creataData(['name' => $name]);

            DB::commit();

            flsh('操作成功');
            return back();
        } catch (\Exception $exception) {
            DB::rollback();

            flsh($exception->getMessage(), 'error');
            return back();

        }
    }

    /**
     * 描述：删除商品分类
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        $GoodsClassModel = new GoodsClassModel();

        $GoodsClassModel->saveData($id, ['status' => $GoodsClassModel::STATUS_DELETE]);

        return ajaxReturn('success','操作成功');
    }
}
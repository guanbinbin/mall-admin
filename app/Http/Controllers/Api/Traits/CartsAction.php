<?php
/**
 * Created by PhpStorm.
 * User：Jack Yang
 *
 * Date: 2018/1/18
 * Time: 下午2:44
 */

namespace App\Http\Controllers\Api\Traits;

use App\Models\GoodsCartsModel;
use Illuminate\Http\Request;

trait CartsAction
{
    /**
     * 描述：购物车数量改变
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalSave(Request $request)
    {
        $id = $request->get('id');
        $params = $request->get('params');

        $GoodsCartsModel = new GoodsCartsModel();

        $cartsInfo = $GoodsCartsModel->getInfo($id, ['amount']);
        $params['totalAmount'] = $params['total'] * $cartsInfo['amount'];

        $int = $GoodsCartsModel->saveData($id, $params);

        if (!$int) {
            return ajaxReturn(false, '编辑失败');
        }

        return ajaxReturn(true, '编辑成功');
    }

    /**
     * 描述：删除购物车数据
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delCartsGoods(Request $request)
    {
        $id = $request->get('id');

        $GoodsCartsModel = new GoodsCartsModel();

        $int = $GoodsCartsModel->saveData($id, ['status' => $GoodsCartsModel::STATUS_DELETE]);

        if (!$int) {
            return ajaxReturn(false, '删除失败');
        }

        return ajaxReturn(true, '删除成功');
    }

    /**
     * 描述：清空购物车
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCartsGoods(Request $request)
    {
        $u_id = $request->get('u_id');

        $GoodsCartsModel = new GoodsCartsModel();
        $result = $GoodsCartsModel->clearData($u_id);

        if (!$result) {
            return ajaxReturn(false, '清空失败');
        }

        return ajaxReturn(true, '清空成功');
    }

    //TODO 提交计算
    public function confirm(Request $request)
    {

    }
}
<?php
/**
 * Created by PhpStorm.
 * User：Jack Yang
 *
 * Date: 2017/12/20
 * Time: 下午2:57
 */

namespace App\Http\Controllers\Admin\Traits;

use App\Exceptions\ValidatorException;
use App\Models\GoodsImagesModel;
use App\Models\GoodsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait GoodsAction
{
    /**
     * 描述：添加商品基本信息
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createGoods(Request $request)
    {
        $data = $request->only(['name', 'price', 'class_id', 'image', 'detail', 'stock']);

        $this->makeVali($data, [
            'name' => 'required', 'price' => 'required', 'class_id' => 'required',
            'image' => 'image', 'detail' => 'required', 'stock' => 'required'
        ], [
            'name.required' => '请填写商品名称', 'price.required' => '请填写商品价格',
            'class_id.required' => '请选择商品类型', 'image.image' => '请选择商品主图',
            'detail.required' => '商品说明', 'stock.required' => '请填写商品库存'
        ]);

        $imageId = uploadImage($request->file('image'));
        throw_unless($imageId,ValidatorException::class,'图片上传失败');

        $imageDeail = [];
        foreach ($request->file('imageDeail') as $file) {
            $dealiId = uploadImage($file);
            throw_unless($dealiId,ValidatorException::class,'更多图片上传失败');

            $imageDeail[] = $dealiId;
        }

        $data['image'] = $imageId;

        $GoodsModel = new GoodsModel();
        $GoodsImagesModel = new GoodsImagesModel();

        DB::beginTransaction();
        try {
            $goodsData = $GoodsModel->createData($data);

            throw_unless($goodsData, \Exception::class, '上传基本信息失败');

            foreach ($imageDeail as $key => $value) {
                $GoodsImagesModel->createData([
                    'goods_id' => $goodsData->id, 'picture_id' => $value,
                    'sort' => ($key + 1)
                ]);
            }
            DB::commit();

            return redirect()->route('admin.goods.base.index');
        } catch (\Exception $exception) {
            DB::rollback();

            flsh($exception->getMessage(), 'error');
            return back();
        }
    }

    /**
     * 描述：商品上架
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upAction(Request $request)
    {
        $id = $request->get('id');

        $this->savaGoodsStatus($id, GoodsModel::GOODS_UP);

        return ajaxReturn('success', '上架成功');
    }

    /**
     * 描述：商品下架
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function downAction(Request $request)
    {
        $id = $request->get('id');

        $this->savaGoodsStatus($id, GoodsModel::GOODS_BOTTOM);

        return ajaxReturn('success', '下架成功');
    }

    /**
     * 描述：批量上下架
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function multiAction(Request $request)
    {
        $ids = $request->get('ids', null);
        $action = $request->get('action', null);

        foreach ($ids as $item) {
            $this->savaGoodsStatus($item, $action);
        }

        return ajaxReturn('success', '批量操作成功');
    }

    /**
     * 描述：上下架操作
     * User：Jack Yang
     *
     * @param $id
     * @param $goods_status
     * @return mixed
     */
    private function savaGoodsStatus($id, $goods_status)
    {
        $GoodsModel = new GoodsModel();

        $int = $GoodsModel->savaData($id, ['goods_status' => $goods_status]);
        throw_unless($int, ValidatorException::class, '更新数据失败');

        return $int;
    }

    /**
     * 描述：批量删除
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $ids = $request->get('ids', null);

        $GoodsModel = new GoodsModel();

        foreach ($ids as $item) {
            $GoodsModel->savaData($item, ['status' => $GoodsModel::STATUS_DELETE]);
        }

        return ajaxReturn('success', '删除成功');
    }

    /**
     * 描述：编辑商品操作
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');
        $input = $request->only(['name', 'class_id', 'price', 'detail', 'stock']);

        $this->makeVali($input, [
            'name' => 'required', 'price' => 'required', 'class_id' => 'required',
            'detail' => 'required', 'stock' => 'required'
        ], [
            'name.required' => '请填写商品名称', 'price.required' => '请填写商品价格',
            'class_id.required' => '请选择商品类型', 'detail.required' => '商品说明',
            'stock.required' => '请填写商品库存'
        ]);

        $GoodsModel = new GoodsModel();
        $GoodsImagesModel = new GoodsImagesModel();

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $imageId = uploadImage($request->file('image'));
                throw_unless($imageId, \Exception::class, '商品主图更新失败');
                $input['image'] = $imageId;
            }
            $int = $GoodsModel->savaData($id, $input);
            throw_unless($int, \Exception::class, '更新基本信息失败');

            if ($request->hasFile('imageDeail')) {
                foreach ($request->file('imageDeail') as $key => $item) {
                    $where = [
                        'sort' => ($key + 1), 'goods_id' => $id, 'status' => $GoodsImagesModel::STATUS_NORMAL
                    ];
                    $result = $GoodsImagesModel->where($where)->first();
                    if (!$result) {
                        $detailId = uploadImage($item);
                        throw_unless($detailId, \Exception::class, '更新基本信息失败');
                        $map = [
                            'sort' => ($key + 1), 'goods_id' => $id, 'picture_id' => $detailId
                        ];
                        $GoodsImagesModel->createData($map);
                    } else {
                        $detailId = uploadImage($item);
                        throw_unless($detailId, \Exception::class, '更新基本信息失败');

                        $map = [
                            'picture_id' => $detailId
                        ];

                        $int = $GoodsImagesModel->saveData($result->id, $map);
                        throw_unless($int, \Exception::class, '替换图片失败,请重试');
                    }
                }
            }

            DB::commit();

            flsh('操作成功', 'success');
            return redirect()->route('admin.goods.base.edit', ['id' => $id]);

        } catch (\Exception $exception) {
            DB::rollback();

            flsh($exception->getMessage(), 'error');
            return redirect()->route('admin.goods.base.edit', ['id' => $id]);

        }
    }
}
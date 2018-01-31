<?php
/**
 * Created by PhpStorm.
 * User：Jack Yang
 *
 * Date: 2017/12/25
 * Time: 上午10:33
 */

namespace App\Http\Controllers\Admin\Traits;


use App\Exceptions\ValidatorException;
use App\Models\BannerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait BannerAction
{
    /**
     * 描述：新增轮播数据
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $input = $request->only(['title', 'type', 'ex_id']);

        $this->makeVali($input, [
            'title' => 'required', 'type' => 'required', 'ex_id' => 'required|integer'
        ], [
            'title.required' => '请填写标题', 'type.required' => '请选中类型',
            'ex_id.integer' => '外链编号必须为数值', 'ex_id.required' => '请填写外链编号',
        ]);

        throw_unless($request->hasFile('image'),
            ValidatorException::class, '请选中图片上传');

        $input['image'] = $image = uploadImage($request->file('image'));

        throw_unless($image, ValidatorException::class, '上传图片失败');

        $BannerModel = new BannerModel();

        $BannerModel->createData($input);

        flsh('添加成功');

        return back();
    }

    /**
     * 描述：删除轮播图
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $BannerModel = new BannerModel();

        DB::beginTransaction();
        try {
            $BannerModel->savaData($request->get('id'), ['status' => $BannerModel::STATUS_DELETE]);
            DB::commit();

            return ajaxReturn('success', '操作成功');
        } catch (\Exception $exception) {

            DB::rollback();

            return ajaxReturn('error', $exception->getMessage());
        }
    }

    /**
     * 描述：编辑操作
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $input = $request->only(['title', 'type', 'ex_id']);

        $this->makeVali($input, [
            'title' => 'required', 'type' => 'required', 'ex_id' => 'required|integer'
        ], [
            'title.required' => '请填写标题', 'type.required' => '请选中类型',
            'ex_id.integer' => '外链编号必须为数值', 'ex_id.required' => '请填写外链编号',
        ]);

        if ($request->hasFile('image')) {
            $input['image'] = $image = uploadImage($request->file('image'));
            throw_unless($image, ValidatorException::class, '图片上传失败');
        }

        $BannerModel = new BannerModel();

        DB::beginTransaction();
        try {
            $BannerModel->savaData($request->get('id'), $input);

            DB::commit();

            flsh('操作成功');
            return back();
        } catch (\Exception $exception) {

            DB::rollback();
            flsh($exception->getMessage(), 'error');
            return back();
        }

    }
}
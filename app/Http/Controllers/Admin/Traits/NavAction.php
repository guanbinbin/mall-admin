<?php
/**
 * Created by PhpStorm.
 * User：Jack Yang
 *
 * Date: 2017/12/26
 * Time: 上午9:20
 */

namespace App\Http\Controllers\Admin\Traits;


use App\Exceptions\ValidatorException;
use App\Models\NavigationModel;
use Illuminate\Http\Request;

trait NavAction
{
    /**
     * 描述：添加首页导航数据操作
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAction(Request $request)
    {
        $title = $request->get('title');

        $NavigationModel = new NavigationModel();

        $result = $NavigationModel->createData(['title' => $title]);

        throw_unless($result, ValidatorException::class, '首页导航数据创建失败');

        return ajaxReturn('success', '首页导航数据创建成功');
    }

    /**
     * 描述：更新数据
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        $input = $request->only(['title', 'goods']);

        $this->makeVali($input, [
            'title' => 'required'
        ], [
            'title.required' => '首页导航标题不能为空'
        ]);

        $NavigationModel = new NavigationModel();

        $NavigationModel->saveData($id, $input);

        flsh('更新成功');
        return back();
    }

    /**
     * 描述：删除操作
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        $NavigationModel = new NavigationModel();

        $NavigationModel->saveData($id, ['status' => $NavigationModel::STATUS_DELETE]);


        return ajaxReturn('success','操作成功');
    }
}
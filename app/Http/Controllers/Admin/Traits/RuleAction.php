<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\AuthRuleModel;
use App\Models\RoleRuleModel;
use App\Models\UserRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait RuleAction
{
    /**
     * 描述：创建新的菜单信息
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAction(Request $request)
    {
        $input = $request->only(['parent_id', 'name', 'icon', 'route', 'role']);


        $this->makeVali($input, [
            'parent_id' => 'required|integer', 'name' => 'required|string',
            'icon' => 'string|nullable', 'route' => 'string'
        ], [
            'parent_id.required' => '请选择父级菜单', 'parent_id.integer' => '父级菜单数据类型错误',
            'name.required' => '请正确填写菜单标题', 'name.string' => '菜单标题类型错误',
            'icon.string' => '请正确填写菜单类型', 'route.string' => '请正确填写菜单路由'
        ]);

        $AuthRuleModel = new AuthRuleModel();
        $RoleRuleModel = new RoleRuleModel();


        DB::beginTransaction();
        try {
            $ruleData = $AuthRuleModel->createData([
                'name' => $request->get('name'),
                'parent_id' => $request->get('parent_id'),
                'icon' => $request->get('icon'),
                'route' => $request->get('route')
            ]);

            if (count($request->get('role'))) {
                foreach ($request->get('role') as $item) {
                    $RoleRuleModel->createData([
                        'role_id' => $item, 'permissions_id' => $ruleData->id
                    ]);

                    $this->clearSidebar($item); //清理该角色的sidebar数据
                }
            }

            DB::commit();

            return ajaxReturn('success', '操作成功');
        } catch (\Exception $exception) {
            DB::rollback();

            return ajaxReturn('error', $exception->getMessage());
        }
    }

    /**
     * 描述：清理权限的sidebar数据
     * User：Jack Yang
     *
     * @param $role_id
     */
    private function clearSidebar($role_id)
    {
        $UserRoleModel = new UserRoleModel();

        $userData = $UserRoleModel->where(['role_id' => $role_id, 'status' => $UserRoleModel::STATUS_NORMAL])
            ->get(['user_id'])->toArray();

        foreach ($userData as $i) {
            Cache::forget('AUTH_ROLE_PERMISSIONS_USERS_ID' . $i['user_id']);
        }
    }
}
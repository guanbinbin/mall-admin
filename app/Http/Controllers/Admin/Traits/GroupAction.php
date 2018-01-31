<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\AuthRoleModel;
use App\Models\RoleRuleModel;
use App\Models\UserRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait GroupAction
{
    /**
     * 描述：新增角色信息
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAction(Request $request)
    {
        $name = $request->get('name');

        if (!strlen($name)) {
            return ajaxReturn('error', '请填写角色名称');
        }

        $AuthGroupModel = new AuthRoleModel();

        DB::beginTransaction();
        try {
            $satus = $AuthGroupModel->checkName($name);
            throw_unless($satus, \Exception::class, '该角色名称已被使用');

            $data = ['name' => $name];
            $AuthGroupModel->creataData($data);

            DB::commit();
            return ajaxReturn('success', '新增成功');

        } catch (\Exception $exception) {
            DB::rollback();

            return ajaxReturn('error', $exception->getMessage());
        }
    }

    /**
     * 描述：删除角色信息
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $name = $request->get('name');

        $AuthGroupModel = new AuthRoleModel();

        $int = $AuthGroupModel->deleteData($id);

        if (!$int) {
            return ajaxReturn('error', '删除失败');
        }

        Cache::forget($name);
        return ajaxReturn('success', '删除成功');
    }

    /**
     * 描述：编辑角色的权限
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ruleEdit(Request $request)
    {
        $id = $request->get('id');
        $rule = $request->get('rule');

        $RoleRuleModel = new RoleRuleModel();

        DB::beginTransaction();
        try {
            $RoleRuleModel->closeRoleRule($id);

            foreach ($rule as $key => $value) {
                $RoleRuleModel->createData([
                    'role_id' => $id, 'permissions_id' => $value
                ]);
            }

            DB::commit();

            Cache::forget('GET_ROLE_TO_RULE_ROLE_ID_' . $id);

            $UserRoleModel = new UserRoleModel();
            $user = $UserRoleModel->where(['role_id' => $id, 'status' => $UserRoleModel::STATUS_NORMAL])
                ->get(['user_id'])->toArray();
            foreach ($user as $item) {
                Cache::forget('AUTH_ROLE_PERMISSIONS_USERS_ID' . $item['user_id']); //删除用户sidebar缓存数据
            }

            return ajaxReturn('success', '操作成功');
        } catch (\Exception $exception) {
            DB::rollback();

            return ajaxReturn('error', $exception->getMessage());
        }


    }


}
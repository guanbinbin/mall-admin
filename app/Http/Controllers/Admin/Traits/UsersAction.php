<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\UserRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait UsersAction
{
    /**
     * 描述：更新用户的角色信息
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');
        $rule = $request->get('rule');

        if (!count($request->get('rule'))) {
            return ajaxReturn('success', '操作成功');
        }

        $UserRoleModel = new UserRoleModel();

        DB::beginTransaction();
        try {
            //关闭该用户以往的角色信息
            $UserRoleModel->closeRole($id);
            //开启选中的角色信息
            foreach ($rule as $key => $item) {
                $UserRoleModel->createData([
                    'user_id' => $id, 'role_id' => $item
                ]);
            }
            DB::commit();
            Cache::forget('USERS_ROLE_USER_ID' . $id); //删除该用户的角色节点缓存
            Cache::forget('AUTH_ROLE_PERMISSIONS_USERS_ID' . $id); //删除用户sidebar缓存数据

            return ajaxReturn('success', '操作成功');
        } catch (\Exception $exception) {
            DB::rollback();

            return ajaxReturn('error', $exception->getMessage());
        }
    }
}
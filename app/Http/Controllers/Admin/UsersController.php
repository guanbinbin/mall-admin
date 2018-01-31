<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\UsersAction;
use App\Models\AuthRoleModel;
use App\Models\UserModel;
use App\Models\UserRoleModel;
use Illuminate\Http\Request;

class UsersController extends CommonController
{
    use UsersAction;

    /**
     * 描述：管理员组信息
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $input = $request->only(['id', 'name', 'email']);

        $UserModel = new UserModel();

        $data = $UserModel->getUsers($input);

        return view('admin.users.index', ['data' => $data, 'input' => $input]);
    }

    /**
     * 描述：管理员详情
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');

        $UserModel = new UserModel();
        $AuthRoleModel = new AuthRoleModel();
        $UserRoleModel = new UserRoleModel();

        $data = $UserModel->find($id);
        $roleData = $AuthRoleModel->getNameData();
        $UserToRole = $UserRoleModel->getUsersRole($id)->toArray();

        return view('admin.users.user', [
            'data' => $data, 'role' => $roleData, 'id' => $id,
            'UserToRole' => array_column($UserToRole, 'role_id')]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserModel;
use Illuminate\Http\Request;

class MembersController extends CommonController
{
    /**
     * 描述：用户管理信息
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function memberIndex(Request $request)
    {
        $input = $request->only(['id', 'name', 'email']);

        $UserModel = new UserModel();

        $data = $UserModel->getMembers($input);

        return view('admin.member.index', ['data' => $data, 'input' => $input]);
    }

    //TODO 用户详情
    public function memberEdit()
    {
        return view('admin.member.member');
    }
}

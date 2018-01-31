<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\GroupAction;
use App\Models\AuthRoleModel;
use App\Models\AuthRuleModel;
use App\Models\RoleRuleModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends CommonController
{
    use GroupAction;

    /**
     * 描述：角色列表
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $serch = $request->get('serch', null);

        $AuthRoleModel = new AuthRoleModel();

        $data = $AuthRoleModel->getRoleList($serch);

        return view('admin.group.index', ['data' => $data, 'serch' => $serch]);
    }

    /**
     * 描述：角色详情
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function infoAction(Request $request)
    {
        $id = $request->get('id');

        $RoleRuleModel = new RoleRuleModel();
        $AuthRuleModel = new AuthRuleModel();

        $allRule = $AuthRuleModel->getAllRule();
        $roleToRuleData = $RoleRuleModel->getRoleToRule($id)->toArray();

        return view('admin.group.info', ['id' => $id,
            'allRule' => $allRule, 'roleTorule' => array_column($roleToRuleData, 'permissions_id')]);
    }
}

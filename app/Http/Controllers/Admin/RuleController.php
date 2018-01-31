<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\RuleAction;
use App\Models\AuthRoleModel;
use App\Models\AuthRuleModel;

class RuleController extends CommonController
{
    use RuleAction;

    public function index()
    {
        $AuthRoleModel = new AuthRoleModel();
        $AuthRuleModel = new AuthRuleModel();

        $roleName = $AuthRoleModel->getNameData();
        $ruleName = $AuthRuleModel->getRuleData();

        $allRule = $AuthRuleModel->getAllRule();

        return view('admin.rule.index', [
            'role' => $roleName, 'rule' => $ruleName, 'allRule' => $allRule]);
    }
}

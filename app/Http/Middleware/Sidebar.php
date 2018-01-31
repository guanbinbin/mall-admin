<?php

namespace App\Http\Middleware;

use App\Models\AuthRuleModel;
use App\Models\RoleRuleModel;
use App\Models\UserRoleModel;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class Sidebar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = Auth::id();
        $UserRoleModel = new UserRoleModel();
        $RoleRuleModel = new RoleRuleModel();
        $roleData = $UserRoleModel->getUsersRole($id)->toArray();
        $roleArray = array_column($roleData, 'role_id'); //角色数组

        $ruleData = $RoleRuleModel->where('status', $RoleRuleModel::STATUS_NORMAL)
            ->whereIn('role_id', $roleArray)->get(['permissions_id'])->toArray();
        $ruleArray = array_unique(array_column($ruleData, 'permissions_id'));

        $rule = $this->getRule($id, $ruleArray);

        $status = $this->checkRule($request, $rule);//判断是否有权限访问

        if (!$status) {
            return redirect()->route('home');
        }

        view()->composer('admin.layouts.sidebar', function ($view) use ($rule) {
            $view->with('rule', $rule);
        });

        return $next($request);
    }

    /**
     * 描述：判断当前路由是否
     * User：Jack Yang
     *
     * @param $request
     * @param $rule
     * @return bool
     */
    private function checkRule($request, $rule)
    {
        $route = Route::currentRouteName();

        if ($route == 'home') {
            return true;
        }

        $ruleItem = [];
        foreach ($rule as $key => $item) {
            if (isset($item['son'])) {
                foreach ($item['son'] as $i) {
                    $ruleItem[] = $this->analysis($i['route']);
                }
            }
        }

        $status = false;

        foreach ($ruleItem as $item) {
            if ($request->route()->named($item)) {
                $status = true;
            }
        }

        if (!$status) {
            return false;
        }

        return true;
    }

    /**
     * 描述：获取该管理员的Rule数据
     * User：Jack Yang
     *
     * @param $id
     * @param $ruleArray
     * @return mixed
     */
    private function getRule($id, $ruleArray)
    {
//        $rule = Cache::remember('AUTH_ROLE_PERMISSIONS_USERS_ID' . $id, 60 * 24 * 7, function () use ($ruleArray) {
        $AuthRuleModel = new AuthRuleModel();

        $rule = [];

        foreach ($ruleArray as $key => $item) {
            $inside = $AuthRuleModel
                ->where([
                    'id' => $item, 'parent_id' => $AuthRuleModel::PARENT_ID_NORMAL,
                    'status' => $AuthRuleModel::STATUS_NORMAL
                ])
                ->first(['id', 'name', 'icon', 'route']);
            if ($inside) {
                unset($ruleArray[$key]);
                $rule[] = $inside->toArray();
            }
        }

        foreach ($rule as $key => &$item) {
            foreach ($ruleArray as $i) {
                $is = $AuthRuleModel
                    ->where(['id' => $i, 'parent_id' => $item['id'], 'status' => $AuthRuleModel::STATUS_NORMAL])
                    ->first(['id', 'name', 'route']);

                if ($is) {
                    $item['son'][] = $is->toArray();
                }
            }
        }

        return $rule;
//        });

//        return $rule;
    }

    /**
     * 描述：获取各权限的上级route
     * User：Jack Yang
     *
     * @param $string
     * @return string
     */
    private function analysis($string)
    {
        return substr($string, 0, strrpos($string, '.') + 1) . '*';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class RoleRuleModel extends Model
{
    protected $table = 'auth_role_permissions';
    protected $primaryKey = 'id';

    protected $guarded = [];

    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    /**
     * 描述：设定为时间戳格式
     * User：Jack Yang
     *
     * @return string
     */
    protected function getDateFormat()
    {
        return 'U';
    }

    /**
     * 描述：新增角色节点数据
     * User：Jack Yang
     *
     * @param $data
     * @return mixed
     */
    public function createData($data)
    {
        $data = $this->create($data);

        throw_unless($data, \Exception::class, '新增角色菜单节点失败');

        return $data;
    }

    /**
     * 描述：获取角色权限节点数据
     * User：Jack Yang
     *
     * @param $id
     * @return mixed
     */
    public function getRoleToRule($id)
    {
        $data = Cache::remember('GET_ROLE_TO_RULE_ROLE_ID_' . $id, 60 * 24 * 7, function () use ($id) {
            return $this->where(['status' => self::STATUS_NORMAL, 'role_id' => $id])->get(['permissions_id']);
        });

        return $data;
    }

    /**
     * 描述：关闭该角色所有权限
     * User：Jack Yang
     *
     * @param $id
     * @return mixed
     */
    public function closeRoleRule($id)
    {
        $int = $this->where(['status' => self::STATUS_NORMAL, 'role_id' => $id])
            ->update(['status' => self::STATUS_DELETE]);

        throw_unless($int, \Exception::class, '操作失败');

        return $int;
    }
}

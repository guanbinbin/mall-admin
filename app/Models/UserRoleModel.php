<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class UserRoleModel extends Model
{
    protected $table = 'auth_user_role';
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
     * 描述：获取单个管理员的角色节点数据流
     * User：Jack Yang
     *
     * @param $userId
     * @return mixed
     */
    public function getUsersRole($userId)
    {
        return $this->where(['status' => self::STATUS_NORMAL, 'user_id' => $userId])->get(['role_id']);
    }

    /**
     * 描述：关闭该管理员所有的角色
     * User：Jack Yang
     *
     * @param $userId
     * @return mixed
     */
    public function closeRole($userId)
    {
        $int = $this->where(['status' => self::STATUS_NORMAL, 'user_id' => $userId])
            ->update(['status' => self::STATUS_DELETE]);

        throw_unless($int, \Exception::class, '操作失败');

        return $int;
    }

    /**
     * 描述：新建角色用户节点数据
     * User：Jack Yang
     *
     * @param $data
     * @return mixed
     */
    public function createData($data)
    {
        $data = $this->create($data);

        throw_unless($data, \Exception::class, '操作失败');

        return $data;
    }
}

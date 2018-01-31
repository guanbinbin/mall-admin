<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AuthRoleModel extends Model
{
    protected $table = 'auth_role';

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
     * 描述：判断角色名称是否可用
     * User：Jack Yang
     *
     * @param $name
     * @return mixed
     */
    public function checkName($name)
    {
        $status = Cache::remember($name, 30 * 24 * 60, function () use ($name) {
            $status = $this->where(['name' => $name, 'status' => self::STATUS_NORMAL])->first(['id']);
            if ($status) {
                return false;
            } else {
                return true;
            }
        });

        return $status;
    }

    /**
     * 描述：新增数据
     * User：Jack Yang
     *
     * @param $data
     * @return mixed
     */
    public function creataData($data)
    {
        $db = $this->create($data);

        throw_unless($db, \Exception::class, '新增数据失败');

        Cache::forget($data['name']);

        return $db;
    }

    /**
     * 描述：获取角色数据流
     * User：Jack Yang
     *
     * @param $name
     * @return mixed
     */
    public function getRoleList($name)
    {
        $data = $this->where('status', self::STATUS_NORMAL)
            ->when($name, function ($query) use ($name) {
                $query->where('name', "like", "%{$name}%");
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(8, ['id', 'name', 'updated_at']);

        return $data;
    }

    /**
     * 描述：删除角色
     * User：Jack Yang
     *
     * @param $id
     * @return mixed
     */
    public function deleteData($id)
    {
        $int = $this->where('id', $id)->update(['status' => self::STATUS_DELETE]);

        return $int;
    }

    /**
     * 描述：获取单个角色信息
     * User：Jack Yang
     *
     * @param $id
     * @return mixed
     */
    public function getRoleInfo($id)
    {
        $data = Cache::remember('GET_ROLE_INFO_ID_' . $id, 60 * 24 * 7, function () use ($id) {
            return $this->where('id', $id)->first(['id', 'name']);
        });

        return $data;
    }

    /**
     * 描述：获取角色信息数据流
     * User：Jack Yang
     *
     * @return mixed
     */
    public function getNameData()
    {
        $data = $this->where('status', self::STATUS_NORMAL)->get(['id', 'name']);

        return $data;
    }

}

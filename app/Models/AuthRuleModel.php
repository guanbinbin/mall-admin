<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthRuleModel extends Model
{
    protected $table = 'auth_permissions';
    protected $primaryKey = 'id';

    protected $guarded = [];

    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    const PARENT_ID_NORMAL = 0;

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
     * 描述：获取父级权限数据流
     * User：Jack Yang
     *
     * @return mixed
     */
    public function getRuleData()
    {
        $data = $this->where(['status' => self::STATUS_NORMAL, 'parent_id' => self::PARENT_ID_NORMAL])
            ->get(['id', 'name']);

        return $data;
    }

    /**
     * 描述：新增菜单数据
     * User：Jack Yang
     *
     * @param $data
     * @return mixed
     */
    public function createData($data)
    {
        $data = $this->create($data);
        throw_unless($data, \Exception::class, '新增菜单信息失败');
        return $data;
    }

    /**
     * 描述：获取所有的菜单
     * User：Jack Yang
     *
     * @return mixed
     */
    public function getAllRule()
    {
        $data = $this->where(['status' => self::STATUS_NORMAL, 'parent_id' => self::PARENT_ID_NORMAL])
            ->orderBy('id', 'asc')->get(['id', 'name', 'icon', 'route'])->toArray();

        foreach ($data as &$item) {
            $db = $this->where(['status' => self::STATUS_NORMAL, 'parent_id' => $item['id']])
                ->get(['id', 'name', 'route'])->toArray();

            if ($db) {
                $item['son'] = $db;
            }
        }

        return $data;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    const TYPE_MEMBER = 1;
    const TYPE_ADMINER = 2;

    protected $guarded = [];

    /**
     * 描述：获取用户数据信息
     * User：Jack Yang
     *
     * @param $where
     * @return mixed
     */
    public function getMembers($where)
    {
        $data = $this
            ->when(isset($where['id']), function ($query) use ($where) {
                $query->where('id', $where['id']);
            })
            ->when(isset($where['name']), function ($query) use ($where) {
                $query->where('name', 'like', "%{$where['name']}%");
            })
            ->when(isset($where['email']), function ($query) use ($where) {
                $query->where('email', 'like', "%{$where['email']}%");
            })
            ->where('type', self::TYPE_MEMBER)
            ->orderBy('updated_at', 'desc')
            ->paginate(8);

        return $data;
    }

    /**
     * 描述：获取管理员信息
     * User：Jack Yang
     *
     * @param $where
     * @return mixed
     */
    public function getUsers($where)
    {
        $data = $this->where('type', self::TYPE_ADMINER)
            ->when(isset($where['id']), function ($query) use ($where) {
                $query->where('id', $where['id']);
            })
            ->when(isset($where['name']), function ($query) use ($where) {
                $query->where('name', 'like', "%{$where['name']}%");
            })
            ->when(isset($where['email']), function ($query) use ($where) {
                $query->where('email', 'like', "%{$where['email']}%");
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(8);

        return $data;
    }

    /**
     * 描述：判断用户是否存在
     * User：Jack Yang
     *
     * @param $openid
     * @return bool
     */
    public function checkOpenid($openid)
    {
        $result = Cache::remember('OPENID_CHECK_' . $openid, 60 * 24 * 7, function () use ($openid) {
            return $this->where('openid', $openid)->first(['id']);
        });

        if ($result) {
            return $result->id;
        } else {
            return false;
        }
    }

    /**
     * 描述：新增数据
     * User：Jack Yang
     *
     * @param $data
     * @return mixed
     */
    public function createData($data)
    {
        $data = $this->create($data);

        return $data;
    }

    /**
     * 描述：获取用户名称
     * User：Jack Yang
     *
     * @param $u_id
     * @return mixed
     */
    public function getUserName($u_id)
    {
        $data = $this->where(['type' => self::TYPE_MEMBER, 'id' => $u_id])
            ->first(['name']);

        return $data['name'];
    }
}

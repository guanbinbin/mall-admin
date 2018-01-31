<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersAddressModel extends Model
{
    protected $table = 'users_address';
    protected $primaryKey = 'id';

    protected $guarded = [];

    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    const DEFAULT_NORMAL = 0; //非默认地址
    const DEFAULT_TRUE = 1; //默认地址

    protected function getDateFormat()
    {
        return 'U';
    }

    /**
     * 描述：获取用户收货地址信息
     * User：Jack Yang
     *
     * @param $u_id
     * @return mixed
     */
    public function lists($u_id)
    {
        $data = $this->where(['u_id' => $u_id, 'status' => self::STATUS_NORMAL])
            ->get(['id', 'sex', 'name', 'phone', 'address', 'default']);

        return $data;
    }

    /**
     * 描述：创建数据
     * User：Jack Yang
     *
     * @param $data
     * @return mixed
     */
    public function createData($data)
    {
        $result = $this->create($data);

        return $result;
    }

    /**
     * 描述：更新数据
     * User：Jack Yang
     *
     * @param $id
     * @param $updata
     * @return mixed
     */
    public function saveData($id, $updata)
    {
        $int = $this->where('id', $id)->update($updata);

        return $int;
    }

    /**
     * 描述：关闭所有的默认地址
     * User：Jack Yang
     *
     * @param $u_id
     * @return mixed
     */
    public function closeDefault($u_id)
    {
        $int = $this->where('u_id', $u_id)->update(['default' => self::DEFAULT_NORMAL]);

        return $int;
    }

    /**
     * 描述：获取单条收货地址信息
     * User：Jack Yang
     *
     * @param $id
     * @return mixed
     */
    public function getFind($id)
    {
        $data = $this->where(['id' => $id, 'status' => self::STATUS_NORMAL])
            ->first(['id', 'sex', 'name', 'phone', 'address', 'default']);

        return $data;
    }

    /**
     * 描述：获取该用户第一条收货地址数据
     * User：Jack Yang
     *
     * @param $u_id
     * @return mixed
     */
    public function getUserFind($u_id)
    {
        $data = $this->where(['u_id' => $u_id, 'status' => self::STATUS_NORMAL])
            ->first(['id']);

        return $data['id'];
    }

    /**
     * 描述：获取单个用户的默认地址信息
     * User：Jack Yang
     *
     * @param $u_id
     * @return mixed
     */
    public function getUserDefault($u_id)
    {
        $data = $this->where([
            'u_id' => $u_id, 'status' => self::STATUS_NORMAL, 'default' => self::DEFAULT_TRUE])
            ->first(['id', 'name', 'sex', 'phone', 'address']);

        return $data;
    }

}

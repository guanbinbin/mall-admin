<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsCartsModel extends Model
{
    protected $table = 'goods_cart';
    protected $primaryKey = 'id';
    protected $guarded = [];

    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    protected function getDateFormat()
    {
        return 'U';
    }

    /**
     * 描述：新增购物车数据
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
     * 描述：判断该商品是否已在购物车
     * User：Jack Yang
     *
     * @param $goodsId
     * @param $uid
     * @return bool
     */
    public function cheackGoods($goodsId, $uid)
    {
        $result = $this->where(['status' => self::STATUS_NORMAL, 'g_id' => $goodsId, 'u_id' => $uid])
            ->first();

        if ($result) {
            return false;
        }

        return true;
    }

    /**
     * 描述：获取购物车商品数据
     * User：Jack Yang
     *
     * @param $uid
     * @return mixed
     */
    public function getCarts($uid)
    {
        $data = $this->where(['status' => self::STATUS_NORMAL, 'u_id' => $uid])
            ->get(['id', 'g_id', 'name', 'price', 'total', 'thumb_url', 'amount', 'totalAmount']);

        return $data;
    }

    /**
     * 描述：更新数据
     * User：Jack Yang
     *
     * @param $id
     * @param $update
     * @return mixed
     */
    public function saveData($id, $update)
    {
        $int = $this->where('id', $id)->update($update);

        return $int;
    }

    /**
     * 描述：自定义获取单条购物车数据
     * User：Jack Yang
     *
     * @param $id
     * @param $map
     * @return mixed
     */
    public function getInfo($id, $map = ['*'])
    {
        $data = $this->where('id', $id)->first($map);

        return $data;
    }

    /**
     * 描述：清空购物车
     * User：Jack Yang
     *
     * @param $u_id
     * @return mixed
     */
    public function clearData($u_id)
    {
        $result = $this->where(['u_id' => $u_id, 'status' => self::STATUS_NORMAL])
            ->update(['status' => self::STATUS_DELETE]);

        return $result;
    }
}

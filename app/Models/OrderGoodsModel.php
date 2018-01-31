<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderGoodsModel extends Model
{
    protected $table = 'order_goods';
    protected $primaryKey = 'id';

    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    protected $guarded = [];

    protected $fillable = ['*'];

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
     * 描述：获取订单商品梗概
     * User：Jack Yang
     *
     * @param $order_id
     * @return mixed
     */
    public function getOrderGoodsFiled($order_id)
    {
        $data = $this->where(['status' => self::STATUS_NORMAL, 'order_id' => $order_id])
            ->get(['id', 'goods_name', 'goods_price', 'goods_num']);

        return $data;
    }

    /**
     * 描述：获取订单商品数据
     * User：Jack Yang
     *
     * @param $order_id
     * @return mixed
     */
    public function getOrderGoods($order_id)
    {
        $data = $this->where(['status' => self::STATUS_NORMAL, 'order_id' => $order_id])
            ->get(['id', 'goods_image', 'goods_name', 'goods_price', 'goods_num', 'logistics_no']);

        return $data;
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLogModel extends Model
{
    protected $table = 'order_log';
    protected $primaryKey = 'id';

    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    const TYPE_USERS = 1; //操作者 用户
    const TYPE_ADMINER = 2; // 管理员

    protected $guarded = [];

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
     * 描述：获取订单日志信息
     * User：Jack Yang
     *
     * @param $order_id
     * @return mixed
     */
    public function getOrderLog($order_id)
    {
        $data = $this->where(['order_id' => $order_id, 'status' => self::STATUS_NORMAL])
            ->get(['created_at','msg']);

        return $data;
    }

    /**
     * 描述：创建订单日志数据
     * User：Jack Yang
     *
     * @param $data
     * @return mixed
     */
    public function createOrderLog($data)
    {
        $result = $this->create($data);

        return $result;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = 'order';
    protected $primaryKey = 'id';

    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    const ORDER_CANCEL = 0; //已取消
    const ORDER_NORMAL = 10; //默认状态(未付款)
    const ORDER_ALREADY_PAY = 20; //已付款
    const ORDER_DELIVERY = 30; //已发货
    const ORDER_GET = 40; //已收货
    const ORDER_OVER = 50; //已完成

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
     * 描述：获取单个用户的订单信息
     * User：Jack Yang
     *
     * @param $u_id
     * @param $type
     * @return mixed
     */
    public function getUserOrder($u_id, $type)
    {
        $data = $this->where(['buy_id' => $u_id, 'status' => self::STATUS_NORMAL])
            ->when(is_numeric($type), function ($query) use ($type) {
                $query->where('order_status', $type);
            })
            ->orderBy('create_time', 'desc')
            ->get(['id', 'order_amount']);

        return $data;
    }

    /**
     * 描述：获取单条指定数据行
     * User：Jack Yang
     *
     * @param $id
     * @param array $map
     * @return mixed
     */
    public function getFind($id, $map = ['*'])
    {
        $data = $this->where('id', $id)->first($map);

        return $data;
    }

    /**
     * 描述：获取订单数据
     * User：Jack Yang
     *
     * @param $order_sn
     * @param $pay_sn
     * @param $order_status
     * @return mixed
     */
    public function getLists($order_sn, $pay_sn, $order_status)
    {
        $data = $this
            ->when($order_sn, function ($query) use ($order_sn) {
                $query->where('order_sn', 'like', "%{$order_sn}%");
            })
            ->when($pay_sn, function ($query) use ($pay_sn) {
                $query->where('pay_sn', 'like', "%{$pay_sn}%");
            })
            ->when($order_status || $order_status == 0 and $order_status != 100, function ($query) use ($order_status) {
                $query->where('order_status', $order_status);
            })
            ->where(['status' => self::STATUS_NORMAL])
            ->orderBy('order_status', 'desc')
            ->orderBy('create_time', 'desc')
            ->paginate(10);

        return $data;
    }

    /**
     * 描述：创建主订单数据
     * User：Jack Yang
     *
     * @param $data
     * @return mixed
     */
    public function createData($data)
    {
        $data['pay_sn'] = $this->makePaySn($data['buy_id']);
        $data['order_sn'] = $this->makeOrderSn($data['pay_sn']);

        $data = $this->create($data);

        return $data;
    }

    /**
     * 描述：生成支付单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
     * 长度 =2位 + 10位 + 3位 + 3位  = 18位
     * 1000个会员同一微秒提订单，重复机率为1/100
     *
     * User：Jack Yang
     *
     * @param int $member_id 用户编号
     * @return string
     */
    private function makePaySn($member_id)
    {
        return mt_rand(10, 99)
            . sprintf('%010d', time() - 946656000)
            . sprintf('%03d', (float)microtime() * 1000)
            . sprintf('%03d', (int)$member_id % 1000);
    }

    /**
     * 描述：订单编号生成规则，n(n>=1)个订单表对应一个支付表，
     * 生成订单编号(年取1位 + $pay_id取13位 + 第N个子订单取2位)
     * 1000个会员同一微秒提订单，重复机率为1/100
     *
     * User：Jack Yang
     *
     * @param int $pay_id 支付表自增ID
     * @return string
     */
    private function makeOrderSn($pay_id)
    {
        //记录生成子订单的个数，如果生成多个子订单，该值会累加
        static $num;
        if (empty($num)) {
            $num = 1;
        } else {
            $num++;
        }
        return (date('y', time()) % 9 + 1) . sprintf('%013d', $pay_id) . sprintf('%02d', $num);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsModel extends Model
{
    protected $table = 'goods';

    protected $primaryKey = 'id';

    protected $guarded = [];

    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    const GOODS_UP = 20;
    const GOODS_BOTTOM = 10;

    protected function getDateFormat()
    {
        return 'U';
    }

    /**
     * 描述：获取商品数据
     * User：Jack Yang
     *
     * @param $where
     * @return mixed
     */
    public function getGoodsList($where)
    {
        $data = $this->where(['status' => self::STATUS_NORMAL])
            ->when(isset($where['name']), function ($query) use ($where) {
                $query->where('name', 'like', "%{$where['name']}%");
            })
            ->when(isset($where['id']), function ($query) use ($where) {
                $query->where('id', $where['id']);
            })
            ->when(isset($where['goods_status']), function ($query) use ($where) {
                $query->where('goods_status', $where['goods_status']);
            })
            ->orderBy('goods_status', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['id', 'name', 'image', 'price', 'updated_at', 'goods_status', 'created_at']);

        return $data;
    }

    /**
     * 描述：创建商品数据
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
     * 描述：更新数据
     * User：Jack Yang
     *
     * @param $id
     * @param $save
     * @return mixed
     */
    public function savaData($id, $save)
    {
        $int = $this->where('id', $id)->update($save);

        return $int;
    }

    /**
     * 描述：商品详情
     * User：Jack Yang
     *
     * @param $id
     * @return mixed
     */
    public function findData($id)
    {
        $data = $this->where(['id' => $id])
            ->first(['id', 'name', 'class_id', 'image', 'detail', 'stock', 'price']);

        return $data;
    }

    /**
     * 描述：获取分类商品数据
     * User：Jack Yang
     *
     * @param $class_id
     */
    public function getClassGoods($class_id)
    {
        $data = $this->where(['status' => self::STATUS_NORMAL, 'class_id' => $class_id])
            ->where('goods_status', self::GOODS_UP)
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['id', 'name', 'price', 'image']);

        return $data;
    }
}

<?php

namespace App\Models;

use App\Exceptions\ValidatorException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GoodsClassModel extends Model
{
    protected $table = 'goods_class';

    protected $primaryKey = 'id';

    protected $guarded = [];

    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    protected function getDateFormat()
    {
        return 'U';
    }

    /**
     * 描述：判断分类名称是否可用
     * User：Jack Yang
     *
     * @param $name
     * @return mixed
     */
    public function checkName($name)
    {
        $status = Cache::remember('GOODS_NAME_' . $name, 30 * 24 * 60, function () use ($name) {
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

        Cache::forget('GOODS_NAME_' . $data['name']);

        return $db;
    }

    /**
     * 描述：获取分类数据
     * User：Jack Yang
     *
     * @param $name
     * @return mixed
     */
    public function getGoodsClass($name)
    {
        $data = $this->where(['status' => self::STATUS_NORMAL])
            ->when($name, function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->orderBy('updated_at', 'desc')->paginate(8, ['id', 'name', 'updated_at']);

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

        throw_unless($int, ValidatorException::class, '操作执行失败');

        return $int;
    }

    /**
     * 描述：获取所有的分类
     * User：Jack Yang
     *
     * @return mixed
     */
    public function getAllClass()
    {
        $data = $this->where(['status' => self::STATUS_NORMAL])->get(['id', 'name']);

        return $data;
    }
}

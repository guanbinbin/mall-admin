<?php

namespace App\Models;

use App\Exceptions\ValidatorException;
use Illuminate\Database\Eloquent\Model;

class NavigationModel extends Model
{
    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    protected $table = 'navigation';
    protected $primaryKey = 'id';

    protected $guarded = [];

    protected function getDateFormat()
    {
        return 'U';
    }

    /**
     * 描述：创建导航栏数据
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
     * 描述：获取列表数据
     * User：Jack Yang
     *
     * @param $title
     * @return mixed
     */
    public function getList($title)
    {
        $data = $this->where('status', self::STATUS_NORMAL)
            ->when($title, function ($query) use ($title) {
                $query->where('title', 'like', "%{$title}%");
            })
            ->paginate(8, ['id', 'title', 'created_at', 'updated_at']);

        return $data;
    }

    /**
     * 描述：获取单条数据
     * User：Jack Yang
     *
     * @param $id
     * @return mixed
     */
    public function getFind($id)
    {
        $data = $this->where('id', $id)->first(['id', 'title', 'goods']);

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

        throw_unless($int, ValidatorException::class, '更新数据失败');

        return $int;
    }

    /**
     * 描述：获取所有首页导航数据
     * User：Jack Yang
     *
     * @return mixed
     */
    public function getListApi()
    {
        $data = $this->where('status', self::STATUS_NORMAL)
            ->get(['id', 'title', 'goods']);

        return $data;
    }

    /**
     * 描述：获取到导航栏的商品编号
     * User：Jack Yang
     *
     * @param $id
     * @return mixed
     */
    public function getNavGoods($id)
    {
        $data = $this->where('id', $id)->first(['goods']);

        return $data->goods;
    }


}

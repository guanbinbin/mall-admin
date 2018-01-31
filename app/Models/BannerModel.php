<?php

namespace App\Models;

use App\Exceptions\ValidatorException;
use Illuminate\Database\Eloquent\Model;

class BannerModel extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'id';

    protected $guarded = [];

    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    protected function getDateFormat()
    {
        return 'U';
    }

    /**
     * 描述：新增轮播数据
     * User：Jack Yang
     *
     * @param $data
     * @return mixed
     */
    public function createData($data)
    {
        $data = $this->create($data);

        throw_unless($data, ValidatorException::class, '添加轮播信息失败');

        return $data;
    }

    /**
     * 描述：获取到轮播信息
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
            ->orderBy('updated_at', 'desc')
            ->paginate(8, ['id', 'title', 'image', 'created_at', 'updated_at']);

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
    public function savaData($id, $update)
    {
        $int = $this->where('id', $id)->update($update);

        throw_unless($int, \Exception::class, '操作失败');

        return $int;
    }

    /**
     * 描述：获取详情
     * User：Jack Yang
     *
     * @param $id
     * @return mixed
     */
    public function getFind($id)
    {
        $data = $this->where('id', $id)->first(['id', 'title', 'type', 'ex_id', 'image']);

        return $data;
    }

    public function getApiList()
    {
        $data = $this->where('status', self::STATUS_NORMAL)
            ->orderBy('updated_at', 'desc')
            ->get(['id', 'image', 'type']);

        return $data;
    }
}

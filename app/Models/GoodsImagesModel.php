<?php

namespace App\Models;

use App\Exceptions\ValidatorException;
use Illuminate\Database\Eloquent\Model;

class GoodsImagesModel extends Model
{
    protected $table = 'goods_images';

    protected $primaryKey = 'id';

    protected $guarded = [];

    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;

    protected function getDateFormat()
    {
        return 'U';
    }

    public function createData($data)
    {
        $data = $this->create($data);

        throw_unless($data, \Exception::class, '上传更多图片失败');

        return $data;
    }

    /**
     * 描述：获取商品图片数据
     * User：Jack Yang
     *
     * @param $id
     * @return mixed
     */
    public function getGoodsImage($id)
    {
        $data = $this->where(['goods_id' => $id, 'status' => self::STATUS_NORMAL])
            ->orderBy('sort', 'asc')->get(['id', 'picture_id']);

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
}

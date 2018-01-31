<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PicturesModel extends Model
{
    protected $table = 'pictures';

    protected $primaryKey = 'id';

    protected $guarded = [];

    const TYPE_LOCAHOST = 1;

    protected function getDateFormat()
    {
        return 'U';
    }

    public function getPictures($id)
    {
        $data = Cache::remember('PICTURES_INFO_ID_' . $id, 60 * 24 * 7, function () use ($id) {
            return $this->where('id', $id)->first();
        });

        return $data;
    }

    public function insertDB($data)
    {
        $data = $this->create($data);

        return $data;
    }
}

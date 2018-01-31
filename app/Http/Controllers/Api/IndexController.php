<?php

namespace App\Http\Controllers\Api;

use App\Models\BannerModel;
use App\Models\GoodsModel;
use App\Models\NavigationModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function test()
    {
        return ajaxReturn('success', 'test api');
    }

    /**
     * 描述：获取banner信息
     * User：Jack Yang
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBanner()
    {
        $BannerModel = new BannerModel();
        $data = $BannerModel->getApiList();

        foreach ($data as &$item) {
            $item->path = getCover($item->image)->path;
        }

        return ajaxReturn('success', '获取成功', $data);
    }

    /**
     * 描述：首页导航栏
     * User：Jack Yang
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNavigation()
    {
        $NavigationModel = new NavigationModel();

        $dbs = (object)[];

        $data = $NavigationModel->getListApi();

        foreach ($data as &$item) {
            $item->name = $item->title;
            unset($item->title);
        }

        if (count($data->toArray())) {
            $dbs->goods = $this->setNavGoods($data->first()->goods);
        }

        $dbs->nav = $data;

        return ajaxReturn('success', 'ok', $dbs);
    }

    /**
     * 描述：获取不同标题的商品数据
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNavGoods(Request $request)
    {
        $id = $request->get('id');

        $NavigationModel = new NavigationModel();
        $ids = $NavigationModel->getNavGoods($id);
        $data = $this->setNavGoods($ids);

        return ajaxReturn('success', 'ok', $data);
    }

    /**
     * 描述：根据首页导航栏获取到商品详细数据
     * User：Jack Yang
     *
     * @param $ids
     * @return object
     */
    private function setNavGoods($ids)
    {
        $goods = [];
        $GoodsModel = new GoodsModel();
        $ids = explode(',', $ids);
        foreach ($ids as $value) {
            $info = $GoodsModel->where(['id' => $value])->first(['id', 'name', 'price', 'image']);
            if ($info) {
                $info->image = getCover($info->image)->path;
                $goods[] = $info;
            }
        }

        return (object)$goods;
    }
}

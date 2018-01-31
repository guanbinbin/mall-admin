<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\BannerAction;
use App\Models\BannerModel;
use Illuminate\Http\Request;

class BannerController extends CommonController
{
    use BannerAction;

    /**
     * 描述：轮播主页
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = $request->get('title', null);
        $BannerModel = new BannerModel();
        $data = $BannerModel->getList($title);

        return view('admin.banner.index', ['data' => $data, 'title' => $title]);
    }

    /**
     * 描述：新增
     * User：Jack Yang
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    public function edit(Request $request)
    {
        $id = $request->get('id');

        $BannerModel = new BannerModel();

        $data = $BannerModel->getFind($id);


        return view('admin.banner.edit', ['data' => $data]);
    }
}

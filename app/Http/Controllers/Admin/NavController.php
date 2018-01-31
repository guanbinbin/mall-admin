<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\NavAction;
use App\Models\NavigationModel;
use Illuminate\Http\Request;

class NavController extends CommonController
{
    use NavAction;

    /**
     * 描述：首页导航管理
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = $request->get('title', null);

        $NavigationModel = new NavigationModel();

        $data = $NavigationModel->getList($title);

        return view('admin.nav.index', ['data' => $data, 'title' => $title]);
    }

    /**
     * 描述：编辑
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');

        $NavigationModel = new NavigationModel();

        $data = $NavigationModel->getFind($id);

        return view('admin.nav.edit', ['data' => $data]);
    }
}

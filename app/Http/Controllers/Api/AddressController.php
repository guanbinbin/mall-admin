<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\AddressAction;
use App\Models\UsersAddressModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    use AddressAction;

    /**
     * 描述：收货地址列表
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {
        $u_id = $request->get('u_id');

        $UsersAddressModel = new UsersAddressModel();
        $data = $UsersAddressModel->lists($u_id);

        foreach ($data as &$item) {
            $item->_id = $item->id;
            $item->gender = $this->reverseSex($item->sex);
            $item->is_def = $this->reverseDefault($item->default);
            $item->tel = $item->phone;
        }

        return ajaxReturn(true, 'success', $data);
    }

    /**
     * 描述：获取单个收货地址信息
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAddressInfo(Request $request)
    {
        $id = $request->get('id');

        $UsersAddressModel = new UsersAddressModel();
        $data = $UsersAddressModel->getFind($id);

        $data->sex = $this->reverseSex($data->sex);
        if (!$data->default){
            $data->default = !1;
        }

        return ajaxReturn(true, 'success', $data);
    }
}

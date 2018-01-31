<?php
/**
 * Created by PhpStorm.
 * User：Jack Yang
 *
 * Date: 2018/1/19
 * Time: 下午2:22
 */

namespace App\Http\Controllers\Api\Traits;

use App\Models\UsersAddressModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait AddressAction
{
    /**
     * 描述：删除收货地址
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $u_id = $request->get('u_id');

        $UsersAddressModel = new UsersAddressModel();
        $check = $UsersAddressModel->getFind($id);

        DB::beginTransaction();
        try {

            $int = $UsersAddressModel->saveData($id,
                ['status' => $UsersAddressModel::STATUS_DELETE]);

            throw_unless($int, \Exception::class, '更新失败');

            //判断是否为默认地址(如果为默认地址，则下该用户的第一条数据更新为默认地址)
            if ($check->default == $UsersAddressModel::DEFAULT_TRUE) {
                $id = $UsersAddressModel->getUserFind($u_id);

                $defaultInt = $UsersAddressModel->saveData($id,
                    ['default' => $UsersAddressModel::DEFAULT_TRUE]);

                throw_unless($defaultInt, \Exception::class, '更新默认地址失败');

            }

            DB::commit();

            return ajaxReturn(true, '操作成功');
        } catch (\Exception $exception) {
            DB::rollback();

            return ajaxReturn(false, $exception->getMessage());
        }
    }

    /**
     * 描述：设置默认地址
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function defaultAddress(Request $request)
    {
        $u_id = $request->get('u_id');
        $id = $request->get('id');

        $UsersAddressModel = new UsersAddressModel();

        DB::beginTransaction();
        try {
            $clearInt = $UsersAddressModel->closeDefault($u_id);
            throw_unless($clearInt, \Exception::class, '更新失败');

            $int = $UsersAddressModel->saveData($id, ['default' => $UsersAddressModel::DEFAULT_TRUE]);

            throw_unless($int, \Exception::class, '更新默认地址失败');

            DB::commit();

            return ajaxReturn(true, '更新成功');
        } catch (\Exception $exception) {

            DB::rollback();

            return ajaxReturn(false, $exception->getMessage());
        }

    }

    /**
     * 描述：添加收货地址
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAction(Request $request)
    {
        $u_id = $request->get('u_id');
        $params = $request->get('params');

        $UsersAddressModel = new UsersAddressModel();

        $check = $UsersAddressModel->lists($u_id);

        $data = [
            'u_id' => $u_id, 'sex' => $this->judgeSex($params['gender']), 'name' => $params['name'],
            'phone' => $params['tel'], 'address' => $params['address'],
        ];

        //判断该用户是否有地址信息数据
        if ($check->first()) { //如果有数据的话(判断采用何种默认地址)
            $default = $this->judgeDefault($params['is_def']);

            $data['default'] = $this->judgeDefault($params['is_def']);
            if ($default) {
                $UsersAddressModel->closeDefault($u_id);
            }
        } else { //没有地址，则新增地址为默认地址
            $data['default'] = $UsersAddressModel::DEFAULT_TRUE;
        }
        $result = $UsersAddressModel->createData($data);

        if (!$result) {
            return ajaxReturn(false, '添加地址失败');
        }

        return ajaxReturn(true, '添加地址成功');
    }

    /**
     * 描述：判断性别参数
     * User：Jack Yang
     *
     * @param $sex
     * @return int
     */
    protected function judgeSex($sex)
    {
        switch ($sex) {
            case 'male':
                $sex = 1;
                break;
            case 'female':
                $sex = 0;
                break;
            default:
                $sex = 1;
        }

        return $sex;
    }

    /**
     * 描述：判断是否默认地址
     * User：Jack Yang
     *
     * @param $default
     * @return int
     */
    protected function judgeDefault($default)
    {
        switch ($default) {
            case true:
                $default = 1;
                break;
            case false:
                $default = 0;
                break;
            default:
                $default = 0;
        }

        return $default;
    }

    /**
     * 描述：反转性别
     * User：Jack Yang
     *
     * @param $sex
     * @return string
     */
    protected function reverseSex($sex)
    {
        switch ($sex) {
            case 1:
                $sex = 'boy';
                break;
            case 0:
                $sex = 'female';
                break;
            default:
                $sex = 'boy';
        }
        return $sex;
    }

    /**
     * 描述：反转默认标示
     * User：Jack Yang
     *
     * @param $default
     * @return bool
     */
    protected function reverseDefault($default)
    {
        switch ($default) {
            case 1:
                $default = true;
                break;
            case 0:
                $default = false;
                break;
            default:
                $default = false;
        }

        return $default;
    }
}
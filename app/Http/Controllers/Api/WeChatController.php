<?php

namespace App\Http\Controllers\Api;

use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class WeChatController extends Controller
{
    /**
     * 描述：进入小程序必备操作
     * User：Jack Yang
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSessionKey(Request $request)
    {
        $code = $request->get('code');

        $appid = config('wechat.appid');
        $appsecret = config('wechat.appsecret');
        $url = "https://api.weixin.qq.com/sns/jscode2session";

        $additional = "?appid={$appid}&secret={$appsecret}&js_code={$code}&grant_type=authorization_code";

        $urlLink = $url . $additional;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlLink);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);    //该设置会将头文件信息作为数据流输出
        $output = curl_exec($ch);
        curl_close($ch);

        if ($output) {
            $parameter = json_decode($output, true);
            return $this->WeChatRegis($parameter['session_key'], $parameter['openid']);
        } else {
            return ajaxReturn('error', '登录失败');
        }
    }

    private function WeChatRegis($session_key, $openid)
    {
        $UserModel = new UserModel();
        $result = $UserModel->checkOpenid($openid);

        if (!$result) { //第一次进入小程序，注册该用户

            $userData = [
                'name' => '用户' . time(),
                'email' => '用户' . time(),
                'password' => '用户' . time(),
                'session_key' => $session_key,
                'openid' => $openid
            ];

            $info = $UserModel->createData($userData);

            if (!$info) {
                myLog('存储数据失败');
            }

            Cache::forget('OPENID_CHECK_' . $openid);

            $userId = $info->id;
        } else { //非首次进入小程序,更新用户session_key
            $userId = $result;
        }

        if (session()->exists('WeChatUser')) {
            session()->put('WeChatUser', $userId);
        }

        return ajaxReturn('success', 'ok', $userId);
    }

}

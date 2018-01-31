<?php

//use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([], function ($router) {

    $router->get('/test', 'IndexController@test');

    /** 获取用户的appId和sessionKey */
    $router->get('/get/sessionkey', 'WeChatController@getSessionKey');

    /** page/index/index */
    $router->group(['prefix' => 'index'], function ($router) {
        //获取banner数据
        $router->get('/banner', 'IndexController@getBanner');
        //获取navigation数据
        $router->get('/navigation', 'IndexController@getNavigation');
        //获取首页导航栏商品数据
        $router->get('/navigation/goods', 'IndexController@getNavGoods');
    });

    /** page/goods/* */
    $router->group(['prefix' => 'goods'], function ($router) {

        /* page/goods/detail */
        $router->group(['prefix' => 'detail'], function ($router) {
            //获取商品详情
            $router->get('/', 'GoodsController@getDetail');
            //添加到购物车
            $router->post('/addcart', 'GoodsController@addShopCart');
        });

    });

    /** page/classify/* */
    $router->group(['prefix' => 'classify'], function ($router) {

        /* page/classify/index */
        $router->group(['prefix' => 'index'], function ($router) {
            //获取分类和商品数据
            $router->get('/', 'ClassifyController@getIndex');
            //获取不同分类下的商品数据
            $router->get('/tab/goods', 'ClassifyController@getGoods');
        });

    });

    /** page/cart/* */
    $router->group(['prefix' => 'carts'], function ($router) {
        //获取购物车数据
        $router->get('/', 'CartsController@getIndex');
        //改变购物车商品数量
        $router->post('/total/save', 'CartsController@totalSave');
        //删除购物车商品数据
        $router->post('/del/goods', 'CartsController@delCartsGoods');
        //清空购物车
        $router->post('/clear', 'CartsController@clearCartsGoods');
    });

    /** page/order/* */
    $router->group(['prefix' => 'order'], function ($router) {

        /* page/order/confirm */
        $router->group(['prefix' => 'confirm'], function ($router) {
            //获取用户默认地址信息
            $router->get('/default/address', 'OrderController@defaultAddress');
            //获取购物车数据
            $router->post('/goods', 'OrderController@getGoodsData');
            //确认订单界面
            $router->post('/', 'OrderController@confirm');
        });
    });

    /** page/user/* */
    $router->group(['prefix' => 'user'], function ($router) {

        /* page/order/list/index */
        $router->group(['prefix' => 'order'], function ($router) {
            //我的订单
            $router->post('/lists', 'OrderController@lists');
            //订单详情
            $router->post('/details', 'OrderController@details');
        });

        /* page/address/list */
        $router->group(['prefix' => 'address'], function ($router) {
            //用户收货地址列表
            $router->get('/', 'AddressController@lists');
            //新增地址操作
            $router->post('/add', 'AddressController@addAction');
            //设置默认收货地址
            $router->post('/set/default', 'AddressController@defaultAddress');
            //地址详情
            $router->post('/info', 'AddressController@getAddressInfo');
            //删除收货地址
            $router->post('/delete', 'AddressController@deleteAction');
        });

    });


});
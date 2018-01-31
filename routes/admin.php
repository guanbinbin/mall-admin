<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/icon', 'HomeController@icon')->name('admin.icon'); //图标页面


Route::group([], function ($router) {

    Auth::routes();

    /** sidebar data */
    $router->group(['middleware' => 'sidebar'], function ($router) {

        $router->get('/home', 'HomeController@index')->name('home');

        /*
         * 系统设置
         */
        $router->group(['prefix' => 'rights'], function ($router) {

            /** 角色组管理 */
            $router->group(['prefix' => 'group'], function ($router) {
                $router->get('/', 'GroupController@index')->name('admin.rights.group.index');
                $router->post('/create', 'GroupController@createAction')->name('admin.rights.group.create');
                $router->post('/delete', 'GroupController@deleteAction')->name('admin.rights.group.delete');
                $router->get('/info', 'GroupController@infoAction')->name('admin.rights.group.info');
                $router->post('/rule/edit', 'GroupController@ruleEdit')->name('admin.rights.group.rule.edit');
            });

            /** 规则管理(菜单管理) */
            $router->group(['prefix' => 'rule'], function ($router) {
                $router->get('/', 'RuleController@index')->name('admin.rights.rule.index');
                $router->post('/create', 'RuleController@createAction')->name('admin.rights.rule.create');
            });

            /** 管理员组 */
            $router->group(['prefix' => 'users'], function ($router) {
                $router->get('/', 'UsersController@index')->name('admin.rights.users.index');
                $router->get('/info', 'UsersController@edit')->name('admin.rights.users.edit');
                $router->post('/rule/edit', 'UsersController@editAction')->name('admin.rights.users.rule.edit');
            });
        });

        /*
         * 用户设置
         */
        $router->group(['prefix' => 'members'], function ($router) {

            /** 用户管理 */
            $router->group(['prefix' => 'base'], function ($router) {
                $router->get('/', 'MembersController@memberIndex')->name('admin.members.base.index');
                $router->get('/info', 'MembersController@memberEdit')->name('admin.members.base.edit');
            });
        });

        /*
         * 商品设置
         */
        $router->group(['prefix' => 'goods'], function ($router) {

            /** 商品管理 */
            $router->group(['prefix' => 'base'], function ($router) {
                $router->get('/', 'GoodsController@index')->name('admin.goods.base.index');
                $router->get('/create', 'GoodsController@create')->name('admin.goods.base.create');
                $router->post('/create', 'GoodsController@createGoods')->name('admin.goods.base.create.goods');
                $router->post('/up', 'GoodsController@upAction')->name('admin.goods.base.up');
                $router->post('/down', 'GoodsController@downAction')->name('admin.goods.base.down');
                $router->post('/delete', 'GoodsController@deleteAction')->name('admin.goods.base.delete');
                $router->post('/multi', 'GoodsController@multiAction')->name('admin.goods.base.multi');
                $router->get('/edit', 'GoodsController@edit')->name('admin.goods.base.edit');
                $router->post('/edit', 'GoodsController@editAction')->name('admin.goods.base.edit.goods');
            });

            /** 商品分类 */
            $router->group(['prefix' => 'class'], function ($router) {
                $router->get('/', 'GoodsClassController@index')->name('admin.goods.class.index');
                $router->post('/create', 'GoodsClassController@createAction')->name('admin.goods.class.create');
                $router->post('/delete', 'GoodsClassController@deleteAction')->name('admin.goods.class.delete');
            });
        });

        /*
         * 程序前端设定
         */
        $router->group(['prefix' => 'demo'], function ($router) {

            /** 首页轮播图 */
            $router->group(['prefix' => 'banner'], function ($router) {
                $router->get('/', 'BannerController@index')->name('admin.demo.banner.index');
                $router->get('/create', 'BannerController@create')->name('admin.demo.banner.create');
                $router->post('/create', 'BannerController@createAction')->name('admin.demo.banner.create.action');
                $router->post('/delete', 'BannerController@deleteAction')->name('admin.demo.banner.delete.action');
                $router->get('/edit', 'BannerController@edit')->name('admin.demo.banner.edit');
                $router->post('/edit', 'BannerController@editAction')->name('admin.demo.banner.edit.action');
            });

            /** 首页导航栏 */
            $router->group(['prefix' => 'nav'], function ($router) {
                $router->get('/', 'NavController@index')->name('admin.demo.nav.index');
                $router->post('/create', 'NavController@createAction')->name('admin.demo.nav.create.action');
                $router->get('/edit', 'NavController@edit')->name('admin.demo.nav.edit');
                $router->post('/edit', 'NavController@editAction')->name('admin.demo.nav.edit.action');
                $router->post('/delete', 'NavController@deleteAction')->name('admin.demo.nav.delete.action');

            });
        });

        /*
         * 订单设置
         */
        $router->group(['prefix' => 'order'], function ($router) {

            /** 订单中心 */
            $router->group(['prefix' => 'base'], function ($router) {
                $router->get('/', 'OrderController@index')->name('admin.order.base.index');
                $router->get('/edit', 'OrderController@edit')->name('admin.order.base.edit');
            });
        });
    });
});


<?php

Auth::routes();

Route::get('/api/suggestion', 'UserController@apiSuggestion');
Route::post('/saveSuggestion', 'UserController@saveSuggestion');
Route::get('/registerCompany', 'UserController@registerCompany');
Route::post('/saveIntent', 'UserController@saveIntent');

//微信
Route::get('/getToken', 'WeixinController@getToken');
Route::get('/createList', 'WeixinController@createList');
Route::get('/qunSend', 'WeixinController@qunSend');
 

//后台模块
Route::group(['namespace' => 'Admin', 'middleware' => 'auth'], function () {
//Route::group(['namespace' => 'Admin', 'prefix' => 'admin', ], function () {
    //首页
    Route::get('/', function () {
        return view('admin.dashboard');
    });
    Route::get('/index', function () {
        return view('admin.dashboard');
    });
    Route::get('/home', function () {
        return view('admin.dashboard');
    });
    //dept
    Route::get('dept/lists', 'DeptController@getLists')->name('dept.lists');
    Route::post('dept/{dept}', 'DeptController@disable')->name('dept.disable');
    Route::resource('dept', 'DeptController', ['only' =>
        ['index', 'store', 'update', 'show']
    ]);

    //feedback
    Route::get('feedback/lists', 'FeedbackController@getLists')->name('feedback.lists');
    Route::post('feedback/{feedback}', 'FeedbackController@disable')->name('feedback.disable');
    Route::resource('feedback', 'FeedbackController', ['only' =>
        ['index', 'store', 'update', 'show']
    ]);

    //客户
    Route::get('customer/lists', 'CustomerController@getLists')->name('customer.lists');
    Route::post('customer/{customer}', 'CustomerController@disable')->name('customer.disable');
    Route::resource('customer', 'CustomerController', ['only' =>
        ['index', 'store', 'update', 'show']
    ]);
    //客户充值记录
    Route::get('billrecord/lists', 'BillRecordController@getLists')->name('billrecord.lists');
    Route::resource('billrecord', 'BillRecordController', ['only' =>
        ['index', 'store']
    ]);

    //后台用户
    Route::get('user/lists', 'AdminUserController@getLists')->name('user.lists');
    Route::post('user/{user}', 'AdminUserController@disable')->name('user.disable');
    Route::post('user-reset/{user}', 'AdminUserController@reset')->name('user.reset');
    Route::resource('user', 'AdminUserController', ['only' =>
        ['index', 'store', 'update', 'show']
    ]);

    //角色
    Route::get('role/lists', 'RoleController@getLists')->name('role.lists');
    Route::resource('role', 'RoleController');

    //权限
    Route::get('permission/lists', 'PermissionController@getLists')->name('permission.lists');
    Route::resource('permission', 'PermissionController');
});

<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/permissions', 'ApiController@getPermissionsLists')->name('api.permissions.lists');
    Route::get('/roles', 'ApiController@getRolesLists')->name('api.roles.lists');
    Route::get('/depts', 'ApiController@getDeptsLists')->name('api.depts.lists');
    Route::get('/customers', 'ApiController@getCustomersLists')->name('api.customers.lists');
    Route::get('/users', 'ApiController@getUsersLists')->name('api.users.lists');
    Route::get('/getkflist', 'ApiController@getKFList')->name('api.kf.lists');
    Route::get('/createkf', 'ApiController@createKF')->name('api.kf.create');
    Route::get('/KFInvite', 'ApiController@KFInvite')->name('api.kf.invite');
    Route::get('/KFVisit', 'ApiController@KFVisit')->name('api.kf.visit');
});

Route::get('/export', 'ExportDataController@index');



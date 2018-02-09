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
//Route::middleware('auth:api')->get('/roles', 'ApiController@getRolesLists')->name('api.roles.lists');
Route::get('/permissions', 'ApiController@getPermissionsLists')->name('api.permissions.lists');
Route::get('/roles', 'ApiController@getRolesLists')->name('api.roles.lists');
Route::get('/depts', 'ApiController@getDeptsLists')->name('api.depts.lists');
Route::get('/customers', 'ApiController@getCustomersLists')->name('api.customers.lists');
Route::get('/users', 'ApiController@getUsersLists')->name('api.users.lists');

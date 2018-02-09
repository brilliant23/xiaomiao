<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Dept;
use App\Permission;
use App\Role;
use App\User;

class ApiController extends Controller
{

    /**
     * @return mixed
     */
    function getUsersLists(){
        $data['sale'] =  User::where('dept_id', config('params.user_type.sale'))->pluck('name', 'id');
        $data['account'] =  User::where('dept_id', config('params.user_type.account'))->pluck('name', 'id');
        return $data;
    }

    /**
     * @return mixed
     */
    function getCustomersLists(){
        return Customer::pluck('company_name', 'id');
    }

    /**
     * @return mixed
     */
    function getDeptsLists(){
        return Dept::where('status', 1)->pluck('name', 'id');
    }

    /**
     * @return mixed
     */
    function getRolesLists(){
        return Role::pluck('name', 'id');
    }

    /**
     * @return mixed
     */
    function getPermissionsLists(){
        return Permission::pluck('name', 'id');
    }
}

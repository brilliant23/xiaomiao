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
    /**
     * 获取客服列表
     * @return mixed
     */
    function getKFList(WeixinController $weiXin){
        $token = $weiXin ->getToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token='.$token;

        $CurlController = new CurlController($url);
        $result = $CurlController ->getRequest();
        return ($result);
    }

    /**
     * 客服创建
     * @return mixed
     */
    function createKF(WeixinController $weiXin){
        $token = $weiXin ->getToken();
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/add?access_token='.$token;
        $data = [
            'kf_account'=>'kf2001@xiaomiaokuaiji',
            'nickname'=>'客服1',
        ];
        $data = json_encode($data);
        $CurlController = new CurlController($url,'',$data,'post');
        $result = $CurlController ->postRequest();
        return ($result);
    }
    /**
     * 客服认证
     * @return mixed
     */
    function KFInvite(WeixinController $weiXin){
        $token = $weiXin ->getToken();
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/inviteworker?access_token='.$token;
        $data = [
            'kf_account'=>'kf2001@xiaomiaokuaiji',
            'invite_wx'=>'wxid_5pku7rh2duox11',
        ];
        $data = json_encode($data);
        $CurlController = new CurlController($url,'',$data,'post');
        $result = $CurlController ->postRequest();
        return ($result);
    }

    /**
     * 客服和用户之间创建对话
     * @return mixed
     */
    function KFVisit(WeixinController $weiXin){
        $token = $weiXin ->getToken();
        $url = 'https://api.weixin.qq.com/customservice/kfsession/create?access_token='.$token;
        $data = [
            'kf_account'=>'kf2001@xiaomiaokuaiji',
            'openid'=>'oFAI7wzNEu9Qoh4L0vcFKFVFM7FM',
        ];
        $data = json_encode($data);
        $CurlController = new CurlController($url,'',$data,'post');
        $result = $CurlController ->postRequest();
        return ($result);
    }

}

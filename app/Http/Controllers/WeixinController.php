<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class WeixinController extends Controller
{
    //获取微信工作号token
    public function getToken(){

        //先从redis 中取
        $redis  = new \Redis();
        $redis ->connect('127.0.0.1',6379,3);
        $token = $redis ->get('access_token');
        //redis中不存在时，调用微信的接口申请;获取后存入redis中，并设置过期时间
        if(!$token){
            //调用接口获取token
            $weixin = Config::get('app.WEIXIN');
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$weixin['APPID'].'&secret='.$weixin['APPSECRET'];
            $CurlController = new CurlController($url);
            $token = $CurlController ->getRequest();
            $token = json_decode($token,true);
            //存入redis,设置过期时间;
            $redis ->set('access_token',$token['access_token'],$token['expires_in']);
        }
        return $token;
    }
    //创建自定义菜单
    public function createList($data=[]){
        $data = ' {
                     "button":[
                     {    
                          "type":"view",
                          "name":"'.$this->unicode2utf8_2('\ue11B').'投诉建议",
                          "url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3f3207efef0eb007&redirect_uri=http://www.phplijun.cn/api/suggestion?response_type=code&scope=snsapi_base&state=123#wechat_redirect"
                      },
                      {
                           "name":"'.$this->unicode2utf8_2('\ue112').'业务办理",
                           "sub_button":[
                           {    
                               "type":"view",
                               "name":"'.$this->unicode2utf8_2('\ue142').'注册申请",
                               "url":"http://www.phplijun.cn/registerCompany"
                            }
                            ]
                       },
                       {
                          
                          "type":"click",
                          "name":"'.$this->unicode2utf8_2('\ue036').'我的服务",
                          "sub_button":[
                           {    
                               "type":"click",
                               "name":"'.$this->unicode2utf8_2('\ue12f').'账户充值",
                               "key":"V1001_0006"
                            },
                            {    
                               "type":"click",
                               "name":"'.$this->unicode2utf8_2('\ue14a').'账户查询",
                               "key":"V1001_0005"
                            },
                            {    
                               "type":"click",
                               "name":"'.$this->unicode2utf8_2('\ue009').'在线客服",
                               "key":"V1001_0004"
                            },
                            {    
                               "type":"view",
                               "name":"'.$this->unicode2utf8_2('\ue11e').'小苗果蔬",
                               "url":"https://daojia.jd.com/activity/storeShare/index.html?channel=o2ostore&storeId=11663508&orgCode=296711"
                            },
                            {    
                               "type":"click",
                               "name":"'.$this->unicode2utf8_2('\ue30c').'商务合作",
                               "key":"V1001_0002"
                            }
                            ]
                       },
                       ]
                 }';
        //$data = json_decode($data,true);
        $token = $this ->getToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$token;
        $CurlController = new CurlController($url,'',$data,'post');
        $result = $CurlController ->getRequest($url,'',$data,'post');
        dd($result);
    }
    public function unicode2utf8_2($str) {
        $str = '{"result_str":"' . $str . '"}';
        $strarray = json_decode ( $str, true );
        return $strarray ['result_str'];
    }

    //消息群发
    public function qunSend(){
        $data = '{
                   "filter":{
                      "is_to_all":true
                   },
                   "text":{
                      "content":"群发测试1111！！！"
                   },
                    "msgtype":"text"
                }';
        $token = $this ->getToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$token;
        $CurlController = new CurlController($url,'',$data,'post');
        $result = $CurlController ->getRequest($url,'',$data,'post');
        dd($token,$url,$result);
    }

    //通过code换取网页授权access_token
    public static  function getWebToken($code){
        $weixin = Config::get('app.WEIXIN');
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$weixin['APPID'].'&secret='.$weixin['APPSECRET'].'&code='.$code.'&grant_type=authorization_code';
        $CurlController = new CurlController($url);
        $result = $CurlController ->getRequest();
        return $result;
    }
}

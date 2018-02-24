<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Feedback;
use App\Intend;
use function GuzzleHttp\Psr7\parse_query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\UserAdmin;
use Illuminate\Support\Facades\Session;
use App\Customer;

class UserController extends Controller
{
    public function index(){

    }
    public function register(){
        return view('register');
    }
    public function registerCompany(){
        return view('registerCompany');
    }
    //投诉建议反馈页面
    public function apiSuggestion(){
        $data = Input::get();
        if(isset($data['code'])){
            $code = $data['code'];
            $result = WeixinController::getWebToken($code);
            $result = json_decode($result,true);
            //var_dump($result);
            return view('suggestion',$result);
        }else{
            return '服务器故障,工程师正在抢修中.......';
        }
    }
    //保存反馈意见数据
    public function saveSuggestion(){
        $data = Input::get();
        $result = Feedback::create($data);
        if($result) return 1;
        else return 0;

    }
    //意向用户数据保存
    public function saveIntent()
    {
        $data = Input::get();
        parse_str($data['intentions'],$data['intentions']);
        $data['intentions'] = implode(',',array_values($data['intentions']));
        $result = Intend::create($data);
        if($result) return 1;
        else return 0;
    }
    //主动推送用户信息
    public function giveMsg(){

    }
    //登录检测
    public function checkLogin(){
        $username = Input::get('username','');
        $password = Input::get('password','');
        $Admin_arr = UserAdmin::find(1);
        if($username == $Admin_arr->name && md5(md5($password)) == $Admin_arr->password && $Admin_arr->status == 1)
        {
            Session::put('username',$username);
            Session::put('password',$password);
            //dd(md5(md5($password)));
            return redirect('/');
        }else{
            return redirect('/login');
        }

    }
    //登出
    public function loginOut()
    {
        Session::forget('username');
        Session::forget('password');
        return redirect('/');
    }
    //新建用户
    public function create()
    {
        $o = Input::get('o','');
        $tag = Input::get('tag','');
        $msg = $tag == -1?'新增':'修改';
        $o['created_name'] = Session::get('username');
        //dd($o);
        if(Customer::where('company_name',$o['company_name'])->first() && $tag == -1)
            return json_encode(['code'=>1,'msg'=>$msg.'失败,该客户已存在！']);
        elseif(Customer::where('company_name',$o['company_name'])->where('id','!=',$tag)->first()){
            return json_encode(['code'=>1,'msg'=>$msg.'失败,该客户已存在！']);
        }
        if($tag == -1) $result = Customer::create($o);
        else  {
            unset($o['id']);
            unset($o['created_at']);
            unset($o['updated_at']);
            $result = Customer::where('id',$tag)->update($o);
        } //dd($result,$o);
        if($result) return json_encode(['code'=>0,'msg'=>'客户'.$msg.'成功']);
        else    return  json_encode(['code'=>1,'msg'=>'客户'.$msg.'失败']);
    }
    //获取列表
    public function getCustomerList()
    {
        $limit = Input::get('limit','');
        $offset = Input::get('offset','');
        $sort = Input::get('sort','');
        $order = Input::get('order','');
        $search = Input::get('search','');
        $module =  Customer::select('*');
        //搜索筛选
        if($search)
            $module = $module ->where(function($query) use($search){
                $query ->where('company_name','like','%'.$search.'%')
                    ->orwhere('corporation','like','%'.$search.'%')
                    ->orwhere('cooperate_name','like','%'.$search.'%')
                    ->orwhere('accountant','like','%'.$search.'%');
            });
        //获取rows
        $rows = $module
                ->skip($offset)->take($limit)
                ->orderBy($sort,$order)
                ->get();
        $total = Customer::count();
        foreach($rows as $k=>$v){
            $rows[$k]->corporate_property = $v->corporate_property == 1?'一般纳税人':'小规模';
            $rows[$k]->area = $v->area == 1?'怀柔':($v->area == 2?'密云':($v->area == 3?'朝阳':($v->area == 4?'昌平':($v->area == 5?'自由地址':($v->area ==6?'附加服务':'')))));
            $rows[$k]->address_type = $v->address_type == 1?'一次性':'年续费';
           //计算实际消费金额
            $rows[$k]->monetary = $v->monetary>$v->stored_charge?$v->stored_charge:$v->monetary;
            //计算余额
            $rows[$k] ->balance = $v->balance < 0 ?0:$v->balance;
        }
        $data = [
            'rows'=>$rows,
            'total'=>$total
        ];
        return json_encode($data);
    }
    //获取用户通过id
    public function getCustomerById($id)
    {
       $result =   Customer::find($id);
       //dd($result);
        $result ->corporate_property = (string) $result ->corporate_property;
        $result ->area = (string) $result ->area;
        $result ->address_type = (string) $result ->address_type;
       return $result;
    }
    //获取月份
    public function getMonth($string,$str2 = null){
        //$string = 2017-11-17 00:00:00;
        $string_arr = explode('-',$string);
        $month = $string_arr[1];
        $year = $string_arr[0];
        $nowMonth = $str2?explode('-',$str2)[1]:date('m');
        $nowYear = $str2?explode('-',$str2)[0]:date('Y');
        $month_num = ($nowYear - $year)*12 + ($nowMonth - $month);
        return $month_num ;
    }

    public function saveDate()
    {
        $data = Input::get('data','');
        $result = Bill::create($data);
        //维护主表总充值费用,和余额
        $id = $data["customer_id"];
        $this ->updateCharge($id);
        if($result)
            return json_encode(['code'=>0,'msg'=>'客户续费成功']);
        else
            return json_encode(['code'=>1,'msg'=>'客户续费失败']);
    }
    public function saveOld()
    {
        $data = Input::get('data','');
        $st_time = $data['st_time'];
        $ed_time = $data['ed_time'];
        unset($data['st_time']);
        unset($data['ed_time']);
        //获取时间间隔月份
        $monthNum = $this->getMonth($st_time,$ed_time);
        $bill_time = $this ->getBillTime($st_time,5);

        for($i=0;$i<=$monthNum;$i++){
            $data['bill_time'] = $this ->getBillTime($st_time,$i);
            if(!(Bill::create($data))){
                return json_encode(['code'=>1,'msg'=>'干预历史扣费失败']);
            }
        }
        $id = $data['customer_id'];
        $this ->updateCharge($id);
        return json_encode(['code'=>0,'msg'=>'干预历史扣费成功']);
    }
    //内部方法
    private  function getBillTime($time,$num){
        //2016-12-11，5
        $arr = explode('-',$time);
        $year = $arr[0];
        $month = $arr[1];
        $newMonth = (($month+$num)%12)?(($month+$num)%12):12;
        $newYear = $year + ((($month+$num)%12)?floor(($month+$num)/12):(floor(($month+$num)/12)-1));
        return  $newYear.'-'.$newMonth.'-01';
    }
    //获取账单数据
    public function getBill($id){
        $rows =  Bill::where('customer_id',$id)->get();
        foreach($rows as $k=>$v){
            if($v->type == 1){
                $rows[$k] ->increase =  $v->money;
                $rows[$k] ->decrease =  '';
            }else{
                $rows[$k] ->increase =  '';
                $rows[$k] ->decrease =  $v->money;
            }
        }
        return $rows;
    }
    //更新主表的的金额字段
    public function updateCharge($id)
    {
        $total_charge = Bill::where('customer_id',$id)->where('type',1)->sum('money');
        $sale_charge = Bill::where('customer_id',$id)->where('type',0)->sum('money');
        $last_charge = $total_charge - $sale_charge;
        $data = [
            'total_charge'=>$total_charge,
            'sale_charge' =>$sale_charge,
            'last_charge' =>$last_charge
        ];
        Customer::where('id',$id)->update($data);
    }
    //所有用户进行扣费
    public function delAll()
    {
        $arr1 = [];
        $arr2 = [];
        $obj_arr = Customer::where('one_charge','>=',0)->get(['id','one_charge']);
        foreach($obj_arr as $k=>$v){
            $arr2['customer_id'] = $v->id;
            $arr2['money'] = $v->one_charge;
            $arr2['type'] = 0;
            $arr2['re_mark'] = '记账费用';
            $arr2['bill_time'] = date('Y-m-d',time());
            $arr1[] = $arr2;
        }

        if(DB::table('bill')->insert($arr1)){
            return json_encode(['code'=>0,'msg'=>'批量扣费成功']);
        }
        return json_encode(['code'=>1,'msg'=>'批量扣费失败']);
    }
}

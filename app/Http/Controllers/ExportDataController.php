<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Dept;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Support\Facades\DB;

class ExportDataController extends Controller
{

    /**
     * @return mixed
     */
    function index(){
        $file = fopen('123.csv',"r");
        fgetcsv($file);
        $error = '';
        while(! feof($file))
        {
            $data = fgetcsv($file);
            if(empty($data[1])){
                continue;
            }
            DB::beginTransaction();
            try {
                $customer = new Customer();
                $customer->company_name = $data[1] ;
                $customer->corporation = $data[2];
                $customer->phone = $data[3];
                $corporate_property = config('params.customer.corporate_property');
                $corporate_property = array_flip($corporate_property);

                $customer->corporate_property = $corporate_property[$data[4]] ?? 0;
                $customer->area = 0; // $data[5]; //todo
                $customer->address_type = 1;
                $customer->trade = $data[7];
                $customer->credit_code = $data[8];
                $customer->get_business_time = str_replace("/","-",$data[9]) ?: '1971-01-01';
                $customer->cooperate_time = str_replace("/","-",$data[10]) ?: '1971-01-01';
                $customer->total_charge = $data[13] ?: 0;
                $customer->revenue_time = str_replace("/","-",$data[14]) ?: '1971-01-01';

                $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
                $model1 = User::where('name', $data[11])->first();
                if (empty($model1)){
                    $str='xm'.substr(str_shuffle($str),5,8).'@xiaomiao.com';
                    $data_user['name'] = $data[11]  ?: ''; //销售
                    $data_user['email'] = $str; //产生随机邮箱
                    $data_user['password'] = bcrypt('xiaomiao'); //默认密码xiaomiao
                    $data_user['remember_token'] = str_random(60);
                    $data_user['api_token'] = str_random(60);
                    if($dept = Dept::where('name', 'like', '%'.$data[12].'%')->first()){
                        $data_user['dept_id'] = $dept->id;
                    } else {
                        $data_user['dept_id'] = 0;
                    }
                    $model1 = User::create($data_user);
                }

                $model2 = User::where('name', $data[15])->first();
                if (empty($model2)){
                    $str='xm'.substr(str_shuffle($str),5,8).'@xiaomiao.com';
                    $data_user2['name'] = $data[15] ?: ''; //会计
                    $data_user2['email'] = $str; //产生随机邮箱
                    $data_user2['password'] = bcrypt('xiaomiao'); //默认密码xiaomiao
                    $data_user2['remember_token'] = str_random(60);
                    $data_user2['api_token'] = str_random(60);
                    $data_user2['dept_id'] = 1; //会计
                    $model2 = User::create($data_user2);
                }
                $customer->account_id = $model2->id;
                $customer->sale_id = $model1->id;
                $customer->sale_charge = $data[16] ?: 0;
                $customer->last_charge = $data[17] ?: 0;
                $customer->one_charge = $data[18] ?: 0;
                $customer->save();
                DB::commit();
            } catch (\Exception $e) {
                $error .= $data[1] .'数据异常，不符合规范<br/>';
                DB::rollBack();
            }
        }
        fclose($file);
        $error = $error ?: '成功';
        return $error;
    }
}

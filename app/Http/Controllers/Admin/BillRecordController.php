<?php

namespace App\Http\Controllers\Admin;

use App\BillRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillRecordController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function getLists(Request $request)
    {
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $name = $request->input('name', '');
        $status = $request->input('status', '');
        $customer_id = $request->input('customer_id', '');
        $where = [];
        if(!empty($name)) {
            $where[] = ['customers.company_name', 'like', '%'.$name.'%'];
        }
        if(!empty($customer_id)) {
            $where[] = ['customer_id', '=', $customer_id];
        }
        if(strlen($status) != 0) {
            $where[] = ['type', '=', $status];
        }
        $curpage = ($offset / $limit) + 1;
        $res = BillRecord::join('customers',function($join){
            $join->on('bill_records.customer_id','=','customers.id');
        })->select('bill_records.*','customers.company_name')
         ->where($where)->orderby($sort, $order)->paginate($limit, ['*'], 'page', $curpage);
        $total = $res->total();
        $rows = $res->items();
        $response = [
            'total' => $total,
            'rows' => $rows
        ];
        return json_encode($response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = config('params.bill_recode_type');
        $tmp_id = $request->has('id') ? $request->input('id') :  '';
        return view('admin.billrecords.index',['tmp_id'=>$tmp_id, 'type'=>$type]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_id' => 'required',
            'money' => 'required',
            'type' => 'required',
        ]);
        $data = $request->all();
        $model = BillRecord::create($data);

        //todo 新建记录  要忘主账单充值或者扣费
        return response($model);
    }
}

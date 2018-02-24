<?php

namespace App\Http\Controllers\Admin;

use App\Customer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
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
        $corporation = $request->input('corporation', '');
        $status = $request->input('status', '');
        $where = [];
        if(!empty($name)) {
            $where[] = ['company_name', 'like', '%'.$name.'%'];
        }
        if(!empty($corporation)) {
            $where[] = ['corporation', 'like', '%'.$corporation.'%'];
        }
        if(strlen($status) != 0) {
            $where[] = ['status', '=', $status];
        }
        $curpage = ($offset / $limit) + 1;
        $res = Customer::where($where)->orderby($sort, $order)->paginate($limit, ['*'], 'page', $curpage);
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
    public function index()
    {
        $data['corporate_property'] = config('params.customer.corporate_property');
        $data['area'] = config('params.customer.area');
        $data['address_type'] = config('params.customer.address_type');
        $data['user'] = User::pluck('name', 'id');
        return view('admin.customers.index', $data);
    }

    /**
     * Display the specified resource.
     * @param Customer $customer
     * @return mixed
     */
    public function show(Customer $customer)
    {
        $customer->corporate_property = strval($customer->corporate_property);
        $customer->area = strval($customer->area);
        $customer->address_type = strval($customer->address_type);
        $customer->account_id = strval($customer->account_id);
        $customer->sale_id = strval($customer->sale_id);
        return $customer;
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
            'company_name' => 'required|unique:customers,company_name',
            'phone' => 'required|regex:/^1[34578][0-9]{9}$/|unique:customers,phone',
        ]);
        $data = $request->all();
        $id = auth()->user()->id;
        $data['created_by'] = $id;
        $data['updated_by'] = $id;
        $model = Customer::create($data);
        return response($model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $this->validate($request, [
            'company_name' => 'required|unique:customers,company_name,'.$customer->id,
            'phone' => 'required|regex:/^1[34578][0-9]{9}$/|unique:customers,phone,'.$customer->id,
        ]);
        $model = $customer;
        $data = $request->all();
        $data['updated_by'] = auth()->user()->id;
        $model->update($data);
        return response($model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request, Customer $customer)
    {
        $model = $customer;
        $status = $request->input('status', 0) ;
        $model->updated_by = auth()->user()->id;
        $model->status = $status ? 0 : 1 ;
        $model->save();
        return response()->json(['errorCode' => '0', 'errorMsg' => 'success']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Dept;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeptController extends Controller
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
        $where = [];
        if(!empty($name)) {
            $where[] = ['name', 'like', '%'.$name.'%'];
        }
        if(strlen($status) != 0) {
            $where[] = ['status', '=', $status];
        }
        $curpage = ($offset / $limit) + 1;
        $res = Dept::where($where)->orderby($sort, $order)->paginate($limit, ['*'], 'page', $curpage);
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
        return view('admin.depts.index');
    }

    /**
     * Display the specified resource.
     * @param Dept $dept
     * @return mixed
     */
    public function show(Dept $dept)
    {
        return $dept;
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
            'name' => 'required|max:15|unique:depts,name',
        ]);
        $data = $request->all();
        $id = auth()->user()->id;
        $data['created_by'] = $id;
        $data['updated_by'] = $id;
        $model = Dept::create($data);
        return response($model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Dept $dept
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dept $dept)
    {
        $this->validate($request, [
            'name' => 'required|max:10|unique:depts,name,'.$dept->id,
        ]);
        $model = $dept;
        $data = $request->all();
        $data['updated_by'] = auth()->user()->id;
        $model->update($data);
        return response($model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Dept $dept
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request, Dept $dept)
    {
        $model = $dept;
        $status = $request->input('status', 0) ;
        $model->updated_by = auth()->user()->id;
        $model->status = $status ? 0 : 1 ;
        $model->save();
        return response()->json(['errorCode' => '0', 'errorMsg' => 'success']);
    }
}
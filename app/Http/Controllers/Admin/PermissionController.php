<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
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
        $res = Permission ::where($where)->orderby($sort, $order)->paginate($limit, ['*'], 'page', $curpage);
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
        return view('admin.permissions.index');
    }

    /**
     * Display the specified resource.
     * @param Permission $permission
     * @return mixed
     */
    public function show(Permission $permission)
    {
        return $permission;
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
            'name' => 'required|max:10|unique:permissions,name',
        ]);
        $model = Permission::create($request->all());
        return response($model);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', ['id'=>$permission->id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission  $permission)
    {
        $this->validate($request, [
            'name' => 'required|max:10|unique:permissions,name,'.$permission->id,
        ]);
        $model = $permission;
        $model->update($request->all());
        return response($model);
    }

    /**
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Permission $permission)
    {
        // 正常删除
        $permission->delete();
        // 强制删除
        //5.5有问题 后续再处理 todo
        $permission->roles()->sync([]); // 删除关联数据
        return response()->json(['errorCode' => '0', 'errorMsg' => 'success']);
    }
}

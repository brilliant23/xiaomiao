<?php

namespace App\Http\Controllers\Admin;

use App\Dept;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminUserController extends Controller
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

        $res = User::with('roles')->where($where)->orderby($sort, $order)->paginate($limit, ['*'], 'page', $curpage);
        $total = $res->total();
        $rows = $res->items();
        $depts = Dept::pluck('name', 'id');
        foreach ($rows as $k=>$v) {
            $rows[$k]->dept_name = isset($depts[$v->dept_id]) ? $depts[$v->dept_id] : '' ;
        }
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
        return view('admin.users.index');
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
            'name' => 'required|max:10|unique:users,name',
            'email' => 'required|email|max:30|unique:users,email',
            'phone' => 'required|regex:/^1[34578][0-9]{9}$/|unique:users,phone',
            'dept_id' => 'required',
        ]);
        $model = new User();
        $model->name = $request->input('name', '');
        $model->email = $request->input('email', '');
        $model->password  = bcrypt($model->email . '@'); //默认密码邮箱加上一个@符
        $model->remember_token = str_random(10);
        $model->save();
        $model->roles()->sync($request->input('role', []));
        return response($model);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(User $user)
    {
        $model = $user;
        $model->password = bcrypt($model->email .'@123');
        $model->save();
        return response()->json(['errorCode' => '0', 'errorMsg' => 'success']);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(Request $request, User $user)
    {
        $model = $user;
        $status = $request->input('status', 0) ;
        $model->status = $status ? 0 : 1 ;
        $model->save();
        return response()->json(['errorCode' => '0', 'errorMsg' => 'success']);
    }


    /**
     * Display the specified resource.
     * @param User $user
     * @return mixed
     */
    public function show(User $user)
    {
        $tmp = $user->roles;
        $role = [];
        foreach ($tmp as $v) {
            $role[] = strval($v->id);
        }
        unset($user->roles);
        $user->role =$role;
        $user->dept_id = strval($user->dept_id);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'phone' => 'required|regex:/^1[34578][0-9]{9}$/|unique:users,phone,'.$user->id,
            'dept_id' => 'required',
        ]);
        $model = $user;
        $data = $request->all();
        $data['updated_by'] = auth()->user()->id;
        $model->update($data);
        $model->roles()->sync($request->input('role', []));
        return response($model);
    }
}

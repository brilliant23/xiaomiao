<?php

namespace App\Http\Controllers\Admin;

use App\Intend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplyController extends Controller
{
    //获取类别
    public function getLists(Request $request)
    {
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $name = $request->input('name', '');
        $status = $request->input('status', '');
        $where = [];
        if (!empty($name)) {
            $where[] = ['name', 'like', '%' . $name . '%'];
        }
        if (strlen($status) != 0) {
            $where[] = ['status', '=', $status];
        }
        $curpage = ($offset / $limit) + 1;
        $res = Intend::where($where)->orderby($sort, $order)->paginate($limit, ['*'], 'page', $curpage);
        $total = $res->total();
        $rows = $res->items();
        foreach ($rows as $k=>$v) {
            if (strlen($v['$name']) > 30 ) {
                $rows[$k]['content_small'] = mb_substr($v['name'], 0, 10) . '……';
            } else {
                $rows[$k]['content_small'] = $v['name'];
            }
        }
        $response = [
            'total' => $total,
            'rows' => $rows
        ];
        return json_encode($response);
    }

    public function index(){
        return view('admin.apply.index');
    }
}

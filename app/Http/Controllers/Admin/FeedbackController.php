<?php

namespace App\Http\Controllers\Admin;

use App\Customer;
use App\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
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
        $customer_content = $request->input('customer_content', '');
        $status = $request->input('status', '');
        $where = [];
        if (!empty($customer_content)) {
            $where[] = ['customer_content', 'like', '%' . $customer_content . '%'];
        }
        if (strlen($status) != 0) {
            $where[] = ['status', '=', $status];
        }
        $curpage = ($offset / $limit) + 1;
        $res = Feedback::where($where)->orderby($sort, $order)->paginate($limit, ['*'], 'page', $curpage);
        $total = $res->total();
        $rows = $res->items();
        $persons = Customer::pluck('corporation', 'id');
        foreach ($rows as $k=>$v) {
            $rows[$k]->customer_name = isset($persons[$v->customer_id]) ? $persons[$v->customer_id] : '' ;
            if (strlen($v['customer_content']) > 30 ) {
                $rows[$k]['content_small'] = mb_substr($v['customer_content'], 0, 10) . '……';
            } else {
                $rows[$k]['content_small'] = $v['customer_content'];
            }
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
        return view('admin.feedbacks.index');
    }

    /**
     * Display the specified resource.
     * @param Feedback $feedback
     * @return mixed
     */
    public function show(Feedback $feedback)
    {

        $persons = Customer::pluck('corporation', 'id');
        $feedback->customer_name = isset($persons[$feedback->customer_id]) ? $persons[$feedback->customer_id] : '' ;
        $feedback->reply_name = isset($persons[$feedback->reply_id]) ? $persons[$feedback->reply_id] : '' ;
        return $feedback;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_content' => 'required|max:255',
        ]);
        $data = $request->all();
        $id = auth()->user()->id;
        $data['customer_id'] = $id;
        $model = Feedback::create($data);
        return response($model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Feedback $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feedback $feedback)
    {
        $this->validate($request, [
            'reply_content' => 'required|max:255',
        ]);
        $model = $feedback;
        $data = $request->all();
        $data['reply_id'] = auth()->user()->id;
        $data['status'] = 2;
        $model->update($data);
        return response($model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Feedback $feedback
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request, Feedback $feedback)
    {
        $model = $feedback;
        $status = $request->input('status', 0);
        $model->reply_id = auth()->user()->id;
        $model->status = $status ? 0 : 1;
        $model->save();
        return response()->json(['errorCode' => '0', 'errorMsg' => 'success']);
    }
}
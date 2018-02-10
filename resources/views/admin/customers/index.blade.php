@extends('adminlte::page')

@section('title', '客户管理')

@section('content_header')
    <h1>客户管理</h1>
    <ol class="breadcrumb">
        <li class="active">客户管理</li>
    </ol>
@stop

@section('content')
<div ng-app="myModule" ng-controller="myController">
    <div class="container-fluid spark-screen" >
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    客户管理
                    <button type="button" class="btn btn-info" style="margin: -6px;float: right;" ng-click="toggle('add')">
                        <i class="glyphicon glyphicon-plus"></i> 新建
                    </button>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <form class="form-inline">
                            <div class="form-group">
                                <label for="search-name">名称</label>
                                <input type="text" class="form-control"
                                       id="search-name" placeholder="名称" name="search-name">
                            </div>
                            <div class="form-group">
                                <label for="search-status">状态</label>
                                <select class="form-control" id="search-status" name="search-status">
                                    <option value="">请选择</option>
                                    <option value="0">禁用</option>
                                    <option value="1">启用</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" ng-click="btnquery()">查询</button>
                                <input type="reset" class="btn btn-default" />
                            </div>
                        </form>
                        <hr/>
                        <table bs-table-control="bsTableControl" id="table"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" id="myModal" aria-labelledby="exampleModalLabel" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="exampleModalLabel">@{{ form_title }}</h4>
                </div>
                <div class="modal-body">
                    <form name="myForm" id="form1" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.company_name.$pristine && myForm.company_name.$invalid }">
                                    <label for="company_name" class="control-label">公司名称:</label>
                                    <input type="text" class="form-control" name="company_name" required ng-model="customer.company_name">
                                    <p ng-show="!myForm.company_name.$pristine && myForm.company_name.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.corporation.$pristine && myForm.corporation.$invalid }">
                                    <label for="corporation" class="control-label">公司法人:</label>
                                    <input type="text" class="form-control" name="corporation" required ng-model="customer.corporation">
                                    <p ng-show="!myForm.corporation.$pristine && myForm.corporation.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.phone.$pristine && myForm.phone.$invalid }">
                                    <label for="phone" class="control-label">手机号:</label>
                                    <input type="text" class="form-control" name="phone" required ng-model="customer.phone">
                                    <p ng-show="!myForm.phone.$pristine && myForm.phone.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.corporate_property.$pristine && myForm.corporate_property.$invalid }">
                                    <label for="corporate_property" class="control-label">企业性质:</label>
                                    <select class="form-control" name="corporate_property" required ng-model="customer.corporate_property"
                                            ng-options="key as value for (key ,value) in p1s">
                                        <option disabled></option>
                                    </select>
                                    <p ng-show="!myForm.corporate_property.$pristine && myForm.corporate_property.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.area.$pristine && myForm.area.$invalid }">
                                    <label for="area" class="control-label">注册地区:</label>
                                    <select class="form-control" name="area" required ng-model="customer.area"
                                            ng-options="key as value for (key ,value) in p2s">
                                        <option disabled></option>
                                    </select>
                                    <p ng-show="!myForm.area.$pristine && myForm.area.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.address_type.$pristine && myForm.address_type.$invalid }">
                                    <label for="address_type" class="control-label">地址类型:</label>
                                    <select class="form-control" name="address_type" required ng-model="customer.address_type"
                                            ng-options="key as value for (key ,value) in p3s">
                                        <option disabled></option>
                                    </select>
                                    <p ng-show="!myForm.address_type.$pristine && myForm.address_type.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.trade.$pristine && myForm.trade.$invalid }">
                                    <label for="trade" class="control-label">所属行业:</label>
                                    <input type="text" class="form-control" name="trade" required ng-model="customer.trade">
                                    <p ng-show="!myForm.trade.$pristine && myForm.trade.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.credit_code.$pristine && myForm.credit_code.$invalid }">
                                    <label for="credit_code" class="control-label">信用编码:</label>
                                    <input type="text" class="form-control" name="credit_code" required ng-model="customer.credit_code">
                                    <p ng-show="!myForm.credit_code.$pristine && myForm.credit_code.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.cooperate_time.$pristine && myForm.cooperate_time.$invalid }">
                                    <label for="cooperate_time" class="control-label">合作时间:</label>
                                    <input type="text" class="form-control" name="cooperate_time" id="cooperate_time"
                                           required ng-model="customer.cooperate_time">
                                    <p ng-show="!myForm.cooperate_time.$pristine && myForm.cooperate_time.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.get_business_time.$pristine && myForm.get_business_time.$invalid }">
                                    <label for="get_business_time" class="control-label">下照日期:</label>
                                    <input type="text" class="form-control" name="get_business_time" id="get_business_time"
                                           required ng-model="customer.get_business_time">
                                    <p ng-show="!myForm.get_business_time.$pristine && myForm.get_business_time.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.revenue_time.$pristine && myForm.revenue_time.$invalid }">
                                    <label for="revenue_time" class="control-label">税务报道日期:</label>
                                    <input type="text" class="form-control" name="revenue_time" id="revenue_time"
                                           required ng-model="customer.revenue_time">
                                    <p ng-show="!myForm.revenue_time.$pristine && myForm.revenue_time.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.account_id.$pristine && myForm.account_id.$invalid }">
                                    <label for="account_id" class="control-label">负责会计人员:</label>
                                    <select chosen class="form-control" name="account_id" required
                                            data-placeholder-text-single="'选择负责会计'"
                                            no-results-text="'没有找到'" ng-model="customer.account_id"
                                            ng-options="key as value for (key ,value) in account_ids">
                                        <option disabled></option>
                                    </select>
                                    <p ng-show="!myForm.account_id.$pristine && myForm.account_id.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" ng-class="{ 'has-error' : !myForm.sale_id.$pristine && myForm.sale_id.$invalid }">
                                    <label for="sale_id" class="control-label">合作销售:</label>
                                    <select chosen class="form-control" name="sale_id" required
                                            data-placeholder-text-single="'选择合作销售'"
                                            no-results-text="'没有找到'" ng-model="customer.sale_id"
                                            ng-options="key as value for (key ,value) in sale_ids">
                                        <option disabled></option>
                                    </select>
                                    <p ng-show="!myForm.sale_id.$pristine && myForm.sale_id.$invalid" class="help-block">不能为空</p>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary" form="form1" ng-disabled="myForm.$invalid"
                            ng-click="save(modalstate, id)">保存
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('vendor/laydate/laydate.js') }}"></script>
    <script>
        $p1 = <?php echo json_encode($corporate_property)?>;
        $p2 = <?php echo json_encode($area)?>;
        $p3 = <?php echo json_encode($address_type)?>;
        $token = "<?php echo (\Auth::user()->api_token); ?>";

        $(function () {
            laydate.render({
                elem: '#cooperate_time',
                type: 'datetime'
            });
            laydate.render({
                elem: '#get_business_time',
                type: 'datetime'
            });
            laydate.render({
                elem: '#revenue_time',
                type: 'datetime'
            });
        })
        //bootstraptable 过渡到ng-click函数
        function ngclick(row, index, value) {
            return '<a href="" ng-click="$parent.toggle( \'edit\', ' + index.id + ')" ' +
                'class="btn btn-default">修改</a>';
        }

        angular.module('myModule', ['bsTable'])
            .controller('myController', function ($scope, $http) {
                $scope.p1s = $p1;
                $scope.p2s = $p2;
                $scope.p3s = $p3;
                //初始化表格
                $scope.bsTableControl = {
                    options: {
                        striped: true,                      //是否显示行间隔色
                        cache: false,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
                        pagination: true,                   //是否显示分页（*）
                        sortName: 'id',
                        sortOrder: 'desc',
                        onSort: function (name, order) {
                            this.sortName = name;
                            this.sortOrder = order;
                        },
                        queryParams: function (params) { //传递参数（*）
                            return {   //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
                                //search:params.search 自带的搜索框 我把自带的先隐藏了，
                                limit: params.limit,   //页面大小
                                offset: params.offset,  //页码
                                order: params.order,
                                sort: params.sort,
                                name: $("#search-name").val(),
                                status: $("#search-status").val()
                                //请求服务器数据时，你可以通过重写参数的方式添加一些额外的参数，例如 toolbar 中的参数 如果 queryParamsType = 'limit' ,返回参数必须包含
                                //limit, offset, search, sort, order 否则, 需要包含: pageSize, pageNumber, searchText, sortName, sortOrder. 返回false将会终止请求
                            };
                        },
                        sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
                        pageNumber: 1,                       //初始化加载第一页，默认第一页
                        pageSize:"10",      //每页的记录行数（*）读取数据库配置
                        pageList: [10, 25, 50, 100],        //可供选择的每页的行数（*）
                        clickToSelect: true,                //是否启用点击选中行
                        uniqueId: "id",                     //每一行的唯一标识，一般为主键列
                        url: "{{route('customer.lists')}}",
                        columns: [{
                            field: 'id',
                            title: 'ID',
                            valign: 'middle',
                            sortable: true
                        }, {
                            field: 'company_name',
                            title: '公司名称',
                            valign: 'middle',
                            formatter: function (value, row, index) {
                                return '<a target="_blank" href="/billrecord?id='+row.id+'">'+value+'</a>';
                            }
                        }, {
                            field: 'corporation',
                            title: '法人',
                            valign: 'middle'
                        }, {
                            field: 'phone',
                            title: '手机号',
                            valign: 'middle',
                            sortable: true
                        }, {
                            field: 'corporate_property',
                            title: '企业性质',
                            valign: 'middle',
                            formatter: function (value, row, index) {
                                return $p1[value];
                            }
                        }, {
                            field: 'area',
                            title: '注册地区',
                            valign: 'middle',
                            formatter: function (value, row, index) {
                                return $p2[value];
                            }
                        }, {
                            field: 'address_type',
                            title: '类型',
                            valign: 'middle',
                            formatter: function (value, row, index) {
                                return $p3[value];
                            }
                        }, {
                            field: 'trade',
                            title: '所属行业',
                            valign: 'middle'
                        }, {
                            field: 'account_id',
                            title: '负责会计人员',
                            valign: 'middle'
                        }, {
                            field: 'sale_id',
                            title: '合作销售',
                            valign: 'middle'
                        }, {
                            field: 'total_charge',
                            title: '金额（元）',
                            valign: 'middle',
                            formatter: function (value, row, index) {
                                return row.total_charge +' / '+
                                row.sale_charge +' / '+
                                row.last_charge +' / '+
                                row.one_charge;
                            }
                        }, {
                            field: 'cooperate_time',
                            title: '合作时间',
                            valign: 'middle'
                        }, {
                            field: 'status',
                            title: '操作',
                            align: 'center',
                            valign: 'middle',
                            formatter: ngclick
                        }]
                    }
                };

                //搜索按钮事件
                $scope.btnquery = function () {
                    $("#table").bootstrapTable('refresh', {url: "{{route('customer.lists')}}"});
                    //主要解决在不是第一页搜索的情况下 如在第二页搜索搜索不到数据，但其实第一页是有数据的
                };
                //回车搜索事件
                $('#search-name').keypress(function(event){
                    if(event.keyCode == "13")
                        $scope.btnquery();
                });

                //添加和修改按钮ng-click触发函数
                $scope.toggle = function (modalstate, id) {
                    //避免下次第二次add时候直接输入框是红色警告的
                    $scope.myForm.$setPristine();

                    $scope.modalstate = modalstate;
                    switch (modalstate) {
                        case 'add':
                            $scope.form_title = "新建";
                            $scope.customer = {};
                            break;
                        case 'edit':
                            $scope.form_title = "修改--" + id;
                            $scope.id = id;
                            $http.get('/customer/' + id)
                                .then(function successCallback(response) {
                                    $scope.customer = response.data;
                                });
                            break;
                        default:
                            break;
                    }

                    $http.get('api/users?api_token='+ $token)
                        .then(function successCallback(response) {
                            $scope.account_ids = response.data.account;
                            $scope.sale_ids = response.data.sale;
                        });
                    $('#myModal').modal('show');
                };

                //添加和修改保存记录
                $scope.save = function (modalstate, id) {
                    var url = "{{route('customer.store')}}";
                    var dataparam = $.param($scope.customer);
                    if (modalstate === 'edit') {
                        url += '/' + id;
                        dataparam += '&_method=put';
                    }
                    $http({
                        url: url,
                        method: "POST",
                        data: dataparam,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function successCallback(response) {
                        swal("成功", '', "success", {timer: 800});
                        $('#myModal').modal('hide');
                        if (modalstate === 'edit') { //修改时刷新当前页
                            $("#table").bootstrapTable('refresh');
                        } else { //添加时刷新返回的第一页
                            $("#table").bootstrapTable('refresh', {url: "{{route('customer.lists')}}"});
                        }
                    }, function errorCallback(response) {
                        var errorMsg = '';
                        $.each(response.data.errors, function(i,val){
                            errorMsg += val + "\n";
                        });
                        swal("错误", errorMsg, "error", {timer: 2000});
                    });
                };
            });
    </script>
@stop
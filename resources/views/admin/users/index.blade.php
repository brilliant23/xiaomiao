{{-- resources/views/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', '后台用户管理')

@section('content_header')
    <h1>后台用户管理</h1>
    <ol class="breadcrumb">
        <li class="active">后台用户管理</li>
    </ol>
@stop

@section('content')
    <div ng-app="myModule" ng-controller="myController">
        <div class="container-fluid spark-screen" >
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        后台用户管理
                        <button type="button" class="btn btn-info" style="margin: -6px;float: right;" ng-click="toggle('add')">
                            <i class="glyphicon glyphicon-plus"></i> 新建
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="container-fluid">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label for="search-name">名称</label>
                                    <input type="text" class="form-control" id="search-name" placeholder="名称" name="search-name">
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

        <div class="modal fade" id="myModal" aria-labelledby="exampleModalLabel" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">@{{ form_title }}</h4>
                    </div>
                    <div class="modal-body">
                        <form name="myForm" id="form1" novalidate>
                            <div class="form-group" ng-class="{ 'has-error' : !myForm.name.$pristine && myForm.name.$invalid }">
                                <label for="name" class="control-label">昵称:</label>
                                <input type="text" class="form-control" name="name" required ng-model="user.name">
                                <p ng-show="!myForm.name.$pristine && myForm.name.$invalid" class="help-block">不能为空</p>
                            </div>
                            <div class="form-group" ng-class="{ 'has-error' : !myForm.email.$pristine && myForm.email.$invalid }">
                                <label for="email" class="control-label">邮箱:</label>
                                <input type="email" class="form-control" name="email" ng-disabled="modify" ng-model="user.email" required/>
                                <p ng-show="!myForm.email.$pristine && myForm.email.$invalid" class="help-block">不能为空</p>
                            </div>
                            <div class="form-group" ng-class="{ 'has-error' : !myForm.phone.$pristine && myForm.phone.$invalid }">
                                <label for="phone" class="control-label">手机:</label>
                                <input type="text" class="form-control" name="phone" ng-model="user.phone" required/>
                                <p ng-show="!myForm.phone.$pristine && myForm.phone.$invalid" class="help-block">不能为空</p>
                            </div>
                            <div class="form-group" ng-class="{ 'has-error' : !myForm.dept_id.$pristine && myForm.dept_id.$invalid }">
                                <label for="dept" class="control-label">部门:</label>
                                <select chosen class="form-control" name="dept_id" required
                                    data-placeholder-text-single="'选择部门'"
                                    no-results-text="'没有找到'" ng-model="user.dept_id"
                                    ng-options="key as value for (key ,value) in depts">
                                    <option disabled></option>
                                </select>
                                <p ng-show="!myForm.dept_id.$pristine && myForm.dept_id.$invalid" class="help-block">不能为空</p>
                            </div>
                            <div class="form-group">
                                <label for="roles" class="control-label">角色:</label>
                                <select chosen class="form-control" multiple
                                    data-placeholder-text-single="'选择角'"
                                    no-results-text="'没有找到'" ng-model="user.role"
                                    ng-options="key as value for (key ,value) in roles">
                                </select>
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
    <script>
        $token = "<?php echo (\Auth::user()->api_token); ?>";
        //bootstraptable 过渡到ng-click函数
        function ngclick(row, index, value) {
            var m = '<a href="" ng-click="$parent.reset( ' + index.id + ')" class="btn btn-default">重置密码</a> ';
            var f = '<a href="" ng-click="$parent.toggle(\'edit\',' + index.id + ')" class="btn btn-default">修改用户</a>';
            var e = row == 1 ?
                '<button class="btn btn-default" type="button" ng-click="$parent.disableditem(' + row + ',' + index.id + ')">禁用</button> ' :
                '<button class="btn btn-default" type="button" ng-click="$parent.disableditem(' + row + ',' + index.id + ')">启用</button> ';
            return e + m + f;
        }

        angular.module('myModule', ['bsTable', 'localytics.directives'])
            .controller('myController', function ($scope, $http) {
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
                        pageSize: '10',      //每页的记录行数（*）读取数据库配置
                        pageList: [10, 25, 50, 100],        //可供选择的每页的行数（*）
                        clickToSelect: true,                //是否启用点击选中行
                        uniqueId: "id",                     //每一行的唯一标识，一般为主键列
                        url: "{{route('user.lists')}}",
                        columns: [{
                                field: 'id',
                                title: 'ID',
                                valign: 'middle',
                                sortable: true
                            },
                            {
                                field: 'name',
                                title: '名称',
                                valign: 'middle',
                                sortable: true
                            }, {
                                field: 'email',
                                title: 'Email',
                                valign: 'middle'
                            }, {
                                field: 'dept_name',
                                title: '部门',
                                valign: 'middle'
                            }, {
                                field: '',
                                title: '角色',
                                valign: 'middle',
                                formatter: function (value, row, index) {
                                    var perms = '';
                                    for ( x in row.roles) {
                                        perms += '<span class="label label-default">'+ row.roles[x].display_name +'</span> ';
                                    }
                                    return perms;
                                }
                            }, {
                                field: 'status',
                                title: '状态',
                                valign: 'middle',
                                formatter: function (value, row, index) {
                                    return value == 1 ?
                                        '<span class="label label-success">正常</span>'
                                        : '<span class="label label-danger">禁用</span>';
                                }
                            }, {
                                field: 'created_at',
                                title: '创建时间',
                                valign: 'middle',
                                sortable: true
                            }, {
                                field: 'updated_at',
                                title: '更新时间',
                                valign: 'middle',
                                sortable: true
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
                    $("#table").bootstrapTable('refresh', {url: "{{route('user.lists')}}"});
                    //主要解决在不是第一页搜索的情况下 如在第二页搜索搜索不到数据，但其实第一页是有数据的
                };
                //回车搜索事件
                $('#search-name').keypress(function(event){
                    if(event.keyCode == "13")
                        $scope.btnquery();
                });
                //禁用按钮事件
                $scope.disableditem = function (status, id) {
                    swal({
                        title: "确定此操作吗？",
                        text: "",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '/user/' + id,
                                type: 'POST',
                                data: {
                                    status: status,
                                    _token: '{{ csrf_token() }}'
                                },
                                dataType: 'json',
                                success: function (data, textStatus, jqXHR) {
                                    swal("成功!", "", "success", {timer: 800});
                                    $("#table").bootstrapTable('refresh');
                                },
                                error: function (xhr, textStatus) {
                                    swal("错误!", "", "error", {timer: 2000});
                                }
                            })
                        } else {
                            swal("你已经取消!", "", "success", {timer: 800});
                        }
                    });
                };


                //重置密码按钮事件
                $scope.reset = function (id) {
                    swal({
                        title: "确定此操作吗？",
                        text: "确定要重置密码操作吗？默认密码：邮箱+'@123'",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '/user-reset/' + id,
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                dataType: 'json',
                                success: function (data, textStatus, jqXHR) {
                                    swal("成功!", "", "success", {timer: 800});
                                    $("#table").bootstrapTable('refresh');
                                },
                                error: function (xhr, textStatus) {
                                    swal("错误!", "", "error", {timer: 2000});
                                }
                            })
                        } else {
                            swal("你已经取消!", "", "success", {timer: 800});
                        }
                    });
                };

                //添加按钮ng-click触发函数
                $scope.toggle = function (modalstate, id) {
                    //避免下次第二次add时候直接输入框是红色警告的
                    $scope.myForm.$setPristine();

                    $scope.modalstate = modalstate;
                    switch (modalstate) {
                        case 'add':
                            $scope.form_title = "新建";
                            $scope.modify = false;
                            $scope.user = {};
                            break;
                        case 'edit':
                            $scope.form_title = "修改--" + id;
                            $scope.modify = true;
                            $scope.id = id;
                            $http.get('/user/' + id)
                                .then(function successCallback(response) {
                                    $scope.user = response.data;
                                });
                            break;
                        default:
                            break;
                    }
                    $http.get('api/roles?api_token='+ $token)
                        .then(function successCallback(response) {
                            $scope.roles = response.data;
                        });
                    $http.get('api/depts?api_token='+ $token)
                        .then(function successCallback(response) {
                            $scope.depts = response.data;
                        });
                    $('#myModal').modal('show');
                };

                //添加和修改保存记录
                $scope.save = function (modalstate, id) {
                    var url = "{{route('user.store')}}";
                    var dataparam = $.param($scope.user);
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
                            $("#table").bootstrapTable('refresh', {url: "{{route('user.lists')}}"});
                        }
                    }, function errorCallback(response) {
                        var errorMsg = '';
                        console.log(response)
                        $.each(response.data.errors, function(i,val){
                            errorMsg += val + "\n";
                        });
                        swal("错误", errorMsg, "error", {timer: 2000});
                    });
                };
            });
    </script>
@stop

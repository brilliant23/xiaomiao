
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title> - 用户注册</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
    <link href="css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="https://cdn.bootcss.com/animate.css/3.5.2/animate.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
        if (window.top !== window.self) {
            window.top.location = window.location;
        }
    </script>

</head>

<body class="signin" ng-app="myApp" ng-controller="myCtrl">
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-12">
            <form name = "myForm" method="post" >
                <h4 class="no-margins">注册：</h4>
                <p class="m-t-md">欢迎注册小苗科技运营管理平台</p>
                {{ csrf_field() }}
                <input type="text" class="form-control uname" placeholder="用户名" ng-model="name" ng-change="getName()" required/>
                <input type="text" class="form-control uname" placeholder="真实名称" ng-model="real_name" required/>
                <input type="password" class="form-control pword m-b" placeholder="密码"  ng-model="password" required/>
                <input type="password" class="form-control pword m-b" placeholder="确认密码"  ng-model="rePassword" required/>
                <a href="/login">直接登录!</a>
                <span style="color:red;" ng-model="errorMsg" ng-bind="errorMsg"></span>
                <button type="button" class="btn btn-success btn-block" ng-disabled="myForm.$invalid" ng-click="register()">注册</button>
            </form>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">
            &copy; hAdmin
        </div>
    </div>
</div>
{{HTML::script('js/angular.min.js?v=1.4.6')}}
<script>
    var myapp = angular.module('myApp',[]);
    myapp.controller('myCtrl',function($scope,$http){
        $scope.errorMsg  = '';
        $scope.checkLogin = function(){
            console.log($scope.username,$scope.password);
        };
        //检查用户名是否重复
        $scope.getName = function(){
            $http.get('/checkName?name='+$scope.name).success(function(o){

            });
        }
        //创建用户
        $scope.register = function(){
            //验证前后密码一致
            if($scope.password != $scope.rePassword){
                $scope.errorMsg = '前后密码不一致！';
            }else  $scope.errorMsg = '';

            //验证通过后，插入数据
            $http.get('/insert?name='+$scope.name+'&real_name='+$scope.real_name+'&password='+$scope.password).success(function(o){

            });
        }
    });

</script>

</body>

</html>

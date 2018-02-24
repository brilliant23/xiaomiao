<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>小苗会计业务中心</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!--标准mui.css-->
    <link rel="stylesheet" href="/css/mui.min.css">
    <!--App自定义的css-->
    <!--<link rel="stylesheet" type="text/css" href="../css/app.css"/>-->
</head>
<body>
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">小苗业务中心，欢迎您！</h1>
</header>
<div class="mui-content">
    <div class="mui-content-padded" style="margin: 5px;">
        <h5 class="mui-content-padded">小苗会计为中小企业提供专业工商注册、代理记账、社保（五险一金）代理、建站设计等服务</h5>
        <br>
        <h5 class="mui-content-padded">依靠专业的技术和服务理念，小苗一年内获得上千客户认可和信赖！</h5>
        <br>
        <h5 class="mui-content-padded" style="font-size:20px;">您要办理的业务<span style="color:red;">*</span></h5>

        <form class="mui-input-group">
            <div class="mui-input-row mui-checkbox mui-left">
                <label>公司注册</label>
                <input name="intention1" value="1" type="checkbox" >
            </div>
            <div class="mui-input-row mui-checkbox mui-left">
                <label>代理记账</label>
                <input name="intention2" value="2" type="checkbox" >
            </div>
            <div class="mui-input-row mui-checkbox mui-left ">
                <label>其他</label>
                <input name="intention3" value="3" type="checkbox" >
            </div>
        </form>
        <br/>
        <form class="mui-input-group" >
        <div class="mui-input-row ">
            <label><span class="mui-icon mui-icon-person"></span>姓名：</label>
            <input type="text" id="name" placeholder="请输入姓名">
        </div>
        <div class="mui-input-row">
            <label><span class="mui-icon mui-icon-phone"></span>电话:</label>
            <input type="number" id="phone" class="mui-input-clear" placeholder="请输入电话">
        </div>
        <br>
         <button id='alertBtn' type="button" class="mui-btn mui-btn-primary mui-btn-block"  onclick="save()" >立即提交</button>
        </form>
    </div>
</div>
</body>
<script src="/js/mui.min.js"></script>
<script src="/js/jquery.min.js"></script>
<script>
    mui.init({
        swipeBack:true //启用右滑关闭功能
    });
    mui('.mui-input-group').on('change', 'input', function() {
        //var value = this.checked?"true":"false";
        //this.previousElementSibling.innerText = "checked："+value;
    });
    //提交保存
    function save(){
        var intentions = $('form').serialize();//复选框选中内容;
        var name = $('#name').val();//姓名;
        var phone = $('#phone').val();//电话号码;
        if(intentions == ''){
            mui.alert('请选择您想要办理的业务！', '<span style="color:red;">业务未选择！</span>', function() {
            });
            return false;
        }
        if(name == ''){
            mui.alert('请您先填写姓名！', '<span style="color:red;">姓名为空！</span>', function() {
            });
            return false;
        }
        if(phone == ''){
            mui.alert('请您填写电话号码！', '<span style="color:red;">手机号为空！</span>', function() {
            });
            return false;
        }
        if(phone.length != 11){
            mui.alert('手机号长度不正确,请检查！', '<span style="color:red;">手机号有误！</span>', function() {
            });
            return false;
        }
        $.ajax({
            url:'/saveIntent',
            data:{intentions:intentions,name:name,phone:phone},
            type:'POST',
            success:function(data, textStatus, jqXHR){
                if(data =='1'){
                    mui.alert('申请成功,苗姐会尽快联系您！', '<span style="color:green">申请成功!</span>', function() {
                        window.setTimeout(function(){
                            WeixinJSBridge.call('closeWindow');
                        },2000);
                    });
                }
            },
            error: function ($msg) {
                alert('服务器故障,工程师正在抢修中.......');
            }

        });

    }
</script>
</html>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>意见反馈中心</title>
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
    <h1 class="mui-title">意见反馈中心</h1>
</header>
<div class="mui-content">
    <div class="mui-content-padded" style="margin: 5px;">
        <div class="mui-input-row" style="margin: 10px 5px;">
            <textarea id="textarea" rows="5" placeholder="请输入您的宝贵意见,让小苗变得更好!"></textarea>
        </div>
        <form class="mui-input-group">

            <button id='alertBtn' type="button" class="mui-btn mui-btn-primary mui-btn-block"  onclick="save()" >立即反馈</button>
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
        var content = $('#textarea').val();//反馈内容;
        var open_id = '{{$openid}}';
        if(content == ''){
            mui.alert('反馈内容为空！请先进行填写 ', '<span style="color:red;">反馈内容为空！</span>', function() {
            });
            return false;
        }
        $.ajax({
            url:'/saveSuggestion',
            data:{open_id:open_id,customer_content:content},
            type:'POST',
            success:function(data, textStatus, jqXHR){
                if(data =='1'){
                    mui.alert('反馈成功,您的反馈小苗已经收到!', '<span style="color:green">反馈成功!</span>', function() {
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
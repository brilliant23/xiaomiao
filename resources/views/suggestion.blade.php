<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>小苗会计意见反馈中心</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="/favicon.ico">
    {{HTML::style('css/bootstrap.min.css?v=3.3.6')}}
    {{HTML::style('css/font-awesome.css?v=4.4.0')}}
    {{HTML::style('css/style.css?v=4.1.0')}}
    <style>
        .has-error, .has-error p.help-block {
            color: #ed5565;
        }
        .star {
            color: #ed5565;
            font-size: 14px;
            margin: 3px;
        }
        .form-group .control-label {
            width: 77px;
        }
    </style>
</head>

<body class="gray-bg">
<div ng-app="myModule" ng-controller="myController">
    <div >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">意见反馈中心：</h4>
                </div>
                <div class="">
                    <form name="myFormAdd" id="formAdd" novalidate>
                        <textarea name="content" id="editor1" rows="10" cols="80" ng-model="post.content"></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" form="form1" ng-disabled="myFormAdd.$invalid"
                            onclick="save()">提交
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 全局js -->
{{HTML::script('js/jquery.min.js?v=2.1.4')}}
{{HTML::script('js/bootstrap.min.js?v=3.3.6')}}

{{HTML::script('js/ckeditor_4.6.0/ckeditor.js')}}


<script>
        var xmlHttpRequest;
        if(window.XMLHttpRequest){
            xmlHttpRequest=new XMLHttpRequest();
        }else{
            xmlHttpRequest=new ActiveXObject("Microsoft.XMLHTTP");
        }

       CKEDITOR.replace( 'editor1', {
        language: 'zh-cn',
        //uiColor: '#9AB8F3',
        //toolbar: 'basic',
        height: 160,
        toolbar: [
            //加粗     斜体，     下划线      穿过线      下标字        上标字     表情
            ['Bold','Italic','Underline','Strike','Subscript','Superscript','Smiley'],
        ],
    });
    function save() {
            var open_id = '{{$openid}}';
            var content =  CKEDITOR.instances.editor1.getData();
            if(content == ''){
                alert('请先填写反馈内容！');
                return false;
            }

        $.ajax({
                url:"/saveSuggestion",
                data: {open_id:open_id,customer_content:content},
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    if(data =='1'){
                        alert('你的反馈我们已经收到,会尽快安排服务人员与您沟通！');
                        window.setTimeout(function(){
                            WeixinJSBridge.call('closeWindow');
                        },2000);

                    }
                },
                error: function ($msg) {
                    alert('服务器故障,工程师正在抢修中.......');
                }
            });
    }

</script>

</body>
</html>
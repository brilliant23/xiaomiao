<?php

header("Content-type:text;charset:utf-8;");

define("TOKEN", "lijun");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            header('content-type:text');
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = file_get_contents('php://input');
        //$GLOBALS["HTTP_RAW_POST_DATA"];
        //var_dump(file_get_contents('php://input'));
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $MsgType = trim($postObj->MsgType);

            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $content = trim($postObj->Content);
            $time = time();

            //时间类型触发不同的回复消息！
           switch($MsgType){
                case 'event':
                    $result = $this ->receiverEvent($postObj);
                    break;
                case 'text':
                    $result = $this ->receiverText($postObj);
                    break;
                case '天气':
                    $contentStr = '天气有点冷';
                    break;
                case '最喜欢的明星':
                    $contentStr = '周星驰';
                    break;
                default:

                    break;
            }

           /* $textTpl = "<xml>
              <ToUserName><![CDATA[%s]]></ToUserName>
              <FromUserName><![CDATA[%s]]></FromUserName>
              <CreateTime>%s</CreateTime>
              <MsgType><![CDATA[%s]]></MsgType>
              <Title><![CDATA[小苗科技官网链接]]></Title>
              <Description><![CDATA[小苗科技官网链接]]></Description>
              <Url><![CDATA[%s]]></Url>
              </xml>";
            $contentStr = 'http://www.xiaomiaokuaiji.com';
            $msgType = "link";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$contentStr);
            echo $resultStr;*/

        }else{
            echo "您没有输入内容";
            exit;
        }
    }
    //发送文本消息自动回复内容
    public function receiverText($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $content = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml> 
              <ToUserName><![CDATA[%s]]></ToUserName>
              <FromUserName><![CDATA[%s]]></FromUserName>
              <CreateTime>%s</CreateTime>
              <MsgType><![CDATA[%s]]></MsgType>
              <Content><![CDATA[%s]]></Content>
              </xml>";
        $msgType = 'text';
        //$resultStr = json_encode($postObj);

        if($content == '小苗果蔬'){
            $resultStr = 'https://daojia.jd.com/activity/storeShare/index.html?channel=o2ostore&storeId=11663508&orgCode=296711';
        }else{
            $resultStr = '苗姐正在火速赶来，您稍等……稍安勿躁，马上到!!!';
        }


        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$resultStr);
        echo $resultStr;
    }


    //自动处理关注事件
    public function receiverEvent($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $content = trim($postObj->Content);
        $time = time();
        //什么事件啊
        $event_type = $postObj ->Event;
        switch($event_type){
            case 'subscribe':
                $returnStr = "君上，苗姐已恭候您多时！".$this->unicode2utf8_2('\ue022')."\n从今天起，苗姐将24小时全程无休为君上您服务".$this->unicode2utf8_2('\ue022')."\n工商注册、代理记账、社保（五险一金）代理、建站设计是苗姐的专长".$this->unicode2utf8_2('\ue022')."\n只要君上一句话，苗姐将马不停蹄，火速执行".$this->unicode2utf8_2('\ue022')."\n还望君上和苗姐相处的日子里，能开心，顺心，舒心".$this->unicode2utf8_2('\ue022')."\n最后苗姐代表全体员工欢迎您的加入 \n".$this->unicode2utf8_2('\ue418').$this->unicode2utf8_2('\ue418').$this->unicode2utf8_2('\ue418').$this->unicode2utf8_2('\ue418').$this->unicode2utf8_2('\ue418').$this->unicode2utf8_2('\ue418').$this->unicode2utf8_2('\ue418').$this->unicode2utf8_2('\ue418').$this->unicode2utf8_2('\ue418');
                break;
            case 'unsubscribe':
                $returnStr = '抱歉！我们会继续进行改进';
                break;
            case 'CLICK':
                $EventKey = $postObj ->EventKey;
                if($EventKey == 'V1001_0001'){
                    $returnStr = '亲爱的客户您好！感谢您一直以来对小苗的守护和陪伴，您可直接在下方留言提出您的宝贵意见，与我们CEO面对面沟通！';
                }elseif($EventKey == 'V1001_0002'){
                    $returnStr =    '前世的五百次回眸才换来今生的擦肩而过,但是我想与您展开亲密的商业合作,合作共赢是小苗永远的愿景,留言告诉我您的需求,我来满足您的一切要求!';
                }elseif($EventKey == 'V1001_0003'){
                    $msgType = 'link';
                }
                break;
            default:
                $returnStr = json_encode($postObj);
                break;

        }
        //check是否有指定返回类型
        if(isset($msgType) && $msgType == 'link'){
            $url = 'http://www.xiaomiaokuaiji.com';
            $textTpl = "<xml> 
              <ToUserName><![CDATA[%s]]></ToUserName>
              <FromUserName><![CDATA[%s]]></FromUserName>
              <CreateTime>%s</CreateTime>
              <MsgType><![CDATA[%s]]></MsgType>
              <Title><![CDATA[小苗科技官网链接]]></Title>
              <Description><![CDATA[小苗科技官网链接]]></Description>
              <Url><![CDATA[%s]]></Url>
              </xml>";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$url);
        }else{
            $textTpl = "<xml> 
              <ToUserName><![CDATA[%s]]></ToUserName>
              <FromUserName><![CDATA[%s]]></FromUserName>
              <CreateTime>%s</CreateTime>
              <MsgType><![CDATA[%s]]></MsgType>
              <Content><![CDATA[%s]]></Content>
              </xml>";
            $msgType = 'text';
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$returnStr);
        }
        echo $resultStr;
    }

    //转换内容
    public function unicode2utf8_2($str) {
        $str = '{"result_str":"' . $str . '"}';
        $strarray = json_decode ( $str, true );
        return $strarray ['result_str'];
    }
}
?>

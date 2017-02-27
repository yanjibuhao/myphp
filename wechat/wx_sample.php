<?php
//auto hc from net
//微信自动回复
//define your token
define("TOKEN", "thchat");
$wechatObj = new wechatCallbackapiTest();
// $wechatObj->valid();
$wechatObj->responseMsg();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
         {
         //get post data, May be due to the different environments
         $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
         
         //extract post data
         if (!empty($postStr)){
         /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
          the best way is to check the validity of xml by yourself */
         libxml_disable_entity_loader(true);
          $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
         $fromUsername = $postObj->FromUserName;
         $toUsername = $postObj->ToUserName;
         $keyword = trim($postObj->Content);
         $time = time();
         $msgType = $postObj->MsgType;//消息类型
         $event = $postObj->Event;//时间类型，subscribe（订阅）、unsubscribe（取消订阅）
         $textTpl = "<xml>
          <ToUserName><![CDATA[%s]]></ToUserName>
          <FromUserName><![CDATA[%s]]></FromUserName>
          <CreateTime>%s</CreateTime>
          <MsgType><![CDATA[%s]]></MsgType>
          <Content><![CDATA[%s]]></Content>
          <FuncFlag>0</FuncFlag>
          </xml>"; 
           
         switch($msgType){
          case "event":
          if($event=="subscribe"){
          $contentStr = "<a href='http://wportal.tplogin.cn:8080/portal/wechat_auth/?token=179c3d74'>陇尚牧场场内免费上网</a>";
          } 
          break;
         }
         $msgType = "text";
         $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
         echo $resultStr;
         }else {
         echo "";
         exit;
         }
     }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>
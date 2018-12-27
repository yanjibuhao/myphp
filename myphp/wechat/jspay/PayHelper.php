<?php
/**
 * Created by PhpStorm.
 * User: su guo qiang
 * Date: 15-12-14
 * Time: 上午 9:56
 * 微信支付帮助库
 */

class Common_util_pub
{
    public $appid, $mchid, $key, $appsecret;

    function __construct($appid,$mchid,$key,$appsecret) {
        $this->appid 	 = $appid;
        $this->mchid 	 = $mchid;
        $this->key  	 = $key;
        $this->appsecret = $appsecret;
    }

    function trimString($value) {
        $ret = null;
        if (null != $value) {
            $ret = $value;
            if (strlen($ret) == 0) {
                $ret = null;
            }
        }
        return $ret;
    }

    /* 产生随机字符串，不长于32位 */
    function createNoncestr( $length = 32 ) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /* 产生随机数字，不长于32位 */
    function createNum( $length = 32 ) {
        $chars = "123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /* 格式化参数，签名过程需要使用 */
    function formatBizQueryParaMap($paraMap, $urlencode) {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v){
            if($urlencode){
                $v = urlencode($v);
            }
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    /* 生成签名 */
    function getSign($Obj){
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        // 按字典序排序参数
        // ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        // 在string后加入KEY
        $String = $String."&key=".$this->key;
        // MD5加密
        $String = md5($String);
        // 所有字符转为大写
        $result_ = strtoupper($String);
        return $result_;
    }

    /* array转xml */
    function arrayToXml($arr) {
        $xml = "<xml>";
        foreach ($arr as $key=>$val) {
            if (is_numeric($val)) {
                $xml.="<".$key.">".$val."</".$key.">";
            }
            else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }

    /* 组装xml数据 */
    protected function _data2xml($xml, $data, $item = 'item') {
        foreach ( $data as $key => $value ) {
            is_numeric ( $key ) && ($key = $item);
            if (is_array ( $value ) || is_object ( $value )) {
                $child = $xml->addChild ( $key );
                $this->_data2xml ( $child, $value, $item );
            } else {
                if (is_numeric ( $value )) {
                    $child = $xml->addChild ( $key, $value );
                } else {
                    $child = $xml->addChild ( $key );
                    $node = dom_import_simplexml ( $child );
                    $node->appendChild ( $node->ownerDocument->createCDATASection ( $value ) );
                }
            }
        }
    }

    /*	将xml转为array */
    function xmlToArray($xml) {
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /* 以post方式提交xml到对应的接口url */
    function postXmlCurl($xml,$url,$second=60) {
        //初始化curl
        
        $ch = curl_init();
        //curl_setopt($ch, CURLOP_TIMEOUT, $second);
        // 这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
        curl_close($ch);
        //返回结果
        if($data) {
            $data = new \SimpleXMLElement ( $data );
            $data || die ( '参数获取失败' );
            $resArr = array();
            foreach ( $data as $key => $value ) {
                $resArr [$key] = strval ( $value );
            }
            return $resArr;
        } else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            return false;
        }
    }

    /* 使用证书，以post方式提交xml到对应的接口url */
    function postXmlSSLCurl($xml,$url,$second=30) {
        $ch = curl_init();
        //curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch,CURLOPT_HEADER,FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,'');
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY, '');
        //post提交方式
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }

    /* 	打印数组 */
    function printErr($wording='',$err='') {
        print_r('<pre>');
        echo $wording."</br>";
        var_dump($err);
        print_r('</pre>');
    }
}

/* 请求型接口的基类 */
class Wxpay_client_pub extends Common_util_pub
{
    var $parameters;//请求参数，类型为关联数组
    public $response;//微信返回的响应
    public $result;//返回参数，类型为关联数组
    var $url;//接口链接
    var $curl_timeout;//curl超时时间

    /* 设置请求参数 */
    function setParameter($parameter, $parameterValue) {
        $this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }

    /* 设置标配的请求参数，生成签名，生成接口参数xml */
    function createXml() {
        $this->parameters["appid"] = $this->appid;//公众账号ID
        $this->parameters["mch_id"] = $this->mchid;//商户号
        $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
        $this->parameters["sign"] = $this->getSign($this->parameters);//签名
        // return  $this->arrayToXml($this->parameters);
        $xml = new \SimpleXMLElement ( '<xml></xml>' );
        $this->_data2xml ($xml,$this->parameters);
        $str = $xml->asXML ();
        return $str;
    }

    /* post请求xml */
    function postXml() {
        $xml = $this->createXml();
        // $xml = iconv("utf-8","iso-8859-1",$xml);
        // $xml = '<xml><appid><![CDATA[wx47056a4a797e1523]]></appid><body><![CDATA[娴嬭瘯鐢ㄤ緥]]></body><mch_id>1295833201</mch_id><nonce_str><![CDATA[z1t93eu8fltpeud8lxy3dewe7ja05wqg]]></nonce_str><notify_url><![CDATA[http://jiong9279.xicp.net/pay/notice]]></notify_url><openid><![CDATA[oC0dzw4BkqJzliwehr2as64p8sN4]]></openid><out_trade_no>125515154</out_trade_no><spbill_create_ip><![CDATA[192.168.2.173]]></spbill_create_ip><total_fee>1</total_fee><trade_type><![CDATA[JSAPI]]></trade_type><sign><![CDATA[0B14E28C7C43C9BFEB9B13755B78FA45]]></sign></xml>';
        // $xml = str_replace('<![CDATA[','',$xml);
        // $xml = str_replace(']]>','',$xml);
        // $xml = '<xml><appid>wx47056a4a797e1523</appid><body>test</body><mch_id>1295833201</mch_id><nonce_str>z1t93eu8fltpeud8lxy3dewe7ja05wqg</nonce_str><notify_url>http://jiong9279.xicp.net/pay/notice</notify_url><openid>oC0dzw4BkqJzliwehr2as64p8sN4</openid><out_trade_no>125515154</out_trade_no><spbill_create_ip>220.249.163.129</spbill_create_ip><total_fee>1</total_fee><trade_type>JSAPI</trade_type><sign>65A75C34792852C361E6131903CCB868</sign></xml>';
        $this->response = $res = $this->postXmlCurl($xml,$this->url,$this->curl_timeout);
        // $this->response = $res = Weixin_Function::post_curl($this->url,$xml);
        if($res['return_code']=='FAIL'){
            echo '微信支付出错啦：'.$res['return_msg'];
            exit;
        }
        return $this->response;
    }

    /* 使用证书post请求xml */
    function postXmlSSL() {
        $xml = $this->createXml();
        $this->response = $this->postXmlSSLCurl($xml,$this->url,$this->curl_timeout);
        return $this->response;
    }

    /* 获取结果，默认不使用证书 */
    function getResult() {
        $this->postXml();
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }
}

/* 统一支付接口类 */
class UnifiedOrder_pub extends Wxpay_client_pub {
    function __construct($appid,$mchid,$key,$appsecret) {
        Common_util_pub::__construct($appid,$mchid,$key,$appsecret);
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        //设置curl超时时间
        $this->curl_timeout = 60;
    }

    /* 生成接口参数xml */
    function createXml() {
        try { //检测必填参数
            if($this->parameters["out_trade_no"] == null) {
                echo ("缺少统一支付接口必填参数out_trade_no！"."<br>"); exit;
            }elseif($this->parameters["body"] == null){
                echo ("缺少统一支付接口必填参数body！"."<br>"); exit;
            }elseif ($this->parameters["total_fee"] == null ) {
                echo ("缺少统一支付接口必填参数total_fee！"."<br>"); exit;
            }elseif ($this->parameters["notify_url"] == null) {
                echo ("缺少统一支付接口必填参数notify_url！"."<br>"); exit;
            }elseif ($this->parameters["trade_type"] == null) {
                echo ("缺少统一支付接口必填参数trade_type！"."<br>"); exit;
            }elseif ($this->parameters["trade_type"] == "JSAPI" && $this->parameters["openid"] == NULL){
                echo ("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>"); exit;
            }
            $this->parameters["appid"] = $this->appid; //公众账号ID
            $this->parameters["mch_id"] = $this->mchid; //商户号
            // $this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR']; //终端ip
            $this->parameters["spbill_create_ip"] = '220.249.163.129'; //终端ip
            $this->parameters["nonce_str"] = $this->createNoncestr(); //随机字符串
            ksort($this->parameters);
            $this->parameters["sign"] = $this->getSign($this->parameters); //签名
            return  $this->arrayToXml($this->parameters);
        } catch (Exception $e) {
            die($e->errorMessage());
        }
    }

    /* 获取prepay_id */
    function getPrepayId() {
        $this->postXml();

        //$this->result = $this->xmlToArray($this->response);
        $this->result = $this->response;
        $prepay_id = $this->result["prepay_id"];
        return $prepay_id;
    }
}



/* JSAPI支付——H5网页端调起支付接口 */
class JsApi_pub extends Common_util_pub {
    var $code;//code码，用以获取openid
    var $openid;//用户的openid
    var $parameters;//jsapi参数，格式为json
    var $prepay_id;//使用统一支付接口得到的预支付id
    var $curl_timeout;//curl超时时间

    function __construct($appid,$mchid,$key,$appsecret) {
        parent::__construct($appid,$mchid,$key,$appsecret);
        $this->curl_timeout = 60;
    }

    /* 生成可以获得code的url */
    function createOauthUrlForCode($redirectUrl) {
        $urlObj["appid"] = $this->appid;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $bizString = $this->formatBizQueryParaMap($urlObj, false);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }

    /* 生成可以获得openid的url */
    function createOauthUrlForOpenid() {
        $urlObj["appid"] = $this->appid;
        $urlObj["secret"] = $this->appsecret;
        $urlObj["code"] = $this->code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->formatBizQueryParaMap($urlObj, false);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }

    /* 通过curl向微信提交code，以获取openid */
    function getOpenid() {
        $url = $this->createOauthUrlForOpenid();
        $tempArr = json_decode(Weixin_Function::get_curl($url), true);
        if (@array_key_exists('access_token', $tempArr)) {
            $this->access = $tempArr ['access_token'];
            $this->openid = $tempArr ['openid'];
        }
        return $tempArr ['openid'];
    }

    /* 设置prepay_id */
    function setPrepayId($prepayId) {
        $this->prepay_id = $prepayId;
    }

    /* 设置code */
    function setCode($code_) {
        $this->code = $code_;
    }

    /* 设置jsapi的参数 */
    function getParameters() {
        $jsApiObj["appId"] = $this->appid;
        $timeStamp = time();
        $jsApiObj["timeStamp"] = "$timeStamp";
        $jsApiObj["nonceStr"] = $this->createNoncestr();
        $jsApiObj["package"] = "prepay_id=$this->prepay_id";
        $jsApiObj["signType"] = "MD5";
        $jsApiObj["paySign"] = $this->getSign($jsApiObj);
        $this->parameters = json_encode($jsApiObj);
        return $this->parameters;
    }
    /* 通知回复 */
    function noticeReturn(){
        $rData['return_code'] = 'SUCCESS';
        $rData['return_msg'] = 'OK';
        $xml = new \SimpleXMLElement ( '<xml></xml>' );
        $this->_data2xml ( $xml, $rData );
        $str = $xml->asXML ();
        echo ($str);
        exit;
    }
}

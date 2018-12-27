<?php
/**
 * Function:微信支付pay
 * User: HC
 * Date: 2018/12/25
 * Time: 10:37
 */

require_once 'Wechat/PayHelper.php';
class PayController extends Zend_Controller_Action{
    private $payConfig;
    private $jsApi;

    function init() {
        $this->setInit();
    }

    /* 支付结果通知 */
    function noticeAction(){
        if (get_magic_quotes_gpc()) {
            function stripslashes_deep($value){
                $value = is_array($value) ?
                    array_map('stripslashes_deep', $value):
                    stripslashes($value);
                return $value;
            }
            $_POST = array_map('stripslashes_deep', $_POST);
            $_GET = array_map('stripslashes_deep', $_GET);
            $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
        }

        $array_data = json_decode(json_encode(simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA)), true);
//        Widgets_Apilog::adddata("paynoticy","",json_encode($array_data,true),"");
//        Widgets_Card::setLogs($array_data);
//        if($array_data['return_code']=='SUCCESS'){
//            $tmp_arr = explode('=',$array_data['attach']);
//            $oid = (int)$tmp_arr[1];
//            if($oid){
////                Widgets_Card::setdemoMes($array_data['openid'],$array_data['total_fee']);
////                Widgets_Card::setMessage($array_data['openid']);
//
//            }
//
//        }
        $this->jsApi->noticeReturn();
    }


    function payAction(){

        // var_dump($_SESSION["OpenID"]);exit;
        // $param = $_REQUEST;
        $type = (float) $this->getRequest()->getParam('type');
//        $product_arr=Widgets_Product::config($type);
//        $order_id=Widgets_Order::addOrder($type,Widgets_User::getUserByOpenid(),$product_arr['money']);
        /*if($price<=0){
            echo json_encode(array('status'=>0,'msg'=>'捐款金额格式有误'));exit;
        }else if(empty($name)){
            echo json_encode(array('status'=>0,'msg'=>'姓名不能为空'));exit;
        }*/


        $order_id=rand(1,100000);
        //var_dump($order_id,$number);exit;

        $price = 0.02;
        // $orderMod = new Model_OrderMapper();
        $order['orderNum'] = $this->jsApi->createNum(6).time();
        //$order['openid'] = $_SESSION['OpenID'];
        /*$order['price'] = $price;
        $order['amount'] = $price;
        $order['name'] = $name;
        $order['note'] = $note;
        $orderid = $orderMod->save($order);*/
        $orderid=10;

        // 相关事件绑定
        // $payHandel=new payHandle($this->token,$_GET['from'],'weixin');
        // $orderInfo=$payHandel->beforePay($orderid);
        // $price=$orderInfo['price'];
        // 判断是否已经支付过
        // if($orderInfo['paid']) exit('您已经支付过此次订单！');

        // 获取 prepay_id
//         var_dump($this->payConfig);exit;
//         echo "<script>alert('{$_SESSION['OpenID']}')</script>";exit;
        $unifiedOrder = new UnifiedOrder_pub($this->payConfig['new_appid'],$this->payConfig['mchid'],$this->payConfig['key'],$this->payConfig['appsecret']);
        $unifiedOrder->setParameter("openid",$_SESSION['open_id']);
        $unifiedOrder->setParameter("body",'敲有型');
        $unifiedOrder->setParameter("out_trade_no",$order['orderNum']);
        $unifiedOrder->setParameter("total_fee",$price*100);
        $unifiedOrder->setParameter("notify_url",'http://'.$_SERVER['HTTP_HOST'].'/pay/notice');
        $unifiedOrder->setParameter("trade_type","JSAPI");
        $unifiedOrder->setParameter("attach",'sudo='.$order_id);
        $prepay_id = $unifiedOrder->getPrepayId();
//         var_dump($prepay_id);exit;
        // 使用jsApi调起支付
        $jsApi = $this->jsApi;
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
        $jsApiParameters = $jsApiParameters;

        $rData['rUrl'] = '';
        $rData['status'] = 1;
        $rData['jsApi'] = $jsApiParameters;

        echo json_encode($rData);
        exit;


    }


    /* 用户授权回调函数 保存用户信息 */
    function redirectAction(){
        if (!isset($_GET['code'])){
            echo 'error:no find code';
            exit;
        }
        $code = $_GET['code'];
        $jsApi = $this->jsApi;
        $jsApi->setCode($code);
        $openid = $jsApi->getOpenid();
        if(!isset($openid)||empty($openid)){
            echo "未获取到OpenID";
            exit;
        }
        $_SESSION['OpenID'] = $openid;
        $this->_forward('index');
    }



    private function setInit(){
        $payConfig['new_appid'] = 'wx0552d3870e2514c5';
        $payConfig['mchid'] = '1519502001';
        $payConfig['key'] = 'djsz3366djsz3366djsz3366djsz3366';
        $payConfig['appsecret'] = '221a3700aa6b4437cd550919dda3d278';

//        $payConfig['new_appid'] = 'wx47056a4a797e1523';
//        $payConfig['mchid'] = '1295833201';
//        $payConfig['key'] = 'djsz3366djsz3366djsz3366djsz3366';
//        $payConfig['appsecret'] = 'f9e2983eaf79e14bdd13c05b646b589f';
//         $payConfig['new_appid'] = 'wxb0c6cbf85fa1feeb';
//         $payConfig['mchid'] = '1289871101';
//         $payConfig['key'] = '8GZ44RXpFMUfj3aNrKlMnWTTWe3adiJ6';
//         $payConfig['appsecret'] = 'fc754becaab8669a7e61e2b28fba3ded';
        $this->jsApi = new JsApi_pub($payConfig['new_appid'],$payConfig['mchid'],$payConfig['key'],$payConfig['appsecret']);
        $this->payConfig = $payConfig;
    }
}
?>
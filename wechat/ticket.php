<?php
/**
 * User: HC
 * Date: 2017/10/24
 * Time: 16:27
 * Function:二维码处理
 */
//user_id为自定义参数
class ticket{
    private $acc_token;

    public function __construct(){
        $this->setConfig();

    }
    //配置
    public function setConfig(){


        $this->acc_token="";//access_token
        if(!$this->acc_token){return false;}
    }

    //获取二维码地址
    public function getUrl($user_id){
        $ticketRs=$this->getTicket($user_id);
        if($ticketRs){
            $ticketRs=urlencode($ticketRs);
        }else{
            return false;
        }
        $wxTicketUrl='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticketRs;
        return $wxTicketUrl;
    }

    //获取ticket
    public function getTicket($user_id){
        $url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->acc_token;
        $paramAr=array(
            "expire_seconds"=>604800,
            "action_name"=>"QR_STR_SCENE",
            "action_info"=>array("scene"=>array("scene_str"=>$user_id))
        );
        $param=json_encode($paramAr);
        $return=$this->httpGet($url,$param);
        $resArr = json_decode($return,true);
        if(isset($resArr['errcode'])){
            if($resArr['errcode']=="40001"){
                $this->acc_token=file_get_contents("http://weixin.xmisp.com/index/regettoken");
                return $this->getTicket($user_id);
            }
        }
        if(isset($resArr['ticket'])){
            return $resArr['ticket'];
        }
        return false;


    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
}

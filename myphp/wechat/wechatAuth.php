<?php 
//auto Hc
//HC维信授权登录操作
//微信帐号配置
	static function wechatConfig(){
		$result=array(
			"appid"=>"test",
			"secret"=>"test",
			"redirect_uri"=>"test",
			"response_type"=>"code",
			"grant_type"=>"authorization_code",
			"scope"=> "snsapi_userinfo",
			"state"=>"yochat",
			"lang"=>"zh_CN"
			);
		return $result;
	}
	//token换取code
	static function wechatAuto(){
		//var_dump($_GET);exit;
		$wechatConfig=self::wechatConfig();
		$appid = trim($wechatConfig["appid"]);//appID
        $redirect_uri = trim($wechatConfig["redirect_uri"]);//重定向地址
        // var_dump($redirect_uri);exit;
        $response_type = trim($wechatConfig["response_type"]);
        $scope = trim($wechatConfig["scope"]);
        $state = trim($wechatConfig["state"]);
        //获取确认地址 
        $html="https://open.weixin.qq.com/connect/oauth2/authorize";
		$html .= "?appid=".$appid;
		$html .= "&redirect_uri=".$redirect_uri;
		$html .= "&response_type=".$response_type;
		$html .= "&scope=".$scope;
		$html .= "&state=".$state;
		$html .= "#wechat_redirect";
		// var_dump($html);exit;
		if (!isset($_GET["code"])) {
			echo "<script>window.location='{$html}';</script>";exit;
		} else {
			return;
		}
	
	}

	//网页授权access_token
	public static function authAccess() {
		$code = $_GET["code"];
		$weconfig = self::wechatConfig();
		$appid = trim($weconfig["appid"]);//appID
		$grant_type = trim($weconfig["grant_type"]);
        $secret = trim($weconfig["secret"]);
	//var_dump($secret);exit;
		$html = "https://api.weixin.qq.com/sns/oauth2/access_token";
		$html .= "?appid=" .$appid;
		$html .= "&secret=" .$secret;
		$html .= "&code=" .$code;
		$html .= "&grant_type=" .$grant_type;
// var_dump($html);exit;
		$result=self::curlGet($html);
		$result=json_decode($result,TRUE);
		return $result;
		
	}

	//获取wechat userinfo
	public static function getWeUserinfo() {
		$ret = self::authAccess();
		// var_dump($ret);exit;
		if (!isset($ret["errcode"])) {		
			$access_token = $ret["access_token"];
			$refresh_token = $ret["refresh_token"];
			$openid = $ret["openid"];		
			$weconfig = self::wechatConfig();
			$lang = trim($weconfig["lang"]);
			$html = "https://api.weixin.qq.com/sns/userinfo";
			$html .= "?access_token=" .$access_token;
			$html .= "&openid=" .$openid;
			$html .= "&lang=" .$lang;
			$result=self::curlGet($html);
			$result=json_decode($result,TRUE);
			return $result;
	    } else {
	    	return false;
	    }
	}

	public function curlGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $return = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
            exit(0);
        }
        curl_close($ch);
        return $return;
    }

?>


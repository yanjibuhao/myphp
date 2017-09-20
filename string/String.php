<?php

	//头部自主定义classname
	/**
	 * email地址替换为星号
	 */
	static public function email_replace($email) {
		$arrEmail = explode ( '@', $email );
		$emailNum = $arrEmail ['0'];
		$emailLen = strlen ( $emailNum );
		if ($emailLen >= 6) {
			$email_result = substr_replace ( $emailNum, '***', '3', '3' );
		} else {
			$email_result = substr ( $emailNum, '0', '1' ) . '**' . substr ( $emailNum, '4' );
		}
		if(count($arrEmail)>1){
			$result = $email_result . '@' . $arrEmail ['1'];
		}else{
			$result = $email_result;
		}
		return $result;
	}
	
	/**
	 *
	 *手机号码替换为星号
	 */
	static public function mobi_replace($mobi) {
		if (strlen ( $mobi ) == '11') {
			$result = substr_replace ( $mobi, '*****', '3', '5' );
		} else {
			$result = substr ( $mobi, '0', '1' ) . '*****' . substr ( $mobi, '6' );
		}
		return $result;
	}
	
	/**
	 * 生成随机数（手机验证码）
	 */
	static public function makeMobiCode($number=6) {
		$result = '';
		$array = array (
				'0',
				'1',
				'2',
				'3',
				'4',
				'5',
				'6',
				'7',
				'8',
				'9' 
		);
		for($i = 1; $i <= $number; $i ++) {
			$rand_num = rand ( '0', '9' );
			$result .= $array [$rand_num];
		}
		return $result;
	}
	
	/**
	 * 获取application.ini的文件的信息
	 */
	static public function getArrayFromINI($name) {
		$pEnv = getenv ( 'Application_ENV' );
		$config = new Zend_Config_Ini ( Application_PATH . '/configs/application.ini', $pEnv );
		$arrConfig = $config->toArray ();
		return $arrConfig [$name];
	}
	
	/**
	 * 判断密码是福包含大小写字母
	 * @param  $pwd
	 * @return boolean
	 */
	static public function checkCardPwd($pwd) {
		if (ereg ( "^[0-9a-zA-Z]+$", $pwd )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 
	 * @param unknown_type $beginTime
	 * @param unknown_type $endTime
	 * @return boolean
	 */
	static public function checkTime($beginTime, $endTime) {
		if (strtotime ( $endTime ) >= strtotime ( $beginTime )) {
			return true;
		} else {
			return false;
		}
	}
	static function implode($pArray, $pSpec = ',') {
		return implode ( $pSpec, $pArray );
	}
	static function explode($pString, $pSpec = ',') {
		return explode ( $pSpec, $pString );
	}
	static function checkString($pString, $pIncludeString) {
		$array = Tools_String::explode ( $pString );
		$result = false;
		foreach ( $array as $value ) {
			if ($value == $pIncludeString) {
				$result = true;
			}
		}
		return $result;
	}
	static function randPasswd() {
		$j = 6;
		$string = "";
		for($i = 0; $i <= $j; $i ++) {
			$string .= rand ( 0, 9 );
		}
		return $string;
	}
	static function getRandChar($length, $numeric = 0) {
		if ($numeric) {
			$hash = sprintf ( '%0' . $length . 'd', mt_rand ( 0, pow ( 10, $length ) - 1 ) );
		} else {
			$hash = '';
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
			$max = strlen ( $chars ) - 1;
			for($i = 0; $i < $length; $i ++) {
				$hash .= $chars [mt_rand ( 0, $max )];
			}
		}
		return $hash;
	}
	static function stripslashes($string) {
		if (is_array ( $string )) {
			foreach ( $string as $key => $val ) {
				$string [$key] = Tools_String::stripslashes ( $val );
			}
		} else {
			$string = stripslashes ( $string );
		}
		return $string;
	}
	function GetAlabNum($pContent) {
		$nums = array (
				'０',
				'１',
				'２',
				'３',
				'４',
				'５',
				'６',
				'７',
				'８',
				'９',
				'．',
				'－',
				'＋',
				'：' 
		);
		$fnums = array (
				'0',
				'1',
				'2',
				'3',
				'4',
				'5',
				'6',
				'7',
				'8',
				'9',
				'.',
				'-',
				'+',
				':' 
		);
		return str_replace ( $nums, $fnums, $pContent );
	}
	public function getEmail($pContent) {
		preg_match ( "/[[:alnum:]._-]+@[[:alnum:]-]+\.([[:alnum:]-]+\.)*[[:alnum:]]+/", $pContent, $pArray );
		return $pArray;
	}
	static function iconv($pString) {
		return iconv ( 'gbk', 'utf-8', $pString );
	}
	static function hex2bin($str) {
		$bin = "";
		$i = 0;
		do {
			$bin .= chr ( hexdec ( $str {$i} . $str {($i + 1)} ) );
			$i += 2;
		} while ( $i < strlen ( $str ) );
		return $bin;
	}
	static function bin2hex($str) {
		$hex = "";
		$i = 0;
		do {
			$hex .= sprintf ( "%02x", ord ( $str {$i} ) );
			$i ++;
		} while ( $i < strlen ( $str ) );
		return $hex;
	}
	static function mktime($pDate) {
		$array = explode ( "-", $pDate );
		return mktime ( 0, 0, 0, $array [1], $array [2], $array [0] );
	}
	static function gbk2utf8($pString) {
		return iconv ( 'gbk', 'utf-8', $pString );
	}
	static function cutstr($string, $length) {
		preg_match_all ( "/[x01-x7f]|[xc2-xdf][x80-xbf]|xe0[xa0-xbf][x80-xbf]|
        [xe1-xef][x80-xbf][x80-xbf]|xf0[x90-xbf][x80-xbf][x80-xbf]|[xf1-xf7][x80-xbf][x80-xbf][x80-xbf]/", $string, $info );
		$wordscut = "";
		$j = 0;
		for($i = 0; $i < count ( $info [0] ); $i ++) {
			$wordscut .= $info [0] [$i];
			$j = ord ( $info [0] [$i] ) > 127 ? $j + 2 : $j + 1;
			if ($j > $length - 3) {
				return $wordscut . " ...";
			}
		}
		return join ( '', $info [0] );
	}
	static function utf_substr($str, $len) {
		$stringlength = strlen ( $str );
		for($i = 0; $i < $len; $i ++) {
			$temp_str = substr ( $str, 0, 1 );
			if (ord ( $temp_str ) > 127) {
				$i ++;
				if ($i < $len) {
					$new_str [] = substr ( $str, 0, 3 );
					$str = substr ( $str, 3 );
				}
			} else {
				$new_str [] = substr ( $str, 0, 1 );
				$str = substr ( $str, 1 );
			}
		}
		$string = join ( $new_str );
		if ($stringlength > $len) {
			// $string .= "";
		}
		return $string;
	}
	static function currenyFormat($pPrice) {
		return sprintf ( "%01.2f", $pPrice );
	}
	
	/**
	 * 此算法计算出来字数和javascript一致！
	 */
	static function countChar($pStr) {
		$pStr = preg_replace ( '/[\x80-\xff]{1,3}/', '**', $pStr, - 1, $n );
		return ceil ( strlen ( $pStr ) / 2 );
	}
	static function e2int($pE) {
		return number_format ( $pE, 0, '', '' );
	}
	
	/**
	 * 如果域名为两节，则直接返回
	 * 
	 * @param $pDomain 域名如www.55.la        	
	 */
	static function getRootDomain($pDomain) {
		$array = explode ( '.', $pDomain );
		if (count ( $array ) > 2) {
			preg_match ( "/\w*\.?([\w|-]*\.(?:com.hk|com.cn|com.tw|net.cn|gov.cn|org.cn|com|net|cn|org|asia|tel|mobi|me|tv|biz|cc|name|info|edu|jp|gov|me|la))$/i", $pDomain, $ohurl );
			return $ohurl ['1'];
		} else {
			return $pDomain;
		}
	}
	
	/**
	 * 将传入变量转化为utf8格式
	 */
	static function mIconv($pTitle, $pPageCode = 'gbk') {
		if ($pPageCode == 'utf-8') {
			$return = $pTitle;
		} else {
			$return = iconv ( 'gbk//IGNORE', 'utf-8', $pTitle );
		}
		return $return;
	}
	static function microtime_float() {
		list ( $usec, $sec ) = explode ( " ", microtime () );
		return (( float ) $usec + ( float ) $sec);
	}
	static function HttpVisit($ip, $host, $url, $port = 80) {
		$errstr = '';
		$errno = '';
		$fp = fsockopen ( $ip, $port, $errno, $errstr, 3 );
		$response = '';
		if (! $fp) {
			return false;
		} else {
			$out = "GET {$url} HTTP/1.1\r\n";
			$out .= "Host:{$host}\r\n";
			$out .= "Connection: close\r\n\r\n";
			fputs ( $fp, $out );
			
			while ( $line = fread ( $fp, 4096 ) ) {
				$response .= $line;
			}
			fclose ( $fp );
			// 去掉Header头信息
			$pos = strpos ( $response, "\r\n\r\n" );
			$response = substr ( $response, $pos + 4 );
			return $response;
		}
	}
	
	/**
	 * 获取客户端IP
	 */
	static function getClientIP() {
		$unknown = 'unknown';
		if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) && $_SERVER ['HTTP_X_FORWARDED_FOR'] && strcasecmp ( $_SERVER ['HTTP_X_FORWARDED_FOR'], $unknown )) {
			$ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
		} elseif (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], $unknown )) {
			$ip = $_SERVER ['REMOTE_ADDR'];
		}
		/*
		 * 处理多层代理的情况 或者使用正则方式：$ip = preg_match("/[\d\.] {7,15}/", $ip, $matches)
		 * ? $matches[0] : $unknown;
		 */
		if (false !== strpos ( $ip, ',' ))
			$ip = reset ( explode ( ',', $ip ) );
		return $ip;
	}
	/**
	 * 获取最后四位
	 */
	static function getLastf($str) {
		return substr ( $str, - 5 );
	}
	/**
	 * 身份替换星号
	 */
	static function replaceId($str){
		$rep_str=substr_replace($str,'****',-4);
		$fin_str=substr_replace($rep_str,'*****',4,5);
		return $fin_str;
	}
	/**
	 * EMAIL替换星号
	 */
	static function replaceEmail($str){
		$arr=explode("@",$str);
		$fin_str=substr_replace($arr[0],'*****',1,2);
		return $fin_str.'@'.$arr[1];
	}
	/**
	 * 手机替换星号
	 */
	static function replaceMobile($str){
		$fin_str=substr_replace($str,'****',3,4);
		return $fin_str;
	}
	/**
	 * *
	 *邮箱验证 
	 */
	static function isEmail($email){
		/*if (eregi("/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$/",$email)) {
			return true;
		}*/
		if (preg_match("/^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$/",$email)) {
			return true;
		}
		return false;	
}

public static function check_email($value,$match='/^[\w\d]+[\w\d-.]*@[\w\d-.]+\.[\w\d]{2,10}$/i'){
	$v = trim($value);
	if(empty($v)){
		return false;
	}
	return preg_match($match,$v);
}
/**
 * 验证电话号码
* @param string $value
* @return boolean
*/
public static function check_phone($value,$match='/^0[0-9]{2,3}[-]?\d{7,8}$/'){
	$v = trim($value);
	if(empty($v))
		return false;
	return preg_match($match,$v);
}

/**
 * 验证手机
 * @param string $value
 * @param string $match
 * @return boolean
 */
public static function check_mobile($value,$match='/^[(86)|0]?(13\d{9})|(15\d{9})|(18\d{9})|(14\d{9})$/'){
	$v = trim($value);
	if(empty($v))
		return false;
	return preg_match($match,$v);
}
/**
 * 验证邮政编码
 * @param string $value
 * @param string $match
 * @return boolean
 */
public static function check_code($value,$match='/\d{6}/'){
	$v = trim($value);
	if(empty($v)){
		return false;
	}
	return preg_match($match,$v);
}
/**
 * 验证IP
 * @param string $value
 * @param string $match
 * @return boolean
 */
public static function check_ip($value,$match='/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/'){
$v = trim($value);
if(empty($v))
	return false;
return preg_match($match,$v);
}

/**
 * 验证身份证号码
 * @param string $value
 * @param string $match
 * @return boolean
 */
public static function check_id($value,$match='/^\d{6}((1[89])|(2\d))\d{2}((0\d)|(1[0-2]))((3[01])|([0-2]\d))\d{3}(\d|X)$/i'){
	$v = trim($value);
	if(empty($v))
		return false;
	else if(strlen($v)>18)
		return false;
	return preg_match($match,$v);
}

/**
 * *
 * 验证URL
 * @pa
 ram string $value
 * @param string $match
 * @return boolean
 */
public static function check_url($value,$match='/^(http:\/\/)?(https:\/\/)?([\w\d-]+\.)+[\w-]+(\/[\d\w-.\/?%&=]*)?$/'){
	$v = strtolower(trim($value));
	if(empty($v))
		return false;
	return preg_match($match,$v);
}
/**
 * 去除字符串中的空格、换行、tab空格等,是trim的补充
* @str字符串
*/
function trimall($str)
{
	$qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
	return str_replace($qian,$hou,$str);
}
/**
 * 去除字符串中的中文
* @str字符串
*/
function notchina($str){
	return preg_replace('/([\x80-\xff]*)/i','',$str);
}
/**
 判断输入是否是纯数字，英文，汉字等
需开启php.ini配置extension=php_mbstring.dll
@$sentence待检查的字符串,$encoding编码方式
* ****************/
public function pure_or_mix($sentence,$encoding='utf-8'){
	$length=strlen($sentence);
	$mbLength=mb_strlen($sentence,$encoding);
	//如果strlen返回的字符长度和mb_strlen以当前编码计算的长度一致，可以判断是纯英文字符串。
	if($length==$mbLength){
		if(is_numeric($sentence)){  //is_numeric函数判断是否为数字字符串
			return 'pure_number';//pn= pure number
		}
		return 'pure_english';  //pe =pure English
	}
	//2、如果strlen返回的字符长度和mb_strlen以当前编码计算的长度不一致，且strlen返回值同mb_strlen的返回值求余后得0可以判断为是全汉字的字符串。
	if($length!=$mbLength && ($length % $mbLength==0)){
		return 'pure_chinese';//pure Chinese
	}
	//  3、如果strlen返回的字符长度和mb_strlen以当前编码计算的长度不一致且strlen返回值同mb_strlen的返回值求余后不为0，可以判断为是英汉混合的字符串。
	return 'mixed';
}
/**
 * 截取字符串
*/
	static function substr_china($string,$sublen,$start=0,$code = 'UTF-8'){
		if($code == 'UTF-8')
		{
			$pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($pa, $string, $t_string); if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen));
			return join('', array_slice($t_string[0], $start, $sublen));
		}
		else{
			$start = $start*2;
			$sublen = $sublen*2;
			$strlen = strlen($string);
			$tmpstr = ''; 
			for($i=0; $i<$strlen; $i++){
				if($i>=$start && $i<($start+$sublen)){
					if(ord(substr($string, $i, 1))>129){
						$tmpstr.= substr($string, $i, 2);
					}else{
						$tmpstr.= substr($string, $i, 1);
					}
				}
				if(ord(substr($string, $i, 1))>129) $i++;
			}
			if(strlen($tmpstr)<$strlen ) $tmpstr.= "";
			return $tmpstr;
		}
	}
	//生成订单号
	static function getOrderNum($key){
		$order_num=$key.time().rand(1000,9999);
		return $order_num;
	}
	/**
	 *获取当天的时间戳
	 *$type=1,2,3
	 *00:00:00
	 *12:00;00
	 *23:59:59
	 */ 
	static function getUnitTime($type,$month=0,$day=0,$year=0){
		$time=null;
		if($month==0){
			$month=date('m');
		}
		if($day==0){
			$day=date('d');
		}
		if($year==0){
			$year=date('Y');
		}
		switch ($type) {
			case 1:
				$time=mktime(0,0,0,$month,$day,$year);
				break;
			case 2:
				$time=mktime(12,0,0,$month,$day,$year);
				break;
			case 3:
				$time=mktime(23,59,59,$month,$day,$year);
				break;
			default:
				$time=mktime(0,0,0,$month,$day,$year);
			break;
		}
		return $time;
	}
	/*
	 *生成随机验证码
	 *@$length字符串长度
	 *@$type 1纯数字,2数字加小写字母,3数字加大小写字母
	*/
	static function getRandCode($length=6,$type=1){
		$arr1=range(0,9);
		$arr2=range("a","z");
		$arr3=range("A","Z");
		$arr=array();
		if($type==1){
			$arr=$arr1;
		}elseif($type==2){
			$arr=array_merge($arr1,$arr2);
		}else{
			$arr=array_merge($arr1,$arr2,$arr3);
		}
		shuffle($arr);
		$buffer=array_slice($arr,0,$length);
		return implode("",$buffer);
	}

    // 是否为手机号码
    public static function is_mobile($mobile)
    {
        $reg = "/^1[34578]\d{9}$/";
        if (preg_match($reg, $mobile)) return true;
        return false;
    }

    // 是否为邮箱
    public static function is_email($email)
    {
        $reg = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
        if (preg_match($reg, $email)) return true;
        return false;
    }

    public static function userNameReplace($str = '')
    {
        if (!$str) return $str;
        if (self::is_mobile($str)) {
            $result = substr($str, 0, 3) . '****' . substr($str, -4);
            return $result;
        }
        $result = substr($str, 0, 2) . '*****' . substr($str, -2);
        return $result;
    }

    public static function getFirstCharter($str)
    {
        if (empty($str)) return '';
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'GB2312//IGNORE', $str);
        $s2 = iconv('GB2312', 'UTF-8//IGNORE', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return null;
    }

    // 获取毫秒时间戳
    public static function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }



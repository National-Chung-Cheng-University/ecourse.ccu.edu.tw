<?php
/*********************************************************************
	getssoCcuRight_config.php
	Last update : 2012/11/23
	系統需求：php4.0 + DOM

	常數：
		SYS_DOOR_URL	子系統端登入頁
		SYS_LOGIN_URL	子系統端登入確認程式
		SSO_DOOR_URL	SSO端首頁
	重要SESSION變數：
		$_SESSION['verifySso']		SSO登入方式辨識訊號
		$_SESSION['tokenSso']		SSO登入token資訊
		$_SESSION['sso_enterip']	使用者端登入IP
		$_SESSION['sso_personid']	學號或身份證字號(教職員工)
	函式：
		sso_getIP()					取得使用者IP
		chk_ssoRight($mix_info)		取得sso權限資訊
		sso_err_msgAlert($msg,$url)	錯誤訊息及導向頁面
		ssoLogOut()					SSO的登出導向

	註：使用if(isset($_SESSION['verifySso']) && trim($_SESSION['verifySso'])=='Y')判斷是否從sso端登入
		請修改'SYS_DOOR_URL'與'SYS_LOGIN_URL'的define值
		若是改在正式平台，請註解掉define('_TEST_PLATFORM',true);這一行
		若是BIG5編碼的子系統請註解掉header('Content-type: text/html; charset=UTF-8')
**********************************************************************/
//header('Content-type: text/html; charset=UTF-8'); //代簽入BIG5編碼的子系統時不需要這行, 檔案格式也需要存成ansi

//session不存在時，啟用session
if(!isset($_SESSION)) session_start();

//正式平台請註解掉這一行
//define('_TEST_PLATFORM', true);

if(defined('_TEST_PLATFORM')) { //測試平台
	define('SYS_DOOR_URL',  'http://cih.elearning.ccu.edu.tw');//子系統端登入頁
	define('SYS_LOGIN_URL', 'http://cih.elearning.ccu.edu.tw/php/index_login.php');//子系統端登入確認程式
	define('SSO_DOOR_URL',  'http://140.123.4.217/');
} else { //正式平台
	define('SYS_DOOR_URL',  'http://cih.elearning.ccu.edu.tw');//子系統端登入頁
	define('SYS_LOGIN_URL', 'http://cih.elearning.ccu.edu.tw/php/index_login_test.php');//子系統端登入確認程式
	define('SSO_DOOR_URL',  'http://portal.ccu.edu.tw/');
}

//取得使用者IP, http://www.jaceju.net/blog/archives/1913/
function sso_getIP() {
	foreach(array(	'HTTP_CLIENT_IP',
					'HTTP_X_FORWARDED_FOR',
					'HTTP_X_FORWARDED',
					'HTTP_X_CLUSTER_CLIENT_IP',
					'HTTP_FORWARDED_FOR',
					'HTTP_FORWARDED',
					'REMOTE_ADDR') as $key) {
        if(array_key_exists($key, $_SERVER)) {
            foreach(explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if((bool) filter_var($ip,	FILTER_VALIDATE_IP,
											FILTER_FLAG_IPV4 |
											FILTER_FLAG_NO_PRIV_RANGE |
											FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
    }
    return null;
}

//錯誤訊息及導向頁面
function sso_err_msgAlert($msg, $url) {
	if(!isset($url)) {
		echo '<script>alert("'.$msg.'");</script>';
	} else {
		if($url == '')
			echo '<script>alert("'.$msg.'");window.location.href="'.SSO_DOOR_URL.'";</script>';
		else
			echo '<script>alert("'.$msg.'");window.location.href="'.$url.'";</script>';
	}
}

//清空子系統session並關閉分頁
function ssoLogout() {
	//清空子系統端的session
	if(!isset($_SESSION))
		session_start();//have to start the session before you can unset or destroy it.
	session_unset();
	session_destroy();
	$_SESSION = array();
	echo '<noscript> <meta HTTP-EQUIV="REFRESH" content="0; url='.SSO_DOOR_URL.'"> </noscript>';//未開啟JS的話,用HTML轉到SSO入口
	//echo '<script> window.close(); </script>';
	echo '<script> top.close(); </script>';
}

//取得sso權限資訊,mix_info = miXd, ticket=要向sso驗證的ticket
function chk_ssoRight($mix_info, $ticket) {
	//2013.05.30 by linsy
	//$xml_txt = file_get_contents(SSO_DOOR_URL.'ssoCcuRightXML.php?cid='.$mix_info);
	$xml_txt = file_get_contents(SSO_DOOR_URL."ssoCcuRightXML_T.php?cid=$mix_info&ticket=$ticket");
	$resultArray = parseXML($xml_txt);

	if(count($resultArray) > 0) {
		if($resultArray['SESS_ALIVE'] == 'N') {
			$err_msg = '錯誤代碼：ENTER_SYS_004\n登入逾時\n~請重新登入~';
			return array(2, $err_msg, null, null, null);
		}else if($resultArray['SESS_ALIVE'] == 'Y') {
			return array(1, (string)$resultArray['IP'], (string)$resultArray['USER_ID'], (string)$resultArray['PERSON_ID']);
		}
	} else {
		$err_msg = '錯誤代碼：ENTER_SYS_003\n系統轉換異常\n~請重新登入~';
		return array(3, $err_msg, null, null, null);
	}
	/*********************************************************************
		$resultArray['SESS_ALIVE'] 	(登入是否逾時---Y/N)->$status(1,2,3)->1為成功，其他為異常
		$resultArray['IP']			(登入時,Client端IP) ->$enter_ip
		$resultArray['USER_ID']		(自訂帳號)          ->$user_id
		$resultArray['PERSON_ID']	(身份證字號)        ->$person_id
		$resultArray['ENTER_TIME']	(登入時間)			->$enter_time
	**********************************************************************/
}

// php4.0不使用DOM分析XML
function parseXML($xml_txt) {
	$order = array("\r\n", "\n", "\r");
	$replace = '';
	$err_msg = '';
	$newstr = str_replace($order, $replace, $xml_txt);
	//$newstr=eregi_replace(">"."[[:space:]]+"."< ",">< ",$newstr);
	if($xmlparser = xml_parser_create()) {
		xml_parser_set_option($xmlparser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($xmlparser, $newstr, $vals, $index);
		xml_parser_free($xmlparser);

		// 本來要動態加入array element,不過搞不定,只好寫死, 以後xml有變動的話,這邊再來改了
		$resultArray = array($vals[1]['tag'] => $vals[1]['value'], $vals[2]['tag'] => $vals[2]['value'], $vals[3]['tag'] => $vals[3]['value'], $vals[4]['tag'] => $vals[4]['value'], $vals[5]['tag'] => $vals[5]['value']);
		/* 搞不定
		$i=0;
		$resultArray=array('test','test');
		foreach($vals as $element)
		{
			if($element['level'] == '2')
			{
				$s1=$element['tag'];
				$s2=$element['value'];
				//if($i==0){
				//	$resultArray = array($s1 => $s2);
				//}else{
					$tmpArray = array($s1 => $s2);
					//echo"tmpArray is</br>";
					//print_r($tmpArray);
					//echo"</br>";
					array_push($resultArray,$tmpArray);
				//}
				//$i++;
			}else{
				$i++;
				continue;
			}
		}
		*/
		return $resultArray;
	} else {
		// return an empty array when failure
		$resultArray;
		return $resultArray;
	}
}
?>

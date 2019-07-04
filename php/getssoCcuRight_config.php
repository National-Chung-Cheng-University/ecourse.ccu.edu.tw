<?php
/*********************************************************************
	getssoCcuRight_config.php
	Last update : 2012/11/23
	�t�λݨD�Gphp4.0 + DOM

	�`�ơG
		SYS_DOOR_URL	�l�t�κݵn�J��
		SYS_LOGIN_URL	�l�t�κݵn�J�T�{�{��
		SSO_DOOR_URL	SSO�ݭ���
	���nSESSION�ܼơG
		$_SESSION['verifySso']		SSO�n�J�覡���ѰT��
		$_SESSION['tokenSso']		SSO�n�Jtoken��T
		$_SESSION['sso_enterip']	�ϥΪ̺ݵn�JIP
		$_SESSION['sso_personid']	�Ǹ��Ψ����Ҧr��(��¾���u)
	�禡�G
		sso_getIP()					���o�ϥΪ�IP
		chk_ssoRight($mix_info)		���osso�v����T
		sso_err_msgAlert($msg,$url)	���~�T���ξɦV����
		ssoLogOut()					SSO���n�X�ɦV

	���G�ϥ�if(isset($_SESSION['verifySso']) && trim($_SESSION['verifySso'])=='Y')�P�_�O�_�qsso�ݵn�J
		�Эק�'SYS_DOOR_URL'�P'SYS_LOGIN_URL'��define��
		�Y�O��b�������x�A�е��ѱ�define('_TEST_PLATFORM',true);�o�@��
		�Y�OBIG5�s�X���l�t�νе��ѱ�header('Content-type: text/html; charset=UTF-8')
**********************************************************************/
//header('Content-type: text/html; charset=UTF-8'); //�Nñ�JBIG5�s�X���l�t�ήɤ��ݭn�o��, �ɮ׮榡�]�ݭn�s��ansi

//session���s�b�ɡA�ҥ�session
if(!isset($_SESSION)) session_start();

//�������x�е��ѱ��o�@��
//define('_TEST_PLATFORM', true);

if(defined('_TEST_PLATFORM')) { //���ե��x
	define('SYS_DOOR_URL',  'http://cih.elearning.ccu.edu.tw');//�l�t�κݵn�J��
	define('SYS_LOGIN_URL', 'http://cih.elearning.ccu.edu.tw/php/index_login.php');//�l�t�κݵn�J�T�{�{��
	define('SSO_DOOR_URL',  'http://140.123.4.217/');
} else { //�������x
	define('SYS_DOOR_URL',  'http://cih.elearning.ccu.edu.tw');//�l�t�κݵn�J��
	define('SYS_LOGIN_URL', 'http://cih.elearning.ccu.edu.tw/php/index_login_test.php');//�l�t�κݵn�J�T�{�{��
	define('SSO_DOOR_URL',  'http://portal.ccu.edu.tw/');
}

//���o�ϥΪ�IP, http://www.jaceju.net/blog/archives/1913/
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

//���~�T���ξɦV����
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

//�M�Ťl�t��session����������
function ssoLogout() {
	//�M�Ťl�t�κݪ�session
	if(!isset($_SESSION))
		session_start();//have to start the session before you can unset or destroy it.
	session_unset();
	session_destroy();
	$_SESSION = array();
	echo '<noscript> <meta HTTP-EQUIV="REFRESH" content="0; url='.SSO_DOOR_URL.'"> </noscript>';//���}��JS����,��HTML���SSO�J�f
	//echo '<script> window.close(); </script>';
	echo '<script> top.close(); </script>';
}

//���osso�v����T,mix_info = miXd, ticket=�n�Vsso���Ҫ�ticket
function chk_ssoRight($mix_info, $ticket) {
	//2013.05.30 by linsy
	//$xml_txt = file_get_contents(SSO_DOOR_URL.'ssoCcuRightXML.php?cid='.$mix_info);
	$xml_txt = file_get_contents(SSO_DOOR_URL."ssoCcuRightXML_T.php?cid=$mix_info&ticket=$ticket");
	$resultArray = parseXML($xml_txt);

	if(count($resultArray) > 0) {
		if($resultArray['SESS_ALIVE'] == 'N') {
			$err_msg = '���~�N�X�GENTER_SYS_004\n�n�J�O��\n~�Э��s�n�J~';
			return array(2, $err_msg, null, null, null);
		}else if($resultArray['SESS_ALIVE'] == 'Y') {
			return array(1, (string)$resultArray['IP'], (string)$resultArray['USER_ID'], (string)$resultArray['PERSON_ID']);
		}
	} else {
		$err_msg = '���~�N�X�GENTER_SYS_003\n�t���ഫ���`\n~�Э��s�n�J~';
		return array(3, $err_msg, null, null, null);
	}
	/*********************************************************************
		$resultArray['SESS_ALIVE'] 	(�n�J�O�_�O��---Y/N)->$status(1,2,3)->1�����\�A��L�����`
		$resultArray['IP']			(�n�J��,Client��IP) ->$enter_ip
		$resultArray['USER_ID']		(�ۭq�b��)          ->$user_id
		$resultArray['PERSON_ID']	(�����Ҧr��)        ->$person_id
		$resultArray['ENTER_TIME']	(�n�J�ɶ�)			->$enter_time
	**********************************************************************/
}

// php4.0���ϥ�DOM���RXML
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

		// ���ӭn�ʺA�[�Jarray element,���L�d���w,�u�n�g��, �H��xml���ܰʪ���,�o��A�ӧ�F
		$resultArray = array($vals[1]['tag'] => $vals[1]['value'], $vals[2]['tag'] => $vals[2]['value'], $vals[3]['tag'] => $vals[3]['value'], $vals[4]['tag'] => $vals[4]['value'], $vals[5]['tag'] => $vals[5]['value']);
		/* �d���w
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

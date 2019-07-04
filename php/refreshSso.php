<?php
/*********************************************************************
	refreshSso.php
	�l�t�κݩ���SSO�ݪ�session�s���ɶ�
	(�l�t�Ϊ��C��page��include��php��, �C����s�����N�|�۰ʩ���sso�s���ɶ��F)
	Last update : 2012/07/19
****�t�λݨD�Gphp4.0****

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
		chk_ssoRefresh($mix_info)	����sso��session�s���ɶ�
		err_msgAlert($msg,$url)		���~�T���ξɦV����
		ssoLogOut()					SSO���n�X�ɦV

	��1�G�ϥ�if(isset($_SESSION['verifySso']) and trim($_SESSION['verifySso'])=="Y")�P�_�O�_�qsso�ݵn�J
	     �Y�O��b�������x�A�е��ѱ�define('_TEST_PLATFORM',true);�o�@��
	��2�G�Y�O�DUTF-8���l�t�νе��ѱ�header('Content-type: text/html; charset=UTF-8')
**********************************************************************/
//header('Content-type: text/html; charset=UTF-8'); //�Nñ�J�DUTF-8���l�t�ήɤ��ݭn�o��, �ɮ׮榡�]�ݭn�s��ansi

//session���s�b�ɡA�ҥ�session
if(!isset($_SESSION)) session_start();

//�������x�е��ѱ��o�@��
//define('_TEST_PLATFORM', true);

if(defined('_TEST_PLATFORM')) { //���ե��x
	//�`�Ʃw�q
	define('SYS_DOOR_URL', 'http://ecourse.elearning.ccu.edu.tw'); //�l�t�κݵn�J��
	define('SYS_LOGIN_URL', 'http://ecourse.elearning.ccu.edu.tw/php/index_login.php'); //�l�t�κݵn�J�T�{�{��
	define('SSO_DOOR_URL', 'http://140.123.4.217/'); //sso�ݭ���
} else { //�������x
	define('SYS_DOOR_URL', 'http://ecourse.elearning.ccu.edu.tw'); //�l�t�κݵn�J��
	define('SYS_LOGIN_URL', 'http://ecourse.elearning.ccu.edu.tw/php/index_login.php'); //�l�t�κݵn�J�T�{�{��
	define('SSO_DOOR_URL', 'http://portal.ccu.edu.tw/');
}

//���o�ϥΪ�IP
function sso_getIP(){
	if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$route=$_SERVER['HTTP_X_FORWARDED_FOR'];
		$ip=split(',', $route);
	}else{
		$route='';
	}
	$ip=(empty($route))? $_SERVER['REMOTE_ADDR']: $ip[0];
	return $ip;
}

//���osso�v����T
//����1���ܼ�(token��T)�A�^��5���ܼ�
function chk_ssoRight($mix_info) {


	$xml_txt = file_get_contents(SSO_DOOR_URL.'ssoCcuRightXML.php?cid='.$mix_info);
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








//�H�W�d�լO���F�ŦX�������������(�PrefreshSso.php�P�B)
}

//����sso��session�s���ɶ�
//����1���ܼ�(token��T)
function chk_ssoRefresh($mix_info) {
	

	$xml_txt = file_get_contents(SSO_DOOR_URL.'ssoCcuRightXML.php?cid='.$mix_info);
	$resultArray = parseXML($xml_txt);
	if(count($resultArray) > 0) {
		if($resultArray['SESS_ALIVE'] == 'N') {
			//$err_msg = '���~�N�X�GENTER_SYS_004\n�n�J�O��\n~�Э��s�n�J~';
			$err_msg = '���~�N�X�GENTER_SYS_007\n�t���ഫ���`\n~�Э��s�n�J~';
			return array(0, $err_msg, null, null, null);
		} else if($resultArray['SESS_ALIVE'] == 'Y') {
			return array(1, '');
		}
	} else {
		//$err_msg = '���~�N�X�GENTER_SYS_003\n�t���ഫ���`\n~�Э��s�n�J~';
		return array(0, '���~�N�X�GENTER_SYS_006\n�n�J�O��\n~�Э��s�n�J~');
		return array(3, $err_msg, null, null, null);
	}




//�H�W�d�լO���F�ŦX�������������(�PrefreshSso.php�P�B)
}

//���~�T���ξɦV����
//����2���ܼ�(���~�T��,�ɦV����)
function err_msgAlert($msg, $url) {
	echo '<script>alert("'.$msg.'");window.location.href="'.$url.'";</script>';
}

//�M�Ťl�t��session����������
function ssoLogout() {
	//�M�Ťl�t�κݪ�session
	if(!isset($_SESSION))
		session_start();//have to start the session before you can unset or destroy it.
	session_unset();
	session_destroy();
	$_SESSION = array();
	//header('Location: '.SSO_DOOR_URL);
	echo '<noscript> <meta HTTP-EQUIV="REFRESH" content="0; url='.SSO_DOOR_URL.'"> </noscript>';//���}��JS����,��HTML���SSO�J�f
	//echo '<script> window.close(); </script>';
	echo '<script> top.close(); </script>';
}

//�p�G�O�qsso�Nñ�J�����p�~����session(in sso server)�s���ɶ�
/*
if(isset($_SESSION['verifySso']) and trim($_SESSION['verifySso'])=='Y') {
	//����session�s��
	list($status,$errorMsg) = chk_ssoRefresh($_SESSION['tokenSso']);
	if($status != 1){ //sso�ݵn�J����
		err_msgAlert($errorMsg, SSO_DOOR_URL);
	}

	//�l�t�Τw�g�n�J,�S�A�qSSO���U�s����l�t�Ϊ����p,�]�ݭn���s�ɦV��l�t�Ϊ��n�J�T�{�{���]sso�n�J�y�{,�ɻ�sso�n�J��T
}
*/

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

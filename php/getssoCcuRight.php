<?php
/*********************************************************************
	getssoCcuRight.php
	�l�t�κݾɤJSSO������T
	Last update : 2012/11/23
	�t�λݨD�Gphp4.0/5.0 + DOM
	�`�ƻP�ܼƻ����Ш�getssoCcuRight_config.php
**********************************************************************/
include_once('getssoCcuRight_config.php');

$miXd  = (isset($_GET['miXd'])) ? trim($_GET['miXd']) : '';
$ticket = (isset($_GET['ticket'])) ? trim($_GET['ticket']) : '';

if($miXd != '' && $ticket != '') {
	//����SSO���
	list($status, $enter_ip, $user_id, $person_id) = chk_ssoRight($miXd, $ticket);
	if($status == 1) {//sso�ݵn�J���\���ʧ@
		//�N��T�ᵹ�l�t�κݪ�session�h�ާ@
		$_SESSION['sso_enterip']	= $enter_ip;	//�ϥΪ̺ݵn�JIP
		$_SESSION['sso_personid']	= $person_id;	//�Ǹ��Ψ����Ҧr��(��¾���u)
		$_SESSION['tokenSso']		= $miXd;		//sso token
		$_SESSION['verifySso']		= 'Y';			//sso�n�J�ѧO

		//�����ܤl�t�κݵn�J�T�{���{���B�z�U�l�t�κݩһݭn���B�~��T
		header('Location: '.SYS_LOGIN_URL);
	} else {
		sso_err_msgAlert($enter_ip, SYS_DOOR_URL);//���Ѯ�enter_ip�|�O���~�T��
	}
} else {
	sso_err_msgAlert('���~�N�X�GENTER_SYS_001\n�n�J��T���~�I\n~�Э��s�n�J~', SYS_DOOR_URL);
}

?>

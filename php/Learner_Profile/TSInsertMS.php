<?php
/**************************/
/*�ɦW:TSInsertMS.php*/
/*����:�ǥͷs�W�޲z�t��*/
/*�����ɮ�:*/
/*TSInsertFrame1.php*/
/*TSAreaInsert1.php*/
/*TSFileInsert1.php*/
/*************************/
require 'fadmin.php';
update_status ("�s�W�ǥ�");

if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"�v�����~");
	exit;
}
if($check != 2)
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}
include("class.FastTemplate.php3");
$tpl = new FastTemplate("./templates");
if($version=="C")
	$tpl->define(array(add_student => "TSInsertMS_Ch.tpl"));
else
	$tpl->define(array(add_student => "TSInsertMS_En.tpl"));
$tpl->parse(BODY, "add_student");
$tpl->FastPrint("BODY");
?>

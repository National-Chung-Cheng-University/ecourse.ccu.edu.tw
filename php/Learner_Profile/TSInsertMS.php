<?php
/**************************/
/*檔名:TSInsertMS.php*/
/*說明:學生新增管理系統*/
/*相關檔案:*/
/*TSInsertFrame1.php*/
/*TSAreaInsert1.php*/
/*TSFileInsert1.php*/
/*************************/
require 'fadmin.php';
update_status ("新增學生");

if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}
if($check != 2)
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
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

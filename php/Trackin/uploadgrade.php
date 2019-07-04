<?php
require 'fadmin.php';
update_status ("上傳成績中");

if($action == "upload_excel"){
		upload_excel("TGShowFrame.php");
}
else if($action == "upload_excel2"){
		$course_id = $course;
		upload_excel("TGShowFrame2.php");
}

//------上傳Excel
function upload_excel ($go_page) {
	global $version, $user_id, $course_id, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version == "C") {
		$tpl->define(array(main=>"uploadgrade.tpl"));
	}
	else {
		$tpl->define(array(main=>"uploadgrade_E.tpl"));
	}
	$tpl->assign( CID, $course_id);
	$tpl->assign( GOPAGE, $go_page);
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->parse(BODY,"main");
	$tpl->FastPrint("BODY");
}
?>

<?php
	require 'fadmin.php';
	
	if ( !(isset($PHPSESSID) && session_check_admin($PHPSESSID)) )
		show_page( "not_access.tpl" ,"ลvญญฟ๙ป~");
	session_register("course_id");
	session_register("id");
	$course_id = $courseid;
	$id = $userid;
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "course_info.tpl") );
	$tpl->assign ( BARFILE, "./course_info_t.php?cname=$cname");
	//$tpl->assign ( NEWSFILE, "../news/news.php" );
	$tpl->parse( BODY, "body");
	$tpl->FastPrint("BODY");

?>
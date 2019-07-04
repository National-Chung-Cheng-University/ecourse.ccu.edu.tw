<?php
	require 'fadmin.php';

	if ( !(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) ) ) {
		show_page( "not_access.tpl" ,"ลvญญฟ๙ป~");
	}
	
	global $course_id, $check, $teacher, $version, $query, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "Tool_menu.tpl") );
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign( PHPID , $PHPSESSID );
	$tpl->parse( BODY, "body");
	$tpl->FastPrint("BODY");

?>
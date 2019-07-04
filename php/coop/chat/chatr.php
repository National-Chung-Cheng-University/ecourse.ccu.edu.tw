<?php
	require 'fadmin.php';
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"ลvญญฟ๙ป~");
	}

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "chatr.tpl") );

	$tpl->assign ( NIC,  $nickname );
	$tpl->assign ( NC,  $colorNum );
	$tpl->assign ( PORT,  $port );
	$tpl->assign ( ID,  $user_id );
	$ip = getenv ( "SERVER_NAME" );
	if ( $ip == "" )
		$ip = $SERVER_NAME;
	$tpl->assign ( HOST, $ip );
	if ( $teacher == 1 )
		$tpl->assign ( CHARACTER,  "teacher" );
	else
		$tpl->assign ( CHARACTER,  "student" );
	$tpl->assign ( CD,  $course_id );

	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
?>
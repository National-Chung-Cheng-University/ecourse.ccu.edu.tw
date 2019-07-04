<?php
	require 'fadmin.php';
	update_status ("線上討論室");
	
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "chat.tpl") );
	
	if ( $teacher != 1 && $guest != 1 && $admin != 1  )
		add_log ( 4, $user_id, "", $course_id );

	$tpl->assign ( NIC,  $nickname );
	$tpl->assign ( NC,  $colorNum );
	$tpl->assign ( PORT,  $port );
	$tpl->assign ( PHPSID, $PHPSESSID );
//	$tpl->assign ( ID,  $user_id );
//	$ip = getenv ( "SERVER_NAME" );
//	if ( $ip == "" )
//		$ip = $SERVER_NAME;
//	$tpl->assign ( HOST, $ip );
//	if ( $teacher == 1 )
//		$tpl->assign ( TEACHER,  $teacher );
//	else
//		$tpl->assign ( TEACHER,  0 );
//	$tpl->assign ( CD,  $course_id );

	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
?>
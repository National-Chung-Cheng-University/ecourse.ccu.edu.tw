<?php
	require 'fadmin.php';
	update_status ("eBoard");
	
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"ลvญญฟ๙ป~");
	}
	if( !isset( $room ) || $room == '' ) {
		header( "Location: ./eboard_int.php" );
		exit();
	}
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "eboard.tpl") );
	
	$ip = getenv ( "SERVER_NAME" );
	if ( $ip == "" )
		$ip = $SERVER_NAME;
	$tpl->assign (SERVERNAME, $ip );

	$tpl->assign ( USER_NAME, $username );
	$tpl->assign ( ROOM_NAME,  $room );
	$tpl->assign ( PHPSID, $PHPSESSID );

	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
?>
<?php
	require 'fadmin.php';
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "select nickname, name, id from user where id ='$user_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "資料庫連結錯誤!!" );
		exit;
	}
	if ( $result = mysql_db_query( $DB, $Q1 ) )
		$row = mysql_fetch_array( $result );

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "eboard_int.tpl") );

	if ( $row['nickname'] != NULL )
		$name = $row['nickname'];
	else if ( $row['name'] != NULL )
		$name = $row['name'];
	else
		$name = $row['id'];
		
	$ip = getenv ( "SERVER_NAME" );
	if ( $ip == "" )
		$ip = $SERVER_NAME;
	$tpl->assign (SERVERNAME, $ip );

	$tpl->assign ( USER_NAME, $name );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
?>
<?php
	require 'fadmin.php';
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	if ( $version == "C" )
		$tpl->define ( array ( body => "email_stu.tpl" ) );
	else
		$tpl->define ( array ( body => "email_stu_E.tpl" ) );
	$tpl->define_dynamic ( "t_mail" , "body" );
	$tpl->define_dynamic ( "th_mail" , "body" );
	
	$flag = 0;		
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	$Q1 = "select u.email, u.name, u.id FROM user u, teach_course tc where tc.teacher_id = u.a_id and tc.course_id = '$course_id' and tc.year = '$course_year' and tc.term = '$course_term' and u.authorization = '1'";
	$Q2 = "select u.email, u.name, u.id FROM user u, teach_course tc where tc.teacher_id = u.a_id and tc.course_id = '$course_id' and tc.year = '$course_year' and tc.term = '$course_term' and u.authorization = '2'";
	$tpl->assign( SKINNUM , $skinnum );
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "$message - 資料庫連結錯誤!!";
	}
	if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "$message - 資料庫讀取錯誤!!";
	}
	else {
		while ( $row = mysql_fetch_array( $result ) ) {
			if ( $row['email'] != "" ) {
				$flag = 1;
				$tpl->assign( MAILONE , $row['email'] );

				if ( $row['name'] != "" )
					$tpl->assign( NAMEONE , $row['name'] );
				else
					$tpl->assign( NAMEONE , $row['id'] );
				$tpl->assign( DATAONE , "," );
				$tpl->parse ( T_MAIL, ".t_mail" );
			}
		}
	}
	if ( $flag == 0 ) {
		$tpl->assign( MAILONE , "" );
		$tpl->assign( NAMEONE , "" );
		if ( $version == "C" )
			$tpl->assign( DATAONE , "此課程無教師email資料" );
		else
			$tpl->assign( DATAONE , "No Teacher email information" );
		$tpl->parse ( T_MAIL, ".t_mail" );
	}
	
	$flag = 0;
	if ( !($result = mysql_db_query( $DB, $Q2 ) ) ) {
		$message = "$message - 資料庫讀取錯誤!!";
	}
	else {
		while ( $row = mysql_fetch_array( $result ) ) {
			if ( $row['email'] != "" ) {
				$flag = 1;
				$tpl->assign( MAILTWO , $row['email'] );

				if ( $row['name'] != "" )
					$tpl->assign( NAMETWO , $row['name'] );
				else
					$tpl->assign( NAMETWO , $row['id'] );
				$tpl->assign( DATATWO , "," );
				$tpl->parse ( TH_MAIL, ".th_mail" );
			}
		}
	}
	if ( $flag == 0 ) {
		$tpl->assign( MAILTWO , "" );
		$tpl->assign( NAMETWO , "" );
		if ( $version == "C" )
			$tpl->assign( DATATWO , "此課程無助教email資料" );
		else
			$tpl->assign( DATATWO , "No Other email information" );
		$tpl->parse ( TH_MAIL, ".th_mail" );
	}
	
	$tpl->assign( MES , $message );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
	
	
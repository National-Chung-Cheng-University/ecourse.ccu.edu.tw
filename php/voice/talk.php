<?php
	require 'fadmin.php';
	update_status ("互動聊天室");
	if ( isset($PHPSESSID) && $check = session_check_teach($PHPSESSID) ) {
		if ( $check == 2 ) {
			if ( $ip != "" && $topic != "" )
				show_page_t ();
			else
				show_page_p();
		}
		else
			show_page_t ();
	}
	else if ( $version == "C" )
		show_page( "not_access.tpl", "你的權限錯誤，請重新登入!!");
	else
		show_page( "not_access.tpl", "Access Deny!!");
	
	function show_page_t(){
		global $version, $ip, $topic, $user_id, $course_id, $check, $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select a_id, name, nickname from user where id ='$user_id'";

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		$result = mysql_db_query( $DB, $Q1 );
		$row = mysql_fetch_array( $result );
		
		include("class.FastTemplate.php3");
		global $REMOTE_ADDR , $SERVER_NAME, $HTTP_X_FORWARDED_FOR;
		$iph = getenv("SERVER_NAME");
		if ( $iph == "" )
			$iph = $SERVER_NAME;
		$tpl = new FastTemplate ( "./templates" );
		
		if ( $check == 2 ) {
			add_log ( 10, $user_id, $ip, $course_id, "", $topic );
			if ( $version == "C" )
				$tpl->define ( array ( body => "talk_s.tpl" ) );
			else
				$tpl->define ( array ( body => "talk_s_E.tpl" ) );
			
			$tpl->assign( IPS, $ip );
			$tpl->assign( TOPIC, $topic );
		}
		else {
			if ( $version == "C" )
				$tpl->define ( array ( body => "talk_c.tpl" ) );
			else
				$tpl->define ( array ( body => "talk_c_E.tpl" ) );
			if ( mysql_num_rows( $result ) != 0 ) {
				$Q3 = "select * from log where event_id ='10'";
				if ( $result3 = mysql_db_query( $DB.$course_id, $Q3 ) )
					$row3 = mysql_fetch_array( $result3 );
				$Q4 = "select id, name , nickname from user where a_id = '".$row3['user_id']."'";
				if ( $result4 = mysql_db_query( $DB, $Q4 ) )
					$row4 = mysql_fetch_array( $result4 );
			}

			$tpl->assign( IPS, $row3['tag1'] );
			$tpl->assign( TOPIC, $row3['tag4'] );
			if ( $row4['name'] != NULL )
				$tpl->assign( INTS, $row4['name'] );
			else if ( $row4['nickname'] != NULL )
				$tpl->assign( INTS, $row4['nickname'] );
			else
				$tpl->assign( INTS, $row4['id'] );
		}
		if ( $row['name'] != NULL )
			$name = $row['name'];
		else if ( $row['nickname'] != NULL )
			$name = $row['nickname'];
		else
			$name = $user_id;
		$tpl->assign( USER, $name );
		$tpl->assign( IPH, $iph );

		$Q5 = "select name from course where a_id ='$course_id'";
		$result5 = mysql_db_query( $DB, $Q5 );
		$row5 = mysql_fetch_array( $result5 );
		$tpl->assign( COURSE, $row5['name'] );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}


	function show_page_p () {
		global $version, $REMOTE_ADDR, $HTTP_X_FORWARDED_FOR, $ip, $skinnum;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if ( $version == "C" )
			$tpl->define ( array ( body => "talk.tpl" ) );
		else
			$tpl->define ( array ( body => "talk_E.tpl" ) );
		$tpl->assign( SKINNUM , $skinnum );
		if ( $ip == "" )
			$ip = getenv ( "REMOTE_ADDR" );
		if ( $ip == "" )
			$ip = $HTTP_X_FORWARDED_FOR;
		if ( $ip == "" )
			$ip = $REMOTE_ADDR;
		$tpl->assign( IPS, $ip );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
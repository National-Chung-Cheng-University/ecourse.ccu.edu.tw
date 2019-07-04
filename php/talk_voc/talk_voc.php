<?php
	require 'fadmin.php';
	update_status ("語音聊天室");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	if ( $guest == "1" ) {

	}
	show_main();

	function show_main () {
		global $SERVER_NAME, $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $user_id, $PHPSESSID ;
		
		$Q1 = "select c.name , c.course_no from course c where c.a_id = '$course_id'";
		$Q2 = "select a_id, nickname, name from user where id='$user_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			echo ( "資料庫連結錯誤!!" );
			exit;
		}
		else if ( $result = mysql_db_query( $DB, $Q1 ) ) {
			$row = mysql_fetch_array ( $result );
		}
		
		if ($result2 = mysql_db_query( $DB, $Q2)) {
			if( $row2 = mysql_fetch_array($result2)) {
				if($row2['nickname'] != NULL) {
					$name = $row2['nickname'];
				}
				else if ($row2['name'] != NULL) {
					$name = $row2['name'];
				}
				else {
					$name = $user_id;
				}
			}
		}
		
		$ip = getenv("SERVER_NAME");
		if ( $ip == "" )
			$ip = $SERVER_NAME;

		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "talk_voc.tpl" ) );
		$tpl->assign( SIP, $ip );
		$tpl->assign( AID, $course_id );
		$tpl->assign( CNAME, $row['name'] );
		$tpl->assign( CID, $row['course_no'] );
		$tpl->assign( UNAME, $name );
		$tpl->assign( PHPSID, $PHPSESSID );
		
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
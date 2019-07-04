<?
	require 'fadmin.php';
	update_status ("發表留言");
	session_id($PHPSESSID);
	session_start();
	if(session_check_teach($PHPSESSID) < 1 || check_group ( $course_id, $coopgroup, $coopcaseid ) == 0 ) {
		show_page("not_access.tpl", "你沒有權限執行此功能.\nYou have no permission to perform this function.");
		exit();
	}

	if ( isset($content) || isset($page) ) {
	   	if ( $content == "" ) {
	   		show_page_d ( 2 );
	   	}
	   	else {
			global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
			$link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD);
			if ( $page == "http://" )
				$page = null;
			if ( $ip == "" )
				$ip = getenv ( "REMOTE_ADDR" );
			if ( $ip == "" )
				$ip = $HTTP_X_FORWARDED_FOR;
			if ( $ip == "" )
				$ip = $REMOTE_ADDR;
			$content = addslashes( $content );
			$Q1 = "select * from user where id = '$user_id'";
			$result1 = mysql_db_query( $DB, $Q1 ) or die("資料庫查詢錯誤, $Q1");
			if(mysql_num_rows($result1) > 0) {
				$row = mysql_fetch_array($result1);
				$a_id = $row['a_id'];
				$email = $row['email'];
				$page = $row['php'];
				$Q2 = "insert into guestbook_".$coopcaseid." ( user_id, group_num, content, host, date ) values ( '$a_id', '$coopgroup', '$content', '$ip','".date("Y-m-d H:i:s")."' )";
				$result2 = mysql_db_query( $DBC.$course_id, "$Q2" ) or die("資料庫查詢錯誤, $Q2");
				if( (check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2) && (strcmp($guest,"1") != 0) && (session_check_teach($PHPSESSID) != 3)) {
					add_log_coop( 3, $user_id, "", $course_id, "", "", $coopgroup, $coopcaseid );
				}
			}
			header("Location: ./guestbookm.php?PHPSESSID=$PHPSESSID" );
		}
	}
	else {
		show_page_d( 1 );
	}
	
	function show_page_d ( $option ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $user_id, $content, $skinnum, $coopgroup;
		$link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD);
		$Q1 = "select * from user where id = '$user_id'";
		$result1 = mysql_db_query( $DB, $Q1 ) or die("資料庫查詢錯誤, $Q1");
		if(mysql_num_rows($result1) > 0) {
			$row = mysql_fetch_array($result1);
			$email = $row['email'];
			$page = $row['php'];
			if ( $row['nickname'] != "" ) {
				$name = $row['nickname'];
			}
			else if ( $row['name'] != "" ) {
				$name = $row['name'];
			}
			else
				$name = $row['id'];
		}
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "guestbook.tpl") );
		if ( $option == 2 ) {
			$tpl->assign( MSG , "No Content" );
		}
		else {
			$tpl->assign( MSG , "　" );
		}
		
		$tpl->assign( HOME , $page );
		$tpl->assign( NAME , $name );
		$tpl->assign( EMAIL , $email );
		$content = stripslashes( $content );
		$tpl->assign( CONTENT , $content );
		$tpl->assign( SKINNUM, $skinnum );
		$tpl->assign( TITLE, "第".$coopgroup."組留言版" );

		$tpl->parse( BODY, "body");
		$tpl->FastPrint("BODY");
	}
?>
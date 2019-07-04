<?
	require 'fadmin.php';
	update_status ("管理留言");
	session_id($PHPSESSID);
	session_start();
	if(session_check_teach($PHPSESSID) < 1 || check_group ( $course_id, $coopgroup, $coopcaseid ) != 2 ) {
		show_page("not_access.tpl", "你沒有權限執行此功能.\nYou have no permission to perform this function.");
		exit();
	}

	if ( $action == "delete" ) {
		delete();
	}
	show_page_d ();

	function delete () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $coopcaseid, $course_id, $a_id;
	
		$link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD);
		$Q1 = "delete from guestbook_".$coopcaseid."  where group_num = '$coopgroup' and a_id = '$a_id'";	
		$result = mysql_db_query( $DBC.$course_id, $Q1 ) or die("資料庫查詢錯誤, $Q1");
	}

	function show_page_d () {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "guestadmin.tpl") );
		$tpl->define_dynamic ( "menu" , "body" );
	
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $skinnum, $coopcaseid , $coopgroup, $course_id, $REMOTE_ADDR;
		if ( $ip == "" )
			$ip = getenv ( "REMOTE_ADDR" );
		if ( $ip == "" )
			$ip = $HTTP_X_FORWARDED_FOR;
		if ( $ip == "" )
			$ip = $REMOTE_ADDR;
	
		$link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD);
		$Q1 = "select * from guestbook_".$coopcaseid." where group_num = '$coopgroup' order by mtime DESC";	
		$result = mysql_db_query( $DBC.$course_id, $Q1 ) or die("資料庫查詢錯誤, $Q1");
		
		$tpl->assign( NAME , "" );
		$tpl->assign( EMAIL , "" );
		$tpl->assign( HOST , "" );
		$tpl->assign( DATE , "" );
		$tpl->assign( CONTENT, "" );
		$tpl->assign( HOME, "" );
		$tpl->assign( SKINNUM, $skinnum );
		$tpl->assign( TITLE, "第".$coopgroup."組留言版" );
		$tpl->assign( ADMIN , "<a href=./guestadmin.php>管理介面</a>" );
		while ( $rows = mysql_fetch_array( $result ) ) {
			$Q1 = "select * from user where a_id = '".$rows['user_id']."'";
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
			$tpl->assign( NAME , $name );
			$tpl->assign( EMAIL , $email );
			$tpl->assign( HOST , $rows["host"] );
			$tpl->assign( DATE , $rows["date"] );
			$tpl->assign( AID , $rows["a_id"] );
			$content = $rows["content"];
	   		$content = str_replace ( "\n", "<BR>", $content );
	   		$content = stripslashes( $content );
			$tpl->assign( CONTENT, $content );
			if ( $page != "" ) {
				$tpl->assign( HOME, "<tr bgcolor=\"#F4FBFF\">\n<td>Homepage：<a href=\"".$page."\" target=\"_blank\">".$page."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>\n" );
			}
			else {
				$tpl->assign( HOME, "" );
			}
			$tpl->parse ( MENU, ".menu" );
	   	}
		$tpl->parse( BODY, "body");
		$tpl->FastPrint("BODY");
	}
?>
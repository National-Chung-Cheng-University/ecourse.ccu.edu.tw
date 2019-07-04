<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( $a_id != "") {
			if ( ($error = del_teach( )) == -1 )
				show_page_d ( "教師 $id 刪除成功!!" );
			else
				show_page_d ( $error );
			
		}
		else if ( isset($a_id) )
			show_page_d ( "教師帳號錯誤" );
		else
			show_page_d ( );
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!" );
	
	function del_teach ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $a_id ;
		$C1 = "select course_id from teach_course where teacher_id = '$a_id'";
		$Q1 = "delete from log where user_id = '$a_id'";
		$Q2 = "delete from gbfriend where my_id = '$a_id' or friend_id='$a_id'";
		$Q3 = "delete from user where a_id = '$a_id'";
		$Q4 = "delete from teach_course where teacher_id = '$a_id' and course_id = '$course_id'";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		for ( $i = 1 ; $i <= 3 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_db_query( $DB, $$Q ) ) ) {
				$error = "資料庫刪除錯誤!!";
				return $error;
			}
		}
		if ( !($result1 = mysql_db_query( $DB, $C1 ) ) ) {
				$error = "資料庫讀取錯誤!!";
				return $error;
		}
		if ( mysql_num_rows( $result1 ) == 0 ) {
			while ( $row1 = mysql_fetch_array( $result1 ) ) {
				if ( !($result = mysql_db_query( $DB.$row1['course_id'], $Q1 ) ) ) {
					$error = "資料庫刪除錯誤!!";
					return $error;
				}
			}
		}
		if ( !($result = mysql_db_query( $DB, $Q4 ) ) ) {
			$error = "資料庫刪除錯誤!!";
			return $error;
		}
		
		return -1;
	}
	
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "del_user.tpl" ) );
		$tpl->define_dynamic ( "tech_list" , "body" );

		$tpl->assign( FORMSTART , "<form>" );
		$tpl->assign( TYPE , "身分" );
		$tpl->assign( NAME , "姓名" );
		$tpl->assign( UID , "帳號" );
		$tpl->assign( PASS , "教師密碼" );
		$tpl->assign( BUTTON , "刪除此使用者" );
		$tpl->parse ( TECH_LIST, ".tech_list" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select a_id, id, name, authorization, pass FROM user WHERE authorization = '1' or authorization = '2' order by id";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else
			while ( $row = mysql_fetch_array( $result ) ) {
				$tpl->assign( FORMSTART , "<form method=post action=./del_user.php>" );
				if ( $row["authorization"] == "1" )
					$tpl->assign( TYPE , "教師" );
				else
					$tpl->assign( TYPE , "助教" );
				$tpl->assign( UID , $row["id"] );
				$tpl->assign( NAME , $row["name"] );
				if ( $row["pass"] == "" )
					$tpl->assign( PASS , "　" );
				else
					$tpl->assign( PASS , $row["pass"] );
				$tpl->assign( BUTTON , "<input type=hidden name=a_id value=". $row["a_id"] ."><input type=hidden name=id value=". $row["id"] ."><input type=submit value=刪除>" );
				$tpl->parse ( TECH_LIST, ".tech_list" );
			}
		
		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
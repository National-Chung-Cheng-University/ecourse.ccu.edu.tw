<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( $name != ""  ) {
			if ( ($error = add_group()) == -1 )
				show_page_d ( "類別 $name 加入成功!!" );
			else
				show_page_d ( $error );
			
		}
		else if ( isset($name) )
			show_page_d ( "請填妥類別名稱" );
		else
			show_page_d ( );
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!" );
	
	function add_group () {
		global $name, $p_id;
		if ( $p_id == "" ) {
			$error = "請選擇課程類別";
			return $error;
		}
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select level from course_group where a_id = '$p_id'";
		$Q2 = "update course_group set is_leaf = '0' where a_id = '$p_id'";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$error = "資料庫讀取錯誤!!";
			return $error;
		}
		if ( !($row = mysql_fetch_array( $result )) ) {
			$error = "$p_id 不存在";
			return $error;
		}
		if ( !($result = mysql_db_query( $DB, $Q2 )) ) {
			$error = "資料庫更新錯誤!!";
			return $error;
		}
		$level = $row["level"] + 1;
		$Q3 = "insert into course_group (name, parent_id, level, is_leaf) values ('$name', '$p_id', '$level', '1')";
		if ( !($result = mysql_db_query( $DB, $Q3 )) ) {
			$error = "資料庫寫入錯誤!!";
			return $error;
		}
		return -1;
	}
	
	function show_page_d ( $message="" ) {
		global $name;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "create_group.tpl" ) );
		$tpl->define_dynamic ( "p_list" , "body" );

		$tpl->assign( NAME, $name );
		$tpl->assign( GID , "課程類別" );
		$tpl->assign( GVD , "" );
		$tpl->parse ( P_LIST, ".p_list" );
		$tpl->assign( GID , "最上層" );
		$tpl->assign( GVD , "1" );
		$tpl->parse ( P_LIST, ".p_list" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select name, a_id from course_group where parent_id != '-1'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else
			while ( $row = mysql_fetch_array( $result ) ) {
				$Q2 = "select a_id from course where group_id = " . $row["a_id"];
				if ( mysql_num_rows( mysql_db_query( $DB, $Q2 ) ) == 0 ) {
					$tpl->assign( GID , $row["name"] );
					$tpl->assign( GVD , $row["a_id"] );
					$tpl->parse ( P_LIST, ".p_list" );
				}
			}
		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
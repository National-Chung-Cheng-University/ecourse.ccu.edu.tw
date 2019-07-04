<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( $cid != "" ) {
			if ( $uid != "" ) {
				if ( ($error = add_teach_course()) == -1 )
					show_page_d ( "加入成功!!" );
				else
					show_page_d ( $error );
			}
			else
				show_page_d ( "請選擇教師代號!!!" );
		}
		else if ( isset($cid) ) 
			show_page_d ( "請選擇課程!!!" );
		else
			show_page_d ( );
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!" );
	
	function add_teach_course () {
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $uid, $cid;
		$Q1 = "insert into teach_course (teacher_id, course_id) values ('$uid', '$cid')";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$error = "資料庫寫入錯誤 或 關係已存在!!";
			return $error;
		}

		return -1;
	}
	
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "add_teach_course.tpl" ) );
		$tpl->define_dynamic ( "course_list" , "body" );
		$tpl->define_dynamic ( "tid_list" , "body" );

		$tpl->assign( CVD, "" );
		$tpl->assign( CID, "課程資料" );
		$tpl->parse ( COURSE_LIST, ".course_list" );
		$tpl->assign( TVD , "" );
		$tpl->assign( TID , "教師帳號" );
		$tpl->parse ( TEID_LIST, ".tid_list" );

		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select c.name, c.a_id, c.group_id, cg.name AS gname FROM course c, course_group cg where c.group_id = cg.a_id order by cg.name";
		$Q2 = "select id,a_id FROM user where authorization = '1' or authorization = '2' order by id";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else
			while ( $row = mysql_fetch_array( $result ) ) {
				$course_no = "";
				$Q3 = "select course_no FROM course_no where course_id = '".$row["a_id"]."'";
				if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
					$message = "$message - 資料庫讀取錯誤!!";
				}
				while ( $row3 = mysql_fetch_array( $result3 ) ) {
					$course_no .= $row3['course_no']." ";
				}
				$tpl->assign( CID , "(" . $row["group_id"] . ")" . $row["gname"] . " / " . $row["name"] . "(" . $course_no . ")" );
				$tpl->assign( CVD , $row["a_id"] );
				$tpl->parse ( COURSE_LIST, ".course_list" );
			}
		if ( !($result = mysql_db_query( $DB, $Q2 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else
			while ( $row = mysql_fetch_array( $result ) ) {
				$tpl->assign( TID , $row["id"] );
				$tpl->assign( TVD , $row["a_id"] );
				$tpl->parse ( TEID_LIST, ".tid_list" );
			}
		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
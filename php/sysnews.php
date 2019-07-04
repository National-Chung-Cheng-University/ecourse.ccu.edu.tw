<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) ) {
		$check = session_check_admin($PHPSESSID);
		if ( $submit == "刪除" ) {
			$sub = del_news();
			$nolimit = "";
			$subject = "";
			$news = "";
			show_page_d ( $sub );
		}
		else if ( !isset ($flag) || $check == 0 )
				show_page_d ();
		else if ( $flag == "1" && $check ) {
			if ( checkdate( $start_m, $start_d , $start_y ) && checkdate( $end_m, $end_d , $end_y ) ) {
				if ( $subject != "" ) {
					if ( $news != "" ) {
						if ( ($error = add_news()) == -1 ) {
							$nolimit = "";
							$subject = "";
							$news = "";
							show_page_d ( "公告 $subject 加入成功!!" );
						}
						else
							show_page_d ( $error );
					}
					else
						show_page_d ( "請撰寫公告內容!!!" );
				}
				else
					show_page_d ( "請填寫公告標題!!!" );
			}
			else
				show_page_d ( "日期錯誤!!!" );
		}
		else if ( $check == 1 )
			show_page_d ( "$uesr_id$teacher$course_id" );
		else
			show_page ( "not_access.tpl", "權限錯誤" );
	}
	else
		show_page ( "not_access.tpl", "權限錯誤" );	
	check_news ();
	
	function add_news () {
		global $start_y, $start_m, $start_d, $end_y, $end_m, $end_d, $nolimit,$handle;
		$start= $start_y."-".$start_m."-".$start_d;
		if ( $nolimit == 1 ) {
			$handle = '1';
			$end = "9999-12-31";
		}
		else {
			$end = $end_y."-".$end_m."-".$end_d;
		}
			
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $important, $subject, $news;
		if (session_is_registered("admin_with_auth_5") == true)
			$Q1 = "insert into news ( system, begin_day, end_day, important, handle, subject, content, updated_by) values ( '1', '$start', '$end', '$important', '$handle', '$subject', '$news', 'unit')";
		else
			$Q1 = "insert into news ( system, begin_day, end_day, important, handle, subject, content) values ( '1', '$start', '$end', '$important', '$handle', '$subject', '$news' )";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$error = "資料寫入錯誤!!";
			return $error;
		}
		$aid = mysql_insert_id();
		add_log ( 8, "", $aid );
		return -1;
	}
	
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "sysnews.tpl" ) );

		global $check, $version;
		if ( $check ) {
			$tpl->define ( array ( body => "sysadnews.tpl" ) );
			$tpl->define_dynamic ( "news_list" , "body" );
			$count = 0;
			if (session_is_registered("admin_with_auth_5") == true)
				$Q1 = "select a_id, begin_day, subject, important, content FROM news where updated_by = 'unit' and system = '1' and begin_day <= '".date("Y-m-d")."' and end_day >= '".date("Y-m-d")."' order by begin_day DESC";
			else
				$Q1 = "select a_id, begin_day, subject, important, content FROM news where system = '1' and begin_day <= '".date("Y-m-d")."' and end_day >= '".date("Y-m-d")."' order by begin_day DESC";
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				$error = "資料庫連結錯誤!!";
			}else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$error = "資料庫讀取錯誤!!";
			}
			else {

				while ( $row = mysql_fetch_array( $result ) ) {
					
					$count ++;
	
					$tpl->assign( DATE , $row["begin_day"] );
					$tpl->assign( SUJ , "(".$row["subject"].")" );
					$tpl->assign( CONTENT , $row["content"] );
					$tpl->assign( AID , "<input type=submit name=submit value=刪除><input type=hidden name=a_id value=".$row["a_id"].">" );

					if ( $row["important"] == 2 )
						$tpl->assign( FONT , "#FF0000" );
					else if ( $row["important"] == 1 )
						$tpl->assign( FONT , "#0000FF" );
					else
						$tpl->assign( FONT , "#FFFFFF" );

					$tpl->parse ( N_LIST, ".news_list" );
				}
	
			}
			for ( $i = $count ; $i < 7 ; $i ++ ) {
				$tpl->assign( DATE , "" );
				$tpl->assign( CONTENT , "" );
				$tpl->assign( SUJ , "" );
				$tpl->assign( AID , "" );
				
				if ( $row["important"] == 2 )
					$tpl->assign( FONT , "#FF0000" );
				else if ( $row["important"] == 1 )
					$tpl->assign( FONT , "#0000FF" );
				else
					$tpl->assign( FONT , "#FFFFFF" );
				$tpl->parse ( N_LIST, ".news_list" );
			}

			global $start_y, $start_m, $start_d;
			$tpl->define_dynamic ( "start_y" , "body" );
			for ( $i = 0 ; $i <= 3 ; $i++ ) {
				if ( $start_y == "" )
					$y = date("Y") + $i;
				else
					$y = $start_y + $i;
				if ( $i == 0 )
					$tpl->assign( SYV , $y ." selected" );
				else
					$tpl->assign( SYV , $y );
				$tpl->assign( SYD , $y );
				$tpl->parse ( START_Y, ".start_y" );
			}
			if ( $start_m == "" )
				$m = date("m");
			else
				$m = $start_m;
			$tpl->assign( "SM".$m , "selected" );
			
			if ( $start_d == "" )
				$d = date("d");
			else
				$d = $start_d;
			$tpl->assign( "SD".$d , "selected" );

			global $end_y, $end_m, $end_d, $handle;
			$tpl->define_dynamic ( "end_y" , "body" );
			for ( $i = 0 ; $i <= 3 ; $i++ ) {
				if ( $end_y == "" )
					$y = date("Y") + $i;
				else
					$y = $end_y + $i;
				if ( $i == 0 )
					$tpl->assign( EYV , $y ." selected" );
				else
					$tpl->assign( EYV , $y );
				$tpl->assign( EYD , $y );
				$tpl->parse ( END_Y, ".end_y" );
			}
			if ( $end_m == "" )
				$m = date("m");
			else
				$m = $end_m;
			$tpl->assign( "EM".$m , "selected" );

			if ( $end_d == "" )
				$d = date("d");
			else
				$d = $end_d;
			$tpl->assign( "ED".$d , "selected" );
			if ( $handle == "" )
				$handle = 0;
			$tpl->assign( "H".$handle , "selected" );
			
			global $important;
			if ( $important == "" )
				$important = 1;
			$tpl->assign( "I".$important , "selected" );
			
			global $nolimit, $subject, $news;
			if ( $nolimit == 1 )
				$tpl->assign( CHECK , "checked" );
			$tpl->assign( MES , $message.$error );
			$tpl->assign( SUB, $subject );
			$tpl->assign( NEWS, $news );
			$tpl->parse( BODY, "body" );
			$tpl->FastPrint("BODY");
		}
		else {
			$tpl->define ( array ( body => "sysnews.tpl" ) );
			$tpl->define_dynamic ( "news_list" , "body" );
			$count = 0;
			if (session_is_registered("admin_with_auth_5") == true)
				$Q1 = "select a_id, begin_day, subject, important, content FROM news where updated_by = 'unit' and system = '1' and begin_day <= '".date("Y-m-d")."' and end_day >= '".date("Y-m-d")."' order by begin_day DESC ";
			else
				$Q1 = "select a_id, begin_day, subject, important, content FROM news where system = '1' and begin_day <= '".date("Y-m-d")."' and end_day >= '".date("Y-m-d")."' order by begin_day DESC ";
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				$error = "資料庫連結錯誤!!";
			}else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$error = "資料庫讀取錯誤!!";
			}
			else {

				while ( $row = mysql_fetch_array( $result ) ) {
					
					$count ++;
	
					$tpl->assign( DATE , $row["begin_day"] );
					$tpl->assign( CONTENT , $row["content"] );
					$tpl->assign( SUJ , "(".$row["subject"].")" );

					if ( $row["important"] == 2 )
						$tpl->assign( FONT , "#FF0000" );
					else if ( $row["important"] == 1 )
						$tpl->assign( FONT , "#0000FF" );
					else
						$tpl->assign( FONT , "#FFFFFF" );

					$tpl->parse ( N_LIST, ".news_list" );
				}
	
			}
			for ( $i = $count ; $i < 7 ; $i ++ ) {
				$tpl->assign( DATE , "" );
				$tpl->assign( CONTENT , "" );
				$tpl->assign( SUJ , "" );
				if ( $row["important"] == 2 )
					$tpl->assign( FONT , "#FF0000" );
				else if ( $row["important"] == 1 )
					$tpl->assign( FONT , "#0000FF" );
				else
					$tpl->assign( FONT , "#FFFFFF" );
				$tpl->parse ( N_LIST, ".news_list" );
			}
			$tpl->parse( BODY, "body" );
			$tpl->FastPrint("BODY");
		}
	}	
	
	function check_news () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
		$Q1 = "select a_id from news where end_day <= '".date("Y-m-d")."' and system = '1' and handle = '0'";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
		}else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$error = "資料庫刪除錯誤!!";
		}
		while ( $row = mysql_fetch_array ( $result ) ) {
			$Q2 = "delete FROM news where a_id = '". $row['a_id'] ."'";
			if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
				$error = "資料庫刪除錯誤!!";
			}
		}
	}

	function del_news () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $a_id;
		$Q1 = "select subject from news where a_id = '$a_id'";
		$Q2 = "delete FROM news where a_id = '$a_id'";
		$Q3 = "delete FROM log where event_id = '8' and tag1 = '$a_id'";

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
		}else if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
			$error = "資料庫讀取錯誤1!!";
		}else if ( !($result = mysql_db_query( $DB, $Q2 ) ) ) {
			$error = "資料庫刪除錯誤2!!";
		}else if ( !($result = mysql_db_query( $DB, $Q3 ) ) ) {
			$error = "資料庫刪除錯誤2!!";
		}else if ( $row = mysql_fetch_array( $result1 ) ) {
			$error = "公告 ". $row['subject'] ." 刪除完成";
		}else {
			$error = "資料錯誤!!";
		}
		return $error;
	}
?>

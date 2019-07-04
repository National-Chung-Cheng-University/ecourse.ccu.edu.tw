<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( $tid != "") {
			if ( ($error = del_teach_course( )) == -1 )
				show_page_d ( "$tn - $gn - $cn 刪除成功!!" );
			else
				show_page_d (  $error );
			
		}
		else if ( isset($tid) )
			show_page_d ( "資料錯誤" );
		else
			show_page_d ( );
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!" );
	
	function del_teach_course ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $tid, $gid, $cid;
		$Q1 = "delete from teach_course where teacher_id = '$tid' and course_id = '$cid'";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		for ( $i = 1 ; $i <= 1 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_db_query( $DB, $$Q ) ) ) {
				$error = "資料庫刪除錯誤!!";
				return $error;
			}
		}
		return -1;
	}
	
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "del_teach_course.tpl" ) );
		$tpl->define_dynamic ( "list" , "body" );

		$tpl->assign( FORMSTART , "<form>" );
		$tpl->assign( TYPE , "身分" );
		$tpl->assign( TNAME , "帳號" );
		$tpl->assign( GNAME , "課程類別" );
		$tpl->assign( CNAME , "課程名稱" );
		$tpl->assign( YEARTERM, "開課學年學期" );
		$tpl->assign( BUTTON , "刪除此資料" );
		$tpl->parse( DLIST, ".list" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select tc.teacher_id, c.group_id, tc.course_id, tc.year, tc.term, u.id, u.authorization, c.name AS iname, cg.name FROM user u, course c, course_group cg, teach_course tc where u.a_id = tc.teacher_id and c.a_id = tc.course_id and cg.a_id = c.group_id order by tc.year desc, tc.term desc, u.id";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else
			while ( $row = mysql_fetch_array( $result ) ) {
				$tpl->assign( FORMSTART , "<form method=post action=./del_teach_course.php>" );
				if ( $row["authorization"] == "1" )
					$tpl->assign( TYPE , "教師" );
				else
					$tpl->assign( TYPE , "助教" );
				$tpl->assign( TNAME , $row["id"] );
				$tpl->assign( GNAME , $row["name"] );
				$tpl->assign( CNAME , $row["iname"] );
				$tpl->assign( YEARTERM, $row["year"]."學年第".$row["term"]."學期" );
				$tpl->assign( BUTTON , "<input type=hidden name=tn value=". $row["id"] . "><input type=hidden name=gn value=". $row["name"] . "><input type=hidden name=cn value=". $row["iname"] . "><input type=hidden name=tid value=". $row["teacher_id"] . "><input type=hidden name=gid value=" . $row["group_id"] . "><input type=hidden name=cid value=" . $row["course_id"] ."><input type=submit value=刪除>" );
				$tpl->parse ( DLIST, ".list" );
			}
		
		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
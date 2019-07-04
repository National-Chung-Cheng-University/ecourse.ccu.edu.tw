<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		show_page_d ( );
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!" );

	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "judge.tpl" ) );
		$tpl->define_dynamic ( "course_list" , "body" );

		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select u.name, u.id, c.group_id, cg.name AS gname, tc.course_id, c.name AS cname, c.course_no  FROM course c, course_group cg, teach_course tc , user u where tc.teacher_id = u.a_id and c.a_id = tc.course_id and cg.a_id = c.group_id";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		$tpl->assign( UNAME , "教師" );
		$tpl->assign( UID , "教師ID" );
		$tpl->assign( CNAME , "課程名稱" );
		$tpl->assign( BUTTON , "觀看" );
		$tpl->parse ( COURSE_LIST, ".course_list" );	
		while ( $row = mysql_fetch_array( $result ) ) {
			if ( $row['name'] == "" )
				$tpl->assign( UNAME , "　" );
			else
				$tpl->assign( UNAME , $row['name'] );
			$tpl->assign( UID , $row['id'] );
			$tpl->assign( CNAME , $row['gname']."/(".$row['course_no'].")".$row['cname'] );
			$tpl->assign( BUTTON , "<input type=hidden name=courseid value=".$row['course_id']."><input type=hidden name=cname value=(".$row['course_no'].")".$row['cname']."><input type=hidden name=userid value=".$row['id']."><input type=submit value=觀看>" );
			$tpl->parse ( COURSE_LIST, ".course_list" );
		}
		
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>

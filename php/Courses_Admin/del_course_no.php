<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( $cno != "") {
			if ( ($error = del_course_no( )) == -1 )
				show_page_d ( "$cno - $gn - $cn �R�����\!!" );
			else
				show_page_d (  $error );
			
		}
		else if ( isset($cno) )
			show_page_d ( "��ƿ��~" );
		else
			show_page_d ( );
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!" );
	
	function del_course_no ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $cno, $cid;
		$Q1 = "delete from course_no where course_no = '$cno' and course_id = '$cid'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		$Q2 = "select * from course_no where course_id = '$cid'";
		if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
			$error = "��Ʈw�R�����~!!";
			return $error;
		}
		if( mysql_num_rows( $result2 ) == 1 ) {
			$error = "�C�ӽҵ{�ܤ֭n���@�ӽҸ�!!";
			return $error;
		}
		for ( $i = 1 ; $i <= 1 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_db_query( $DB, $$Q ) ) ) {
				$error = "��Ʈw�R�����~!!";
				return $error;
			}
		}
		return -1;
	}
	
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "del_course_no.tpl" ) );
		$tpl->define_dynamic ( "list" , "body" );

		$tpl->assign( FORMSTART , "<form>" );
		$tpl->assign( GNAME , "�ҵ{���O" );
		$tpl->assign( CNAME , "�ҵ{�W��" );
		$tpl->assign( CNO , "�ҵ{�s��" );
		$tpl->assign( BUTTON , "�R�������" );
		$tpl->parse( DLIST, ".list" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select c.group_id, c.a_id, c.name as iname, cg.name, cn.course_no FROM course c, course_no cn, course_group cg where c.a_id = cn.course_id and cg.a_id = c.group_id order by c.name";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else
			while ( $row = mysql_fetch_array( $result ) ) {
				$tpl->assign( FORMSTART , "<form method=post action=./del_course_no.php>" );
				$tpl->assign( GNAME , $row["name"] );
				$tpl->assign( CNAME , $row["iname"] );
				$tpl->assign( CNO , $row["course_no"] );
				$tpl->assign( BUTTON , "<input type=hidden name=cno value=". $row["course_no"] . "><input type=hidden name=gn value=". $row["name"] . "><input type=hidden name=cn value=". $row["iname"] . "><input type=hidden name=cid value=" . $row["a_id"] ."><input type=submit value=�R��>" );
				$tpl->parse ( DLIST, ".list" );
			}
		
		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
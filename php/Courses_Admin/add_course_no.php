<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( $cid != "" ) {
			if ( $cno != "" ) {
				if ( ($error = add_course_no()) == -1 )
					show_page_d ( "�[�J���\!!" );
				else
					show_page_d ( $error );
			}
			else
				show_page_d ( "�п�J�ҵ{�N��!!!" );
		}
		else if ( isset($cid) ) 
			show_page_d ( "�п�ܽҵ{!!!" );
		else
			show_page_d ( );
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!" );
	
	function add_course_no () {
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $cno, $cid;
		$Q1 = "insert into course_no (course_id, course_no) values ('$cid', '$cno')";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$error = "��Ʈw�g�J���~ �� ��Ƥw�s�b!!";
			return $error;
		}

		return -1;
	}
	
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "add_course_no.tpl" ) );
		$tpl->define_dynamic ( "course_list" , "body" );

		$tpl->assign( CVD, "" );
		$tpl->assign( CID, "�ҵ{���" );
		$tpl->parse ( COURSE_LIST, ".course_list" );

		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $cno;
		$Q1 = "select c.name, c.a_id, c.group_id, cg.name AS gname FROM course c, course_group cg where c.group_id = cg.a_id order by cg.name";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else {
			while ( $row = mysql_fetch_array( $result ) ) {
				$course_no = "";
				$Q3 = "select course_no FROM course_no where course_id = '".$row["a_id"]."'";
				if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) ) {
					$message = "$message - ��ƮwŪ�����~!!";
				}
				while ( $row3 = mysql_fetch_array( $result3 ) ) {
					$course_no .= $row3['course_no']." ";
				}
				$tpl->assign( CID , "(" . $row["group_id"] . ")" . $row["gname"] . " / " . $row["name"] . "(" . $course_no . ")" );
				$tpl->assign( CVD , $row["a_id"] );
				$tpl->parse ( COURSE_LIST, ".course_list" );
			}
		}
		$tpl->assign( CNO , $cno );
		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( isset($a_id) && $a_id != "" ) {
			if ( ($error = del_course()) == -1 )
				show_page_d ( "�ҵ{ $cname �R�����\!!" );
			else
				show_page_d ( $error );
			
		}
		else if ( isset($a_id) )
			show_page_d ( "�ҵ{�s�����~!!" );
		else
			show_page_d ( );
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!" );
	
	function del_course () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $a_id;
		$Q1 = "delete from course where a_id = '$a_id'";
		$Q2 = "delete from teach_course where course_id = '$a_id'";
		$Q3 = "drop database study$a_id";
		$Q4 = "drop database coop$a_id";
		
		$error = -1;
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~1!!";
		}
		for ( $i = 1 ; $i <= 2 ; $i ++ ) {
			$Q = "Q$i";
			if ( !( mysql_db_query( $DB, $$Q ) ) ) {
				$error = "$error - ��Ʈw�R�����~2$i!!";
			}
		}
		
		$U1 = "select student_id from take_course where course_id = '$a_id'";
		$U2 = "delete from take_course where course_id = '$a_id'";
		if ( !($result1 = mysql_db_query( $DB, $U1 ) ) ) {
			$error = "$error - ��ƮwŪ�����~3!!";
		}
		if ( !($result = mysql_db_query( $DB, $U2 ) ) ) {
			$error = "$error - ��Ʈw�R�����~4!!";
		}
		while ( $row1 = mysql_fetch_array( $result1 ) ) {
			$U3 = "select * from take_course where student_id = '".$row1['student_id']."'";
			if ( $result = mysql_db_query( $DB, $U3 ) ) {
				if ( mysql_num_rows( $result ) == 0 ) {
					$U4 = "delete from user where a_id = '".$row1['student_id']."'";
					$U5 = "delete from log where user_id = '".$row1['student_id']."'";
					$U6 = "delete from gbfriend where my_id = '".$row1['student_id']."' or friend_id='".$row1['student_id']."'";
					if ( !( mysql_db_query( $DB, $U4 ) ) ) {
						$error = "$error - ��Ʈw�R�����~5!!";
					}
					if ( !( mysql_db_query( $DB, $U5 ) ) ) {
						$error = "$error - ��Ʈw�R�����~6!!";
					}
					if ( !( mysql_db_query( $DB, $U6 ) ) ) {
						$error = "$error - ��Ʈw�R�����~6!!";
					}
				}
			}
			else
				$error = "$error - ��Ʈw�R�����~7!!";
		}
		if ( !( mysql_query( $Q3 , $link ) ) ) {
				$error = "$error - ��Ʈw�R�����~8!!";
		}
		if ( !( mysql_query( $Q4 , $link ) ) ) {
				$error = "$error - ��Ʈw�R�����~9!!";
		}
		$target = "../../".$a_id;
		if ( is_dir ( $target ) )
			deldir ( $target );
		return $error;
	}
	
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "del_course.tpl" ) );
		$tpl->define_dynamic ( "course_list" , "body" );
		
		$tpl->assign( FORMSTART , "<form>" );
		$tpl->assign( GNAME , "�ҵ{���O" );
		$tpl->assign( CID , "�ҵ{�s��" );
		$tpl->assign( CNAME , "�ҵ{�W��" );
		$tpl->assign( BUTTON , "�R�����ҵ{" );
		$tpl->parse ( COURSE_LIST, ".course_list" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select c.a_id, c.course_no, c.name, c.group_id, cg.name AS gname FROM course c, course_group cg where cg.a_id = c.group_id";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else
			while ( $row = mysql_fetch_array( $result ) ) {	
				$course_no = "";
				$Q2 = "select course_no FROM course_no where course_id = '".$row["a_id"]."'";
				if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
					$message = "$message - ��ƮwŪ�����~!!";
				}
				while ( $row2 = mysql_fetch_array( $result2 ) ) {
					$course_no .= $row2['course_no']." ";
				}
				$tpl->assign( CNO , $course_no );			
				$tpl->assign( FORMSTART , "<form method=post action=./del_course.php>" );
				$tpl->assign( GNAME , $row["gname"]."(".$row["group_id"].")" );
				$tpl->assign( CID , $course_no );
				$tpl->assign( CNAME , $row["name"]."(".$row["a_id"].")" );
				$tpl->assign( BUTTON , "<input type=hidden name=a_id value=" . $row["a_id"] . "><input type=hidden name=cname value=" . $row["name"] . "><input type=submit value=�R�� onClick=\"return confirm('�O�_�R�����ҵ{?');\" >" );
				$tpl->parse ( COURSE_LIST, ".course_list" );
			}
		
		$tpl->assign( MES , $message );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( $a_id != "") {
			if ( ($error = del_teach( )) == -1 )
				show_page_d ( "�Юv $id �R�����\!!" );
			else
				show_page_d ( $error );
			
		}
		else if ( isset($a_id) )
			show_page_d ( "�Юv�b�����~" );
		else
			show_page_d ( );
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!" );
	
	function del_teach ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $a_id ;
		$C1 = "select course_id from teach_course where teacher_id = '$a_id'";
		$Q1 = "delete from log where user_id = '$a_id'";
		$Q2 = "delete from gbfriend where my_id = '$a_id' or friend_id='$a_id'";
		$Q3 = "delete from user where a_id = '$a_id'";
		$Q4 = "delete from teach_course where teacher_id = '$a_id' and course_id = '$course_id'";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		for ( $i = 1 ; $i <= 3 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_db_query( $DB, $$Q ) ) ) {
				$error = "��Ʈw�R�����~!!";
				return $error;
			}
		}
		if ( !($result1 = mysql_db_query( $DB, $C1 ) ) ) {
				$error = "��ƮwŪ�����~!!";
				return $error;
		}
		if ( mysql_num_rows( $result1 ) == 0 ) {
			while ( $row1 = mysql_fetch_array( $result1 ) ) {
				if ( !($result = mysql_db_query( $DB.$row1['course_id'], $Q1 ) ) ) {
					$error = "��Ʈw�R�����~!!";
					return $error;
				}
			}
		}
		if ( !($result = mysql_db_query( $DB, $Q4 ) ) ) {
			$error = "��Ʈw�R�����~!!";
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
		$tpl->assign( TYPE , "����" );
		$tpl->assign( NAME , "�m�W" );
		$tpl->assign( UID , "�b��" );
		$tpl->assign( PASS , "�Юv�K�X" );
		$tpl->assign( BUTTON , "�R�����ϥΪ�" );
		$tpl->parse ( TECH_LIST, ".tech_list" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select a_id, id, name, authorization, pass FROM user WHERE authorization = '1' or authorization = '2' order by id";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else
			while ( $row = mysql_fetch_array( $result ) ) {
				$tpl->assign( FORMSTART , "<form method=post action=./del_user.php>" );
				if ( $row["authorization"] == "1" )
					$tpl->assign( TYPE , "�Юv" );
				else
					$tpl->assign( TYPE , "�U��" );
				$tpl->assign( UID , $row["id"] );
				$tpl->assign( NAME , $row["name"] );
				if ( $row["pass"] == "" )
					$tpl->assign( PASS , "�@" );
				else
					$tpl->assign( PASS , $row["pass"] );
				$tpl->assign( BUTTON , "<input type=hidden name=a_id value=". $row["a_id"] ."><input type=hidden name=id value=". $row["id"] ."><input type=submit value=�R��>" );
				$tpl->parse ( TECH_LIST, ".tech_list" );
			}
		
		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( $a_id != "" ) {
			if ( ($error = del_group( )) == -1 )
				show_page_d ( "���O $name �R�����\!!" );
			else
				show_page_d ( $error );
			
		}
		else if ( isset($a_id) )
			show_page_d ( "���O�s�����~!!" );
		else
			show_page_d ( );
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!" );
	
	function del_group ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $a_id, $p_id;
		$Q1 = "delete from course_group where a_id = '$a_id'";
		$U1 = "update course_group set is_leaf = '1' where a_id = '$p_id'";
		
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		for ( $i = 1 ; $i <= 1 ; $i ++ ) {
			$Q = "Q$i";
			if ( !($result = mysql_db_query( $DB, $$Q ) ) ) {
				$error = "��Ʈw�R�����~!!";
				return $error;
			}
		}
		if ( $p_id != 1 )
			for ( $i = 1 ; $i <= 1 ; $i ++ ) {
				$U = "U$i";
				if ( !($result = mysql_db_query( $DB, $$U ) ) ) {
					$error = "��Ʈw��s���~!!";
					return $error;
				}
			}
		return -1;
	}
	
	function show_page_d ( $message="" ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "del_group.tpl" ) );
		$tpl->define_dynamic ( "group_list" , "body" );
		
		$tpl->assign( FORMSTART , "<form>" );
		$tpl->assign( PID , "�����O" );
		$tpl->assign( GID , "���O�s��" );
		$tpl->assign( NAME , "���O�W��" );
		$tpl->assign( BUTTON , "�R�������O" );
		$tpl->parse ( GROUP_LIST, ".group_list" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		$Q1 = "select a_id, name, parent_id FROM course_group where is_leaf = '1'";

		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - ��Ʈw�s�����~!!";
		}
		if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
			$message = "$message - ��ƮwŪ�����~!!";
		}
		else
			while ( $row = mysql_fetch_array( $result ) ) {
			
				$Q2 = "select name FROM course_group where a_id = '" . $row["parent_id"] . "'";
				if ( !($result2 = mysql_db_query( $DB, $Q2 ) ) ) {
					$message = "$message - ��ƮwŪ�����~!!";
				}
				$row2 = mysql_fetch_array( $result2 );
				
				$Q3 = "select a_id from course where group_id = " . $row["a_id"];
				if ( mysql_num_rows( mysql_db_query( $DB, $Q3 ) ) == 0 ) {
					$tpl->assign( FORMSTART , "<form method=post action=./del_group.php>" );
					$tpl->assign( PID , $row2["name"]." (".$row["parent_id"].")" );
					$tpl->assign( GID , $row["a_id"] );
					$tpl->assign( NAME , $row["name"] );
					$tpl->assign( BUTTON , "<input type=hidden name=p_id value=" . $row["parent_id"] . "><input type=hidden name=a_id value=" . $row["a_id"] . "><input type=hidden name=name value=" . $row["name"] . "><input type=submit value=�R��>" );
					$tpl->parse ( GROUP_LIST, ".group_list" );
				}
			}
		
		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>
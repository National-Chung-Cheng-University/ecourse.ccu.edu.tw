<?php
	require 'fadmin.php';
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "content.tpl") );
	$tpl->assign ( SKINNUM, $skinnum );
	$result;
	$Q1 = "select subject, content, begin_day FROM news where a_id ='$a_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
	}else {
		if ( $system == 1 ) {
			if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
				$error = "��ƮwŪ�����~!!";
			}
			add_log ( 8, "", $a_id );
		}
		else {
			if ( !($result = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
				$error = "��ƮwŪ�����~!!";
			}
			add_log ( 8, "", $a_id, $course_id );
		}
	}
	if ( mysql_num_rows( $result ) != 0 ) {
		$row = mysql_fetch_array( $result );
		$tpl->assign ( TITLE, $row['subject'] );
		$content = $row['content'];
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign ( NEWS,  $content );
		$tpl->assign ( DAY,  $row['begin_day'] );
		if ( $version == "C" )
			$error = "�̷s����";
		else
			$error = "New News";
	}
	else 
		$error = "��Ƥ��s�b!!!";

	$tpl->assign ( MES,  $error );
	if ( $version == "C" ) {
		$tpl->assign ( SUBJECT, "���D" );
		$tpl->assign ( DATE, "�o�G���" );
		$tpl->assign ( CONTENT, "���e" );
		$tpl->assign ( CLOSE, "��������" );
	}
	
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");

?>
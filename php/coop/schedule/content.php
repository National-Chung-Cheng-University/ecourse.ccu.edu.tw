<?php
	require 'fadmin.php';
	update_status ("�[�ݶi�פ��e");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID) && check_group ( $course_id, $coopgroup, $coopcaseid ) != 0) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "content.tpl") );
	$tpl->assign ( SKINNUM, $skinnum );
	$result;
	$Q1 = "select * FROM schedule_".$coopcaseid." where idx ='$idx' and group_num='".$coopgroup."'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
	}else {
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$error = "��ƮwŪ�����~!!";
		}
	}
	if ( mysql_num_rows( $result ) != 0 ) {
		$row = mysql_fetch_array( $result );
		$tpl->assign ( TITLE, $row['subject'] );
		$content = $row['content'];
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign ( NEWS,  $content );
		if ( $row['file'] != NULL ) {
			$tpl->assign ( NEWS,  "$content<br><a href=\"../../../$course_id/coop/$coopcaseid/$coopgroup/schedule/".$row['file']."\" target=_blank>".$row['file']."</a>" );
		}
		$tpl->assign ( DAY,  $row['day'] );
		if ( $version == "C" )
			$error = "�p�նi��";
		else
			$error = "Group Schedule";
	}
	else 
		$error = "��Ƥ��s�b!!!";

	$tpl->assign ( MES,  $error );
	if ( $version == "C" ) {
		$tpl->assign ( SUBJECT, "���D" );
		$tpl->assign ( DATE, "���" );
		$tpl->assign ( CONTENT, "���e" );
		$tpl->assign ( CLOSE, "��������" );
	}
	
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");

?>
<?php
	require 'fadmin.php';
	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
		exit;
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "select id, name, skill, job, experience, introduction, email, php FROM user where a_id ='$user_aid'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "$message - ��Ʈw�s�����~!!";
	}
	else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "$message - ��ƮwŪ�����~!!";
		show_page ( "not_access.tpl", $message, "", "<a href=./guest.php>�^�W�@��</a>" );
	}
	else if( $row = mysql_fetch_array( $result ) ) {
		if ( $row['php'] != NULL )
			header( "Location: ".$row['php']);
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "TTDATAQuery1.tpl") );
		//$tpl->assign ( ID, $row['id'] );
		$tpl->assign ( NAME, $row['name'] );
		$tpl->assign ( SKILL, $row['skill'] );
		if ( is_file( "../../studentPage/".$row['id'].".gif" ) ) {
			$tpl->assign ( IMAGE, "<img src=\"../../studentPage/".$row['id'].".gif\" width=30%>" );
		}
		else {
			$tpl->assign ( IMAGE, "" );
		}
		$job = $row['job'];
		if($job == "00")
			$tpl->assign( JOB , "N/A" );
		else if($job == "01")
			$tpl->assign( JOB , "�q�l�~" );
		else if($job == "02")
			$tpl->assign( JOB , "��T�~" );
		else if($job == "03")
			$tpl->assign( JOB , "�A�ȷ~" );
		else if($job == "04")
			$tpl->assign( JOB , "�ۥѷ~" );
		else if($job == "05")
			$tpl->assign( JOB , "�Ǽ��~" );
		else if($job == "06")
			$tpl->assign( JOB , "���ķ~" );
		else if($job == "07")
			$tpl->assign( JOB , "��ط~" );
		else if($job == "08")
			$tpl->assign( JOB , "���ķ~" );
		else if($job == "09")
			$tpl->assign( JOB , "�ǳN���" );
		else if($job == "010")
			$tpl->assign( JOB , "�F�����" );
		else if($job == "011")
			$tpl->assign( JOB , "�ǥ�" );
		else if($job == "012")
			$tpl->assign( JOB , "�䥦" );
		else
			$tpl->assign( JOB , "" );

		$content = $row['introduction'];
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign ( INTRO, $content );
		$content = $row['experience'];
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign ( EXPER, $content );
		$tpl->assign ( EMAIL, $row['email'] );
		if ( $query == 1 )
			$tpl->assign ( RET, "<a href=# onClick=\"self.close();return false;\">��������</a>" );
		else
			$tpl->assign ( RET, "<a href=./guest.php>�^�e�@��</a>" );
		$tpl->parse( BODY, "body");
		$tpl->FastPrint("BODY");
	}
	else
		show_page ( "not_access.tpl", "�S�����" , "", "<a href=./guest.php>�^�W�@��</a>" );

?>
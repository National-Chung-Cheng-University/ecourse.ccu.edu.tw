<?php
	require 'fadmin.php';
	update_status ("�s��ӤH���");
	
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2 ) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}

	if ( $guest == "1" ) {
		if( $version == "C" ) {
			show_page ( "not_access.tpl", "$error �A�S���v���ϥΦ��\��!!" );
			exit;
		}
		else {
			show_page ( "not_access.tpl", "$error You have No Permission!!" );
			exit;
		}
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $skinnum;
	$Q1 = "SELECT * FROM user where id = '$user_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
		$error = "��ƮwŪ�����~!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}else if ( !($row = mysql_fetch_array($result)) ) {
		$error = "�ϥΪ̤��s�b!!";
		show_page ( "not_access.tpl", $error );
		exit;
	}
	else if ( $row["authorization"] == 9 ) {
		if( $version == "C" ) {
			show_page ( "not_access.tpl", "$error �A�S���v���ϥΦ��\��!!" );
			exit;
		}
		else {
			show_page ( "not_access.tpl", "$error You have No Permission!!" );
			exit;
		}
	}
	
	if ( ($btn == "�R���ɮ�" || $btn == "Del Pic") ) {
		if ( is_file ( "../../studentPage/".$user_id.".gif" ) ) {
			if( unlink( "../../studentPage/".$user_id.".gif" ) ) {
				if ( $version == "C" )
					$error = "�R�����\\";
				else
					$error = "Delete Success";
			}
	   		else {
	   			if ( $version == "C" )
					$error = "�R������";
				else
					$error = "Delete Abort";
			}
		}
		else {
			if ( $version == "C" )
				$error = "�L�ɮץi�R��";
			else
				$error = "No File for Delete";
		}
	}


	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	if( $version == "C" )
		$tpl->define ( array ( body => "email_tch.tpl") );
	else
		$tpl->define ( array ( body => "email_tch_E.tpl") );
	$tpl->assign ( ID, $row['id'] );
	$tpl->assign( SKINNUM , $skinnum );
	if ( isset($name) && isset($year) && isset($tel) && isset($addr) && isset($email) && ($btn == "�T�w" || $btn == "Send" )) {

		$error = "�ж�";
		if ( $name == "" )
			$error = "$error �m�W";
		if ( $email == "" )
				$error = "$error �q�l�l����}";
		if ( $pageKind == 1 ) {
			if( $version == "C" )
	    			$uurl = "";
    			else
	    			$uurl = "";
		}
		if ( $error == "�ж�" ) {
			$error = "��ƥ[�J���\\";
			$Q2 = "update user set name = '$name', sex = '$sex', birthday = '$year-$month-$day', tel = '$tel',
			addr = '$addr', email = '$email', php = '$uurl', job = '$job', introduction = '$intro', experience = '$exper',
			interest = '$interest', skill = '$skill' , nickname = '$nickname' where id = '$user_id'";
 			if ( !($result = mysql_db_query( $DB, $Q2  )) )
				$error = "��Ʈw�g�J���~!!";
			if( $pic_file != "none" && $pic_file != "" ) {
				if ( !fileupload ( $pic_file, "../../studentPage", $pic, "0774" ) ) {
		   			if ( $version == "C" )
						$error = "�W�ǥ���";
					else
						$error = "Upload Abort";
				}
			}
		}
		$name = stripslashes( $name );
		$nickname = stripslashes( $nickname );
		$interest = stripslashes( $interest );
		$skill = stripslashes( $skill );
		$intro = stripslashes( $intro );
		$exper = stripslashes( $exper );
		$tel = stripslashes( $tel );
		$addr = stripslashes( $addr );
		$email = stripslashes( $email );
		
		if($row["authorization"] == 1){
			$tpl->assign ( READ, "readonly" );
		}
		else{
			$tpl->assign ( READ, "" );
		}
		$tpl->assign ( NAME, $name );
		$tpl->assign ( NICK, $nickname );
		$tpl->assign ( INTEREST, $interest );
		$tpl->assign ( "SEX$sex", "selected" );
		$tpl->assign ( "JOB$job", "selected" );
		$tpl->assign ( YEAR, $year );
		$tpl->assign ( "M$month", "selected" );
		$tpl->assign ( "D$day", "selected" );
		$tpl->assign ( SKILL, $skill );
		$tpl->assign ( INTRO, $intro );
		$tpl->assign ( EXPER, $exper );
		$tpl->assign ( TEL, $tel );
		$tpl->assign ( ADDR, $addr );
		$tpl->assign ( EMAIL, $email );
		if ( $uurl == "" ) {
			$tpl->assign ( "P1", "checked" );
			$tpl->assign ( URL, "http://" );
		}
		else {
			$tpl->assign ( "P2", "checked" );
			$tpl->assign ( URL, $uurl );
		}
		$tpl->assign ( SESSION, "PHPSESSID=$PHPSESSID" );
	}	
	else {
			
		if($row["authorization"] == 1){
			$tpl->assign ( READ, "readonly" );
		}
		else{
			$tpl->assign ( READ, "" );
		}
		
		$tpl->assign ( NAME, $row['name'] );
		$tpl->assign ( NICK, $row['nickname'] );
		$tpl->assign ( INTEREST, $row['interest'] );
		$tpl->assign ( "SEX".$row['sex'], "selected" );
		$tpl->assign ( "JOB".$row['job'], "selected" );
		$tpl->assign ( YEAR, $row['birthday'][0].$row['birthday'][1].$row['birthday'][2].$row['birthday'][3] );
		$month = $row['birthday'][5].$row['birthday'][6];
		if ( $month == "" )
			$month = "01";
		$tpl->assign ( "M$month", "selected" );
		$day = $row['birthday'][8].$row['birthday'][9];
		if ( $day == "" )
			$day = "01";
		$tpl->assign ( "D$day", "selected" );
		$tpl->assign ( SKILL, $row['skill'] );
		$tpl->assign ( INTRO, $row['introduction'] );
		$tpl->assign ( EXPER, $row['experience'] );
		$tpl->assign ( TEL, $row['tel'] );
		$tpl->assign ( ADDR, $row['addr'] );
		$tpl->assign ( EMAIL, $row['email'] );
		if ( $row['php'] == "" ) {
			$tpl->assign ( "P1", "checked" );
			$tpl->assign ( URL, "http://" );
		}
		else {
			$tpl->assign ( "P2", "checked" );
			$tpl->assign ( URL, $row['php'] );
		}
		$tpl->assign ( SESSION, "PHPSESSID=$PHPSESSID" );
	}
	
	$tpl->assign ( VERSION, $version );
	$tpl->assign ( MES, $error );
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");
?>
<?php
	require 'fadmin.php';
	include("mail.php");
	$Q1 = "SELECT pass FROM user where id = '$id' and email='$email'";
	if ( $version == "C" )	
		if ( isset( $id ) && $email != "" ) {
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
				$error = "��Ʈw�s�����~!!";
			else if ( !($result = mysql_db_query( $DB, $Q1  )) )
				$error = "��ƮwŪ�����~!!";
			else if ( mysql_num_rows($result) == 0 )
				$error = "�b����Email���~!!";
			else {
				$row = mysql_fetch_array( $result );
				send ( $row['pass'] );
				$error = "�z���K�X�w�H��U�C��}".$email;
			}
			show_page_d ( $error );
		}
		else
			show_page_d ( "�п�J�z���b���A�εn�O��Email��}" );
	else
		if ( isset( $id ) && $email != "" ) {
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
				$error = "DATABASE CONNECT ERROR!!";
			else if ( !($result = mysql_db_query( $DB, $Q1  )) )
				$error = "DATABASE READ ERROR!!";
			else if ( mysql_num_rows($result) == 0 )
				$error = "ID or Email Error!!";
			else {
				$row = mysql_fetch_array( $result );
				send ( $row['pass'] );
				$error = "Your Password had been sent to:".$email;
			}
			show_page_d ( $error );
		}
		else
			show_page_d ( "Please Input your ID and Email" );

	function show_page_d ( $error="" ) {
		global $version;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "lost_pass.tpl") );
		
		$tpl->assign ( MES, $error );

		if( $version == "C" ) {
			$tpl->assign ( TITLE, "�K�X��" );
			$tpl->assign ( PATH, "img" );
			$tpl->assign ( ID, "�b��" );
			$tpl->assign ( EMAIL, "Email ��}" );
			$tpl->assign ( SUBM, "�e�X" );
			$tpl->assign ( CLEA, "�M��" );
		}
		else {
			$tpl->assign ( TITLE, "Lost Password" );
			$tpl->assign ( PATH, "img_E" );
			$tpl->assign ( ID, "ID" );
			$tpl->assign ( EMAIL, "Email Address" );
			$tpl->assign ( SUBM, "Send" );
			$tpl->assign ( CLEA, "Clear" );
		}
		$tpl->assign ( VERSION, $version );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
	function send ( $pass ) {
		global $SERVER_NAME, $id, $email, $version;
		// �Ѧ�mail.php�᭱������.
		$mail = new mime_mail();
		$mail->from = "study@".$SERVER_NAME;
		$mail->headers = "Errors-To:autumn@exodus.cs.ccu.edu.tw\n";
		$mail->headers .= "Reply-To:study@".$SERVER_NAME;
		$mail->to = $email;
		
		// �o�����ثe�ɶq����M web �W�ۦP.
		if ( $version == "C" ) {
			$mail->subject = $id."�����оǨt��";
			$mail->body = "<html><body><center><b><font color=#0000FF>�z�b�����оǨt�άd�ߪ����</font></b></center>".
			"<table border=0 width=75%>".
			"<tr align='left'><th><b><font color=#0000FF>�z���b��:</font><font color=#000000>$id</font></b></th></tr>".
			"<tr align='left'><th><b><font color=#0000FF>�z���K�X:</font><font color=#000000>$pass</font></b></th></tr>";
			$mail->body = $mail->body."</table></body></html>";
			$mail->body = $mail->body."<a href='http://$SERVER_NAME/'>�����оǨt��$SERVER_NAME</a>";
		}
		else {
			$mail->subject = $id."Your Password at WebEdu";
			$mail->body = "<html><body><center><b><font color=#0000FF>The Password You Require</font></b></center>".
			"<table border=0 width=75%>".
			"<tr align='left'><td><b><font color=#0000FF>YOUR ID : </font><font color=#000000>$id</font></b></td></tr>".
			"<tr align='left'><td><b><font color=#0000FF>PASSWORD: </font><font color=#000000>$pass</font></b></td></tr>";
			$mail->body = $mail->body."</table></body></html>";
			$mail->body = $mail->body."<a href='http://$SERVER_NAME/'>WebEdu $SERVER_NAME</a>";
		}
		$mail->send();
	}
?>
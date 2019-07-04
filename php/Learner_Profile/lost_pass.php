<?php
	require 'fadmin.php';
	include("mail.php");
	$Q1 = "SELECT pass FROM user where id = '$id' and email='$email'";
	if ( $version == "C" )	
		if ( isset( $id ) && $email != "" ) {
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
				$error = "資料庫連結錯誤!!";
			else if ( !($result = mysql_db_query( $DB, $Q1  )) )
				$error = "資料庫讀取錯誤!!";
			else if ( mysql_num_rows($result) == 0 )
				$error = "帳號或Email錯誤!!";
			else {
				$row = mysql_fetch_array( $result );
				send ( $row['pass'] );
				$error = "您的密碼已寄到下列位址".$email;
			}
			show_page_d ( $error );
		}
		else
			show_page_d ( "請輸入您的帳號，及登記的Email位址" );
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
			$tpl->assign ( TITLE, "密碼遺失" );
			$tpl->assign ( PATH, "img" );
			$tpl->assign ( ID, "帳號" );
			$tpl->assign ( EMAIL, "Email 位址" );
			$tpl->assign ( SUBM, "送出" );
			$tpl->assign ( CLEA, "清除" );
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
		// 參考mail.php後面的說明.
		$mail = new mime_mail();
		$mail->from = "study@".$SERVER_NAME;
		$mail->headers = "Errors-To:autumn@exodus.cs.ccu.edu.tw\n";
		$mail->headers .= "Reply-To:study@".$SERVER_NAME;
		$mail->to = $email;
		
		// 這部分目前盡量做到和 web 上相同.
		if ( $version == "C" ) {
			$mail->subject = $id."網路教學系統";
			$mail->body = "<html><body><center><b><font color=#0000FF>您在網路教學系統查詢的資料</font></b></center>".
			"<table border=0 width=75%>".
			"<tr align='left'><th><b><font color=#0000FF>您的帳號:</font><font color=#000000>$id</font></b></th></tr>".
			"<tr align='left'><th><b><font color=#0000FF>您的密碼:</font><font color=#000000>$pass</font></b></th></tr>";
			$mail->body = $mail->body."</table></body></html>";
			$mail->body = $mail->body."<a href='http://$SERVER_NAME/'>網路教學系統$SERVER_NAME</a>";
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
<?php
	require 'fadmin.php';
	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}

	if ( $user_id == "guest" ) {
		if( $version == "C" ) {
			show_page ( "not_access.tpl", "$error 你沒有權限使用此功能!!" );
			exit;
		}
		else {
			show_page ( "not_access.tpl", "$error You have No Permission!!" );
			exit;
		}
	}
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "SELECT email, pass ,authorization FROM user where id = '$user_id'";
	$Q2 = "update user set email = '$email' where id = '$user_id'";
	$Q3 = "update user set pass = '" . passwd_encrypt($newpass) . "',ftppass='" . md5($newpass) . "' where id = '$user_id'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
		$error = "資料庫連結錯誤!!";
	else if ( !($result = mysql_db_query( $DB, $Q1  )) )
		$error = "資料庫讀取錯誤!!";
	else if ( !($row = mysql_fetch_array($result)) ) {
		if ( $version == "C" )
			$error = "使用者不存在!!";
		else
			$error = "No This User!!";
		show_page ( "not_access.tpl", $error );
	}
	//else if ( $row["authorization"] == 9 ) {
	//	show_page ( "not_access.tpl", "你沒有權限使用此功能!!" );
	//	exit;
	//}
	$email_input = "";
	$pass_input = "";

	if ( $row["email"] == "" ) {
		$email_input = "<tr><td>Email:</td><td><input type=text name=email size=40></td></tr>\n";
		if ( $version == "C" )
			$pass_input = "<tr><td>密碼:</td><td><input type=password name=old size=11 maxlength = 16></td></tr>";
		else
			$pass_input = "<tr><td>Old Password:</td><td><input type=password name=old size=11 maxlength = 16></td></tr>";
	}
	if ( $row["pass"] == "" ) {
		if ( $version == "C" )
			$pass_input = "<input type=hidden name=old value=><tr><td>新密碼:</td><td><input type=password name=newpass size=11 maxlength = 16></td></tr>\n<tr><td>密碼再確認:</td><td><input type=password name=check size=11 maxlength = 16></td></tr>\n";
		else
			$pass_input = "<input type=hidden name=old value=><tr><td>New Password:</td><td><input type=password name=newpass size=11 maxlength = 16></td></tr>\n<tr><td>Check Again:</td><td><input type=password name=check size=11 maxlength = 16></td></tr>\n";
	}
	$error = "";

	if ( $version == "C" ) {
		if ( $row["pass"] == "" ) {
			if ( $newpass == $check ) {				
				if ( $newpass == "" )
					$error = "密碼過於簡單請修改密碼後再登入";
			}
			else 
				$error = "新密碼不一樣，請重新輸入!!";
		}
		else if ( passwd_decrypt($row['pass']) != $old && $old != "" )
			$error = "舊密碼錯誤，請重試!!";

		if ( $error != "" ) {
			show_page_d ( $error );
			exit;
		}
		if ( $row["pass"] == "" ) {
			if ( !($result = mysql_db_query( $DB, $Q3  )) )
				$error = "資料更新錯誤!!";
			else
				$pass_input = "";
		}
		if ( $row["email"] == "" ) {
			if ( isset( $email ) ) {
				if ( $email == "" )
					$error = "請輸入你們的Email後再登入!!";				
			}
			else
				$error = "請輸入你們的Email後再登入!!";
		}
		if ( $error != "" ) {
			show_page_d ( $error );
			exit;
		}
		if ( $row["email"] == "" )
			if ( !($result = mysql_db_query( $DB, $Q2  )) )
				$error = "資料更新錯誤!!";
	}
	else {
		if ( $row["pass"] == "" ) {
			if ( $newpass == $check ) {
				if ( $newpass == "" )
					$error = "Your Password is too simple";
			}
			else 
				$error = "NEW PASSWORD ARE NOT THE SAME, PLEASE TRY AGAIN!!!";
		}
		else if ( passwd_decrypt($row['pass']) != $old && $old != "" )
			$error = "Old Password Error，Please Try Again!!";

		if ( $error != "" ) {
			show_page_d ( $error );
			exit;
		}
		if ( $row["pass"] == "" )
			if ( !($result = mysql_db_query( $DB, $Q3  )) )
				$error = "UPDATE Error!!";
		if ( $row["email"] == "" ) {
			if ( isset( $email ) ) {
				if ( $email == "" )
					$error = "Please Input Your Email First!!";				
			}
			else
				$error = "Please Input Your Email First!!";
		}
		if ( $error != "" ) {
			show_page_d ( $error );
			exit;
		}
		if ( $row["email"] == "" )
			if ( !($result = mysql_db_query( $DB, $Q2  )) )
				$error = "UPDATE Error!!";
	}
	add_log ( 1, $user_id );
      	add_message ();
	header( "Location: ../index_login.php?PHPSESSID=$PHPSESSID" );
	exit();

	function show_page_d ( $error="" ) {
		global $version, $email_input, $pass_input;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "chang_pass2.tpl") );
		
		$tpl->assign ( MES, $error );

		if( $version == "C" ) {
			$tpl->assign ( TITLE, "安全性警告" );
			$tpl->assign ( PATH, "img" );
			$tpl->assign ( SUBM, "送出" );
			$tpl->assign ( CLEA, "清除" );
			$tpl->assign ( PASS, $pass_input );
			$tpl->assign ( EMAIL, $email_input );
		}
		else {
			$tpl->assign ( TITLE, "Security Alarm" );
			$tpl->assign ( PATH, "img_E" );
			$tpl->assign ( SUBM, "Send" );
			$tpl->assign ( CLEA, "Clear" );
			$tpl->assign ( PASS, $pass_input );
			$tpl->assign ( EMAIL, $email_input );
		}
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>

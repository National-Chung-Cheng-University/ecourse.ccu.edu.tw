<?php
	require 'fadmin.php';
	if ( !(isset($PHPSESSID) && session_check_admin($PHPSESSID)) ) {
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
	
	if ( $version == "C" )	
		if ( isset( $old ) ) {
			if ( $newpass == $check ) {
				global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $user_id;
				
				if (session_is_registered("admin_with_auth_5") == false)
					$user_id = "ecourse_admin";

				$Q1 = "SELECT pass ,authorization FROM user where id = '$user_id'";
				$Q2 = "update user set pass = '" . passwd_encrypt($newpass) . "',ftppass='" . md5($newpass) . "' where id = '$user_id'";
				if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
					$error = "資料庫連結錯誤!!";
				else if ( !($result = mysql_db_query( $DB, $Q1  )) )
					$error = "資料庫讀取錯誤!!";
				else if ( !($row = mysql_fetch_array($result)) ) {
					$error = "使用者不存在!!";
				//}
				//else if ( $row["authorization"] == 9 ) {
				//		show_page ( "not_access.tpl", "你沒有權限使用此功能!!" );
				//		exit;
				}else if ( passwd_decrypt($row['pass']) == $old ) {
					if ( !($result = mysql_db_query( $DB, $Q2  )) )
						$error = "資料庫更新錯誤!!";
					else
						$error = "您的新密碼為: $newpass";
				}
				else
					$error = "舊密碼錯誤，請重試!!";
				show_page_d ( $error );
			}
			else 
				show_page_d ( "新密碼不一樣，請重新輸入!!" ); 
			
		}
		else
			show_page_d ( );
	else
		if ( isset( $old ) ) {
			if ( $newpass == $check ) {
				global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
				$Q1 = "SELECT pass,authorization FROM user where id = '$user_id'";
				$Q2 = "update user set pass = '" . passwd_encrypt($newpass) . "',ftppass='" . md5($newpass) . "' where id = '$user_id'";
				if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
					$error = "DATABASE CONNECT ERROR!!";
				else if ( !($result = mysql_db_query( $DB, $Q1  )) )
					$error = "DATABASE READ ERROR!!";
				else if ( !($row = mysql_fetch_array($result)) ) {
					$error = "NO THIS USER!!!";
				//}
				//else if ( $row["authorization"] == 9 ) {
				//		show_page ( "not_access.tpl", "You have No Permission!!" );
				//		exit;
				}else if ( passwd_decrypt($row['pass']) == $old ) {
					if ( !($result = mysql_db_query( $DB, $Q2  )) )
						$error = "DATABASE UPDATE ERROR!!";
					else
						$error = "Your New Password is : $newpass";
				}
				else
					$error = "Old Password Error，Please Try Again!!";
				show_page_d ( $error );
			}
			else 
				show_page_d ( "NEW PASSWORD ARE NOT THE SAME, PLEASE TRY AGAIN!!!" ); 
			
		}
		else
			show_page_d ( );

	function show_page_d ( $error="" ) {
		global $version;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "chang_pass_admin.tpl") );
		
		$tpl->assign ( MES, $error );

		if( $version == "C" ) {
			$tpl->assign ( PATH, "img" );
			$tpl->assign ( OLDP, "舊密碼" );
			$tpl->assign ( NEWP, "新密碼" );
			$tpl->assign ( TYPA, "密碼確認" );
			$tpl->assign ( SUBM, "送出" );
			$tpl->assign ( CLEA, "清除" );
		}
		else {
			$tpl->assign ( PATH, "img_E" );
			$tpl->assign ( OLDP, "old password" );
			$tpl->assign ( NEWP, "new password" );
			$tpl->assign ( TYPA, "type again" );
			$tpl->assign ( SUBM, "Send" );
			$tpl->assign ( CLEA, "Clear" );
		}
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
?>

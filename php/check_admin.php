<?php
	require 'common.php';
	if ( $id != "") {
		$error = auth();
		if ($error == "-1" || $error == "-2") {
			session_start();
			session_register("admin");
			session_register("version");
			if ($error == "-1") {
				session_unregister("admin_with_auth_5");
				if($id == "GRD01")
					$admin = 2;
				elseif($id == "ecourse_admin")
					$admin = 3;
				else
					$admin = 1;
			} else { //authorization = 5
				session_register("admin_with_auth_5");

				global $id;
				session_register("user_id");
				$user_id = $id;
				$admin = 4;
			}
			$version = "C";
			session_unregister("teacher");
			session_unregister("course_id");
			if ($admin != 4) session_unregister("user_id");
			session_unregister("id");
			session_unregister("time");
			header( "Location: http://$SERVER_NAME/php/check_admin.php?PHPSESSID=".session_id());		
		}
		else {
			show_page ( "index_ad.tpl" , $error, $id);
		}
	}
	else if ( !isset($id) || $id == "" ){
		if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
			session_unregister("teacher");
			session_unregister("course_id");
			if ($admin != 4) session_unregister("user_id");
			session_unregister("id");
			session_unregister("time");
			if($admin == 2)
				show_page( "show_ad4.tpl" );
			else if($admin == 3)
				show_page( "show_ad3.tpl" );
			else if ($admin == 4) //authorization = 5
				show_page("show_ad5.tpl");
			else
				show_page( "show_ad.tpl" );
		}
		else
			show_page( "index_ad.tpl", "請輸入你的帳號及密碼!!!");
	}
	else
		show_page( "index_ad.tpl" );

	function auth() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
		$Q1 = "SELECT pass, authorization FROM user where id = '$id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
			$error = "資料庫讀取錯誤!!";
			return $error;
		}

		//modified by carlyle (20070821)
		if($row = mysql_fetch_array($result)) {	//有資料才檢查
			if (passwd_encrypt($pass) == $row["pass"]) {
				if ($row["authorization"] == 0)
					return "-1";
				else if ($row["authorization"] == 5)
					return "-2";
			}
		}
		$error = "使用者帳號或密碼錯誤!!";
		return $error;
	}

?>

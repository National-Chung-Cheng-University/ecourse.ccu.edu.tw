<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( $id != "") {
			if ( ($error = add_tech( )) == -1 )
				show_page ( "new_user.tpl", "教師 $id 加入成功!!" );
			else
				show_page ( "new_user.tpl", $error, $id);
			
		}
		else if ( isset($id) )
			show_page( "new_user.tpl", "請輸入教師帳號!!!" );
		else
			show_page( "new_user.tpl" );
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
	
	function add_tech ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
		$Q1 = "insert into user ( id, pass, ftppass, authorization, forbear ) values ( '$id', '" . passwd_encrypt($pass) . "', '" . md5($pass) . "', '1', '1800' )";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		if ( !($result = mysql_db_query( $DB,$Q1  ) ) ) {
			$error = "資料庫寫入錯誤!!";
			return $error;
		}
		return -1;
	}
?>

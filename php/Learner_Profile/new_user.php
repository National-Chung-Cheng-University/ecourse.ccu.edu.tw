<?php
	require 'fadmin.php';
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if ( $id != "") {
			if ( ($error = add_tech( )) == -1 )
				show_page ( "new_user.tpl", "�Юv $id �[�J���\!!" );
			else
				show_page ( "new_user.tpl", $error, $id);
			
		}
		else if ( isset($id) )
			show_page( "new_user.tpl", "�п�J�Юv�b��!!!" );
		else
			show_page( "new_user.tpl" );
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
	
	function add_tech ( ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;
		$Q1 = "insert into user ( id, pass, ftppass, authorization, forbear ) values ( '$id', '" . passwd_encrypt($pass) . "', '" . md5($pass) . "', '1', '1800' )";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		if ( !($result = mysql_db_query( $DB,$Q1  ) ) ) {
			$error = "��Ʈw�g�J���~!!";
			return $error;
		}
		return -1;
	}
?>

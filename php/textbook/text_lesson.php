<?
	require 'fadmin.php';

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}
	if($guest == "1") {
		$Q1 = "SELECT validated FROM course where a_id = '$course_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
		}
		else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
			$error = "��ƮwŪ�����~!!";
		}
		else if ( mysql_num_rows( $result ) == 0 ) {
			$error = "��Ʈw���~!!";
			show_page ( "not_access.tpl", $error );
		}
		else {
			$row = mysql_fetch_array($result);
		}

		if( ($row["validated"]%2 == 1) ) {
			if ( $version == "C" )
				show_page( "not_access.tpl" ,"�Ч����}����[");
			else
				show_page( "not_access.tpl" ,"Access Denied.");
			exit();
		}
	}

	if(is_file("../../$course_id/textbook/index.html") || is_file("../../$course_id/textbook/index.htm")) {
		header( "Location: /$course_id/textbook/");
	}
	else {
		header( "Location: /php/textbook/course_menu.php");
//		show_page( "not_access.tpl" ,"�ثe�|�L�Ч�����,���I�索�C�s���[�ݦU���`�Ч�");
	}
?>

<?
	require 'fadmin.php';

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}
	if($guest == "1") {
		$Q1 = "SELECT validated FROM course where a_id = '$course_id'";
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "資料庫連結錯誤!!";
		}
		else if ( !($result = mysql_db_query( $DB, $Q1  )) ) {
			$error = "資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) == 0 ) {
			$error = "資料庫錯誤!!";
			show_page ( "not_access.tpl", $error );
		}
		else {
			$row = mysql_fetch_array($result);
		}

		if( ($row["validated"]%2 == 1) ) {
			if ( $version == "C" )
				show_page( "not_access.tpl" ,"教材不開放參觀");
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
//		show_page( "not_access.tpl" ,"目前尚無教材首頁,請點選左列連結觀看各章節教材");
	}
?>

<?
//update by Autumn
//2002 03 29 12 41

	require 'fadmin.php';
	update_status ("瀏覽教材");

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}

	// check guest read textbook permission.
	// ok when validated = 0 or 2.
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

	/*
	// Handle exception : index.html / index.htm deleted by user.

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
	
	// 判斷老師是否有上傳自己的教材.
	$sql = "select * from chap_title";
	$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	*/
	
	if ( $scorm == 1 ) {
		include("class.FastTemplate.php3");

		$tpl = new FastTemplate("./templates");

		$tpl->define(array(main => "scorm.tpl"));

		$tpl->assign("COURSE_ID", $course_id);

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	else { // 編輯器的教材
		include("class.FastTemplate.php3");

		$tpl = new FastTemplate("./templates");

		$tpl->define(array(main => "material.tpl"));

		$tpl->assign("PHPID", $PHPSESSID);

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	/*
	else {  // 自己的教材, 作最基本的log.
		if(is_file("../../$course_id/textbook/index.html") || is_file("../../$course_id/textbook/index.htm")) {
			if( ((session_check_teach($PHPSESSID) == 1) || (session_check_teach($PHPSESSID) == 2)) && (strcmp($guest, "1") != 0)) {
				add_log ( 3, $user_id, "0", $course_id, "1", "0" );
			}
			header( "Location: /$course_id/textbook/");
		}
		else {
			if ( $version == "C" ) {
				show_page( "not_access.tpl" ,"目前沒有任何教材");
			}
			else {
				show_page( "not_access.tpl" ,"There is no TextBook");
			}
		}			
	}
	*/
?>
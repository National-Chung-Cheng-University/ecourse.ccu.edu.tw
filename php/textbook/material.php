<?
//update by Autumn
//2002 03 29 12 41

	require 'fadmin.php';
	update_status ("�s���Ч�");

	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
	}

	// check guest read textbook permission.
	// ok when validated = 0 or 2.
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

	/*
	// Handle exception : index.html / index.htm deleted by user.

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");
	
	// �P�_�Ѯv�O�_���W�Ǧۤv���Ч�.
	$sql = "select * from chap_title";
	$result = mysql_db_query($DB.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
	*/
	
	if ( $scorm == 1 ) {
		include("class.FastTemplate.php3");

		$tpl = new FastTemplate("./templates");

		$tpl->define(array(main => "scorm.tpl"));

		$tpl->assign("COURSE_ID", $course_id);

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	else { // �s�边���Ч�
		include("class.FastTemplate.php3");

		$tpl = new FastTemplate("./templates");

		$tpl->define(array(main => "material.tpl"));

		$tpl->assign("PHPID", $PHPSESSID);

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	/*
	else {  // �ۤv���Ч�, �@�̰򥻪�log.
		if(is_file("../../$course_id/textbook/index.html") || is_file("../../$course_id/textbook/index.htm")) {
			if( ((session_check_teach($PHPSESSID) == 1) || (session_check_teach($PHPSESSID) == 2)) && (strcmp($guest, "1") != 0)) {
				add_log ( 3, $user_id, "0", $course_id, "1", "0" );
			}
			header( "Location: /$course_id/textbook/");
		}
		else {
			if ( $version == "C" ) {
				show_page( "not_access.tpl" ,"�ثe�S������Ч�");
			}
			else {
				show_page( "not_access.tpl" ,"There is no TextBook");
			}
		}			
	}
	*/
?>
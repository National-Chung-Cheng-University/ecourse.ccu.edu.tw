<?
	require 'fadmin.php';
	update_status ("瀏覽文章");
	include("class.FastTemplate.php3");

	session_id($PHPSESSID);
	session_start();

	if(!(session_check_teach($PHPSESSID))) {
		show_page("not_access.tpl", "你沒有權限執行此功能.<br>\nYou have no permission to perform this function.");
		exit();
	}

	if ( $action == "record" ) {
		do_record();
	}
	else if ( $action == "show_record" ) {
		show_record();
	}
	else {
		show_page_d();
	}

	function show_page_d ( ) {
		global $contain, $compare, $list, $PHPSESSID, $a_id, $skinnum;
		$tpl = new FastTemplate("./templates");

		$tpl->define(array("main" => "record.tpl"));
		
		$tpl->assign( TEXT1, $contain );
		$tpl->assign( TEXT2, $compare );
		$tpl->assign( TEXT3, $list );
		$tpl->assign( AID, $a_id );
		$tpl->assign( PHPID, $PHPSESSID );
		$tpl->assign( SKINNUM, $skinnum );

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	
	function do_record() {
		global $contain, $compare, $list, $course_id, $a_id, $user_id, $version, $skinnum;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
		$Q1 = "select * from concept_map where student_id = '".GetUserAID($user_id)."' and text_id = '$a_id'";

		($result = mysql_db_query( $DB.$course_id, $Q1 ) ) or die("資料庫讀取錯誤");
		if ( mysql_num_rows( $result ) != 0 ) {
			$row = mysql_fetch_array( $result);
			$Q2 = "update concept_map set contain='$contain', compare='$compare', list='$list' where a_id='".$row['a_id']."'";
			($result2 = mysql_db_query( $DB.$course_id, $Q2 ) );
			$record_id = $row['a_id'];
		}
		else {
			$Q2 = "insert into concept_map ( student_id, text_id, contain, compare, list ) values ( '".GetUserAID($user_id)."', '$a_id', '$contain', '$compare', '$list' )";
			($result2 = mysql_db_query( $DB.$course_id, $Q2 ) );
			$record_id = mysql_insert_id();
		}
		
		
		$tpl = new FastTemplate("./templates");

		$tpl->define(array("main" => "concept_main2.tpl"));
		$tpl->assign( AID, $record_id  );
		$tpl->assign( PHPID, $PHPSESSID );
		$tpl->assign( SKINNUM, $skinnum );

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
		
	}
	
	function show_record () {
		global $contain, $compare, $list, $skinnum, $a_id, $course_id;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
		$Q1 = "select * from concept_map where a_id = '$a_id'";
		
		($result = mysql_db_query( $DB.$course_id, $Q1 ) ) or die("資料庫讀取錯誤$Q1");
		$row=mysql_fetch_array($result);
		$tpl = new FastTemplate("./templates");

		$tpl->define(array("main" => "show_record.tpl"));
		
		$tpl->assign( AID, $a_id );
		$tpl->assign( TEXT1, $row['contain'] );
		$tpl->assign( TEXT2, $row['compare'] );
		$tpl->assign( TEXT3, $row['list'] );
		$tpl->assign( SKINNUM, $skinnum );

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	
	function GetUserAID($user_id) {

		global $DB;

		$sql = "select a_id from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");

		// check name field. if exists, use it as poster name.
		$row = mysql_fetch_array( $result );
		
		return $row['a_id'];
	}
?>
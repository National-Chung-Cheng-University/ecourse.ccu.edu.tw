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
	else {
		show_page_d();
	}

	function show_page_d ( ) {
		global $PHPSESSID, $a_id, $graph, $course_id;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
		$Q1 = "select * from concept_map where a_id = '$a_id'";
		($result = mysql_db_query( $DB.$course_id, $Q1 ) ) or die("資料庫讀取錯誤");
		$row = mysql_fetch_array($result);
		
		$tpl = new FastTemplate("./templates");
		$tpl->assign( AID, $a_id );
		$tpl->assign( PHPID, $PHPSESSID );
		$tpl->define(array("main" => "concept_map.tpl"));
		$tpl->assign( AID, $a_id );
		$tpl->assign( PHPID, $PHPSESSID );
		if ( $graph == "" || $graph == NULL ) {
			if ( $row['graph'] == "" || $row['graph'] == NULL ) {
				$tpl->assign( GRAPH, "<DIV class=obj id=root style=\"BORDER-TOP-WIDTH: 1px; BORDER-LEFT-WIDTH: 1px; BACKGROUND: white; LEFT: 376px; BORDER-BOTTOM-WIDTH: 1px; WIDTH: 100px; CURSOR: hand; TOP: 71px; BORDER-RIGHT-WIDTH: 1px\" lineNr=\"2\" divNr=\"2\" deletable=\"f\" childNode=\"0,1,\" parentNode=\"\"></DIV>");
			}
			else {
				$graph = stripslashes( $row['graph'] );
				$tpl->assign( GRAPH, $graph );
			}
		}
		else {
			$graph = stripslashes( $graph );
			$tpl->assign( GRAPH, $graph );
		}

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	
	function do_record() {
		global $graph, $course_id, $a_id, $version;
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
		$Q1 = "update concept_map set graph='$graph' where a_id = '$a_id'";
		($result = mysql_db_query( $DB.$course_id, $Q1 ) ) or die("資料庫寫入錯誤");
		$tpl = new FastTemplate("./templates");

		$tpl->define(array("main" => "concept_map.tpl"));
		$tpl->assign( AID, $a_id );
		$tpl->assign( PHPID, $PHPSESSID );
		if ( $graph == "" || $graph == NULL) {
			$tpl->assign( GRAPH, "<DIV class=obj id=root style=\"BORDER-TOP-WIDTH: 1px; BORDER-LEFT-WIDTH: 1px; BACKGROUND: white; LEFT: 376px; BORDER-BOTTOM-WIDTH: 1px; WIDTH: 100px; CURSOR: hand; TOP: 71px; BORDER-RIGHT-WIDTH: 1px\" lineNr=\"2\" divNr=\"2\" deletable=\"f\" childNode=\"0,1,\" parentNode=\"\"></DIV>");
		}
		else {
			$graph = stripslashes( $graph );
			$tpl->assign( GRAPH, $graph );
		}
		
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
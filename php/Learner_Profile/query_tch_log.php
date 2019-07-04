<?php
	/* ------------------ */
	/* Written by carlyle */
	/* ------------------ */

        require 'fadmin.php';

        if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)))
                show_page("not_access.tpl","權限錯誤");

        include("class.FastTemplate.php3");
        $tpl = new FastTemplate("./templates");
        $tpl->define(array(body => "query_tch_log.tpl"));
	$tpl->define_dynamic("record","body");

        $c1 = $_POST['checkbox1']; //身份證字號
        $c2 = $_POST['checkbox2']; //科目編碼&班別
        $c3 = $_POST['checkbox3']; //IP

	$search_keyword = "";
	$sql_query = "";
	if ($c1) {
		if (!$_POST['id']) show_page("not_access.tpl","身份證字號不可為空!");
		$search_keyword = $_POST['id'];
		$sql_query = "SELECT user.id,log.tag2,log.tag1,log.mtime,log.tag4 FROM user,log WHERE user.a_id = log.user_id and log.event_id = '12' and " . "user.id = '" . $search_keyword . "' ORDER BY log.mtime DESC";
		$tpl->assign("#SEARCH_KEYWORD#","<strong>" . $search_keyword . "</strong>&nbsp;&nbsp;&nbsp;(身份證字號)");

	} else if ($c2) {
		if (!$_POST['course1'] || !$_POST['course2']) show_page("not_access.tpl","科目編碼及班別不可為空!");
		$search_keyword = $_POST['course1'] . "_" . $_POST['course2'];
		$sql_query = "SELECT user.id,log.tag2,log.tag1,log.mtime,log.tag4 FROM user,log WHERE user.a_id = log.user_id and log.event_id = '12' and " . "log.tag2 = '" . $search_keyword . "' ORDER BY log.mtime DESC";
		$tpl->assign("#SEARCH_KEYWORD#","<strong>" . $search_keyword . "</strong>&nbsp;&nbsp;&nbsp;(科目編碼及班別)");

	} else if ($c3) {
		if (!$_POST['ip']) show_page("not_access.tpl","IP不可為空!");
		$search_keyword = $_POST['ip'];
		$sql_query = "SELECT user.id,log.tag2,log.tag1,log.mtime,log.tag4 FROM user,log WHERE user.a_id = log.user_id and log.event_id = '12' and " . "log.tag1 = '" . $search_keyword . "' ORDER BY log.mtime DESC";
		$tpl->assign("#SEARCH_KEYWORD#","<strong>" . $search_keyword . "</strong>&nbsp;&nbsp;&nbsp;(IP)");
	}


	/* -------------------------------------------------------------------------------------- */
	/* SQL Query                                                                              */
	/* -------------------------------------------------------------------------------------- */
	global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD;

	if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD))) 
		show_page("not_access.tpl","資料庫連結錯誤!!");

	if (!($result = mysql_db_query($DB,$sql_query)))
		show_page("not_access.tpl","資料庫讀取錯誤!!");

	if (mysql_num_rows($result) == 0)
		show_page("not_access.tpl","無符合的結果!!");

	while ($row1 = mysql_fetch_array($result)) {
		$tpl->assign("#ID#",$row1['id']);
		$tpl->assign("#COURSE_ID#",$row1['tag2']);
		$tpl->assign("#IP#",$row1['tag1']);
		$tpl->assign("#TIME#",$row1['mtime']);
		$tpl->assign("#ACTION#",$row1['tag4']);
		$tpl->parse(ROWS,".record");
	}

        $tpl->parse(BODY,"body");
        $tpl->FastPrint("BODY");
?>

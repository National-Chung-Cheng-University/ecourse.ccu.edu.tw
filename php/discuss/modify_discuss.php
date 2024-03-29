<?
// Modify exist discuss information.
// $param : about:1st part
//          course_id  (in session.)
//          discuss_id (from discuss_list.php)
//          about:2nd part
//          course_id  (in session)
//          discuss_id (from self.)
//          discuss_name      (new title)
//          comment    (new comment)
//          is_group
//          group_num
//          access

	require 'fadmin.php';

	session_id($PHPSESSID);
	session_start();

	if(session_check_teach($PHPSESSID) != 2) {
		show_page("not_access.tpl", "你沒有權限執行此功能.\n<br>You have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	if(!isset($discuss_name) && !isset($comment)) {   // 輸出修改討論區畫面
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");

		if($version == "C") {
			$tpl->define(array(main => "modify_discuss.tpl"));
		}
		else {
			$tpl->define(array(main => "modify_discuss_E.tpl"));
		}
		
		$Q1 = "select * from discuss_info where a_id=$discuss_id";
		$result1 = mysql_db_query($DB.$course_id, $Q1) or die("資料庫查詢錯誤, $Q1");

		if( $row1 = mysql_fetch_array($result1) ) {
			$tpl->assign("NAME", $row1["discuss_name"]);
			$tpl->assign("COMMENT", $row1["comment"]);
			if($row1["group_num"] > 0) {
				$tpl->assign("IS_GROUP", "checked");
				$tpl->assign("NOT_GROUP", "");
				$tpl->assign("GROUP_NO", $row1["group_num"]);
			}
			else {
				$tpl->assign("IS_GROUP", "");
				$tpl->assign("NOT_GROUP", "checked");
				$tpl->assign("GROUP_NO", "");				
			}
			if($row1["access"]==0) {
				$tpl->assign("VIEW_PUBLIC", "checked");
				$tpl->assign("VIEW_PRIVATE", "");
			}
			else {
				$tpl->assign("VIEW_PUBLIC", "");
				$tpl->assign("VIEW_PRIVATE", "checked");			
			}
			$tpl->assign("DISCUSS_ID", $discuss_id);
			$tpl->assign("PHP_ID", $PHPSESSID);
		}
		else {
			show_page("not_access.tpl", "指定的討論區不存在.\n<br>The discuss group doesn't exist.");
			exit();
		}

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	else {

		if($isgroup == 0) {
			$access = 0;
			$group_num = 0;
		}
		else {
			$access = (int)$access;
			$group_num = (int)$group_num;
		}
		
		$Q1 = "update discuss_info set discuss_name='$discuss_name',comment='$comment',group_num=$group_num,access=$access where a_id=$discuss_id";
		mysql_db_query($DB.$course_id, $Q1) or die("資料庫查詢錯誤, $Q1");
		if(mysql_affected_rows()) {
			$errno=3;
		}
		else {
			$errno=4;
		}

		header("Location: dis_list.php?errno=$errno&PHPSESSID=$PHPSESSID");
	}
?>
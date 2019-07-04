<?
// For administrator.
// Used for list teacher's a_id, then list Log.
// List log program's name is TeacherTraceInfo.php.

	require 'fadmin.php';
	include("class.FastTemplate.php3");

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	// security check.
	if(!isset($PHPSESSID) || (session_check_teach($PHPSESSID)) == 2)
	{
		show_page("not_access.tpl", "你沒有權限使用此功能");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die( "資料庫鏈結錯誤!!" );
	mysql_select_db($DB) or die( "資料庫選擇錯誤!!" );

	$tpl = new FastTemplate("./templates");

	$tpl->define(array(main => "ShowTeacherListForTraceInfo.tpl"));

	$tpl->define_dynamic("teacher_list","main");

	$Q1 = "Select a_id,id,name From user Where authorization='1'";
	$result1 = mysql_query($Q1);

	if(mysql_num_rows($result1) > 0) {
		while( $row1 = mysql_fetch_array($result1) )	{
			$tpl->assign(A_ID, $row1[0]);
			$tpl->assign(TEACHER_ID, $row1[1]);

			if($row1[2] != "") {
				$tpl->assign(TEACHER_NAME, $row1[2]);
			}
			else {
				$tpl->assign(TEACHER_NAME, "(NULL)");				
			}
			$tpl->parse(ROWS, ".teacher_list");
		}
	}
	else {
			$tpl->assign(A_ID, "");
			$tpl->assign(TEACHER_NAME, "目前沒有任何教師存在");
			$tpl->assign(TEACHER_ID, "");	
	}

	$tpl->parse(BODY, "main");

	$tpl->FastPrint("BODY");
?>

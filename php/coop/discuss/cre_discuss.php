<?
// param:   $course_id  (session)
//          $discuss_name   (SELF)
//          $comment        (SELF)
//			$isgroup		(SELF)
//			$access			(SELF)
//          $error_mes      (SELF)
//			$amount			(SELF)

	require 'fadmin.php';

	if(session_check_teach($PHPSESSID) < 1 || check_group ( $course_id, $coopgroup, $coopcaseid ) != 2 ) {
		show_page("not_access.tpl", "你沒有權限執行此功能.\nYou have no permission to perform this function.");
		exit();
	}

	if( empty($amount) ) {   // 輸出新增討論區畫面
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");

		if($version == "C") {
			$tpl->define(array(main => "cre_discuss.tpl"));
		}
		else {
			$tpl->define(array(main => "cre_discuss_E.tpl"));
		}
		$tpl->assign("SKINNUM", $skinnum);
		$tpl->assign("TITLE", "新增討論區");
		$tpl->assign("MES", "$error_mes");
		$tpl->assign("PHP_ID", $PHPSESSID);

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	else {                                            // 新增討論區到DB
		mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
		$name = $discuss_name;

		// 新增資料到table discuss_info  (所有討論區與課程的關係一覽表)
		$sql = "insert into discuss_".$coopcaseid."_info (discuss_name,comment,group_num,access) values ('$name','$comment','$coopgroup','$access');";

		if(mysql_db_query($DBC.$course_id, $sql) or die("資料庫查詢錯誤, $sql")) {
			$errno = 0;
		}
		else {
			$errno = 1;
			header("Location: dis_list.php?errno=$errno&PHPSESSID=$PHPSESSID");
		}
		$discuss_id = mysql_insert_id();

		// 新增一個新table給此討論區, 名稱為discuss_[$discuss_id]  e.g. discuss_1, discuss_2, blahblahblah...
		$tablename = "discuss_".$coopcaseid."_".$discuss_id;
		$sql =	"create table $tablename (".
				"	a_id INT NOT NULL AUTO_INCREMENT,".
				"	title VARCHAR(64),".
				"	poster VARCHAR(64),".
				"	created DATETIME,".
				"	replied DATETIME,".
				"	parent INT,".
				"	body BLOB,".
				"	viewed MEDIUMINT,".
				"	type VARCHAR(32),".
				"	sound VARCHAR(64),".
				"	PRIMARY KEY(a_id)".
				");";

		if(mysql_db_query($DBC.$course_id, $sql) or die("資料庫查詢錯誤, $sql")) {
			$errno = 0;
			// 新增一個目錄供上傳檔案.
			mkdir("../../../$course_id/coop/$coopcaseid/$coopgroup/$discuss_id",0770);
		}
		else {
			$errno = 1;
		}

		header("Location: dis_list.php?errno=$errno&PHPSESSID=$PHPSESSID");
   }
?>
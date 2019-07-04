<?
// param:   $course_id  (session)
//          $discuss_name   (SELF)
//          $comment        (SELF)
//			$isgroup		(SELF)
//			$access			(SELF)
//          $error_mes      (SELF)
//			$amount			(SELF)
// last Update: 2002/07/30 by Autumn. 增加分組管理功能

	require 'fadmin.php';
	global $discuss_id;

	if(session_check_teach($PHPSESSID) != 2) {
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

		for( $i=1; $i<=$amount; $i++) {
			if( $amount!=1 ) {
				$group_num = $i;
				$name = str_replace("%d", " $i", $discuss_name);
			}
			else {
				$name = $discuss_name;
			}
			// 非小組討論區.
			if($isgroup == 0) {
				$access = 0;
				$group_num = 0;
			}

			// 新增資料到table  discuss_info  (所有討論區與課程的關係一覽表)
			$sql = "insert into discuss_info(discuss_name,comment,group_num,access) values('$name','$comment','$group_num','$access');";

			if(mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql")) {
				$errno = 0;

				//新增一個discuss欄位到user_profile 記錄已讀
				add_DB_colum();
			}
			else {
				$errno = 1;
				header("Location: dis_list.php?errno=$errno&PHPSESSID=$PHPSESSID");
			}

			
			$sql = "select student_id from discuss_group where group_num ='$group_num'";

			if($result1 = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql")) {
				$errno = 0;
			}
			else {
				$errno = 1;
				header("Location: dis_list.php?errno=$errno&PHPSESSID=$PHPSESSID");
			}
			while ( $row1 = mysql_fetch_array($result1) ) {
				$sql2 = "select a_id from user where id ='".$row1["student_id"]."'";

				if($result2 = mysql_db_query($DB, $sql2) or die("資料庫查詢錯誤, $sql")) {
					$errno = 0;
				}
				else {
					$errno = 1;
					header("Location: dis_list.php?errno=$errno&PHPSESSID=$PHPSESSID");
				}
				$row2 = mysql_fetch_array($result2);
				$student_id = $row2['a_id'];
				//新增到discuss_group_map
				$sql = "insert into discuss_group_map( discuss_id,student_id ) values('$discuss_id','$student_id');";

				if(mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql")) {
					$errno = 0;
				}
				else {
					$errno = 1;
					header("Location: dis_list.php?errno=$errno&PHPSESSID=$PHPSESSID");
				}
			}

			// 新增一個新table給此討論區, 名稱為discuss_[$discuss_id]  e.g. discuss_1, discuss_2, blahblahblah...
			$tablename = "discuss_".$discuss_id;
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

			if(mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql")) {
				$errno = 0;
				// 新增一個目錄供上傳檔案.
				mkdir("../../$course_id/board/$discuss_id",0770);
			}
			else {
				$errno = 1;
			} 
		}

		header("Location: dis_list.php?errno=$errno&PHPSESSID=$PHPSESSID");
   }


	function add_DB_colum(){
		global $DB, $course_id, $discuss_id;

		$Q1 = "SELECT a_id FROM discuss_info ORDER BY a_id desc";
		if (!($result = mysql_db_query($DB.$course_id,$Q1)))
			die("資料庫查詢錯誤, $Q1");
		$row = mysql_fetch_array($result);
		$discuss_id = $row['a_id'];

		$Q2 = "alter table user_profile add discuss_".$row['a_id']." Blob";
		if (!mysql_db_query($DB.$course_id,$Q2))
			die("資料庫查詢錯誤, $Q2");
	}

?>

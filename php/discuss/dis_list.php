<?
// param: $course_id (session)
//        $errno     ()
// Last Update: 2002/03/01 by kof9x. Add error message about discuss group backup.
// last Update: 2002/07/30 by Autumn. 增加分組管理功能

	require 'fadmin.php';
	include("class.FastTemplate.php3");
	update_status ("討論區一覽表");
	session_id($PHPSESSID);
	session_start();

	if( $version == "C" ) {
		$error_msg[0] = "新討論區建立 <font color='blue'>成功</font> ";
		$error_msg[1] = "新討論區建立 <font color='blue'>失敗</font>";
		$error_msg[2] = "討論區刪除 <font color='blue'>成功</font> ";
		$error_msg[3] = "討論區資料更新 <font color='blue'>成功</font> ";
		$error_msg[4] = "討論區資料更新 <font color='blue'>失敗</font> ";
		$error_msg[5] = "討論區資料備份 <font color='blue'>成功</font><br>\n".
						"<a href='../../$course_id/textbook/misc/backup.tar.gz'>點選此連結可下載備份</a>";
		$error_msg[6] = "討論區資料備份 <font color='blue'>失敗</font> ";
	}
	else {
		$error_msg[0] = "New Discuss Group Created.";
		$error_msg[1] = "New Discuss Group Creation <font color='blue'>Failed</font>";
		$error_msg[2] = "Discuss Group Deleted.";
		$error_msg[3] = "Discuss Group information Updated.";
		$error_msg[4] = "Discuss Group information Update <font color='blue'>Failed</font>";	
		$error_msg[5] = "Discuss Group Data Dump succeed.\n".
						"<a href='../../$course_id/textbook/misc/backup.tar.gz'>Click here to download</a>";
		$error_msg[6] = "Discuss Group Data Dump <font color='blue'>Failed</font> ";
	}


	$tpl = new FastTemplate("./templates");



	//linsy
	if($_GET['sort'] == "")
		$sort = "DESC";
	elseif($sort == "ASC")
		$sort = "DESC";
	elseif($sort == "DESC")
		$sort = "ASC";
	$tpl->assign("SORT", $sort);
	$field = $_GET['field'];
	$tpl->assign("FIELD", $field);



	// 中英文 + 老師/學生判斷
	if($version == 'C') {
		if(session_check_teach($PHPSESSID) >= 2 ) {
			$tpl->define(array(main => "dis_list_tch.tpl"));
		}
		else {
			$tpl->define(array(main => "dis_list_stu.tpl"));		 
		}
	}
	else {
		if(session_check_teach($PHPSESSID) >= 2 ) {	   
			$tpl->define(array(main => "dis_list_tch_E.tpl"));   
		}
		else {
			$tpl->define(array(main => "dis_list_stu_E.tpl"));
		}
	}

	$tpl->define_dynamic("discuss_list","main");
	$tpl->assign("SKINNUM", $skinnum);
	$tpl->assign("TITLE", "討論區一覽");

	if(isset($errno)) 
		$tpl->assign("ERROR_MSG", $error_msg[$errno]);
	else
		$tpl->assign("ERROR_MSG", "");


	$tpl->assign("PHP_SESS", $PHPSESSID);

	// 由資料庫中讀出屬於此科目的討論區, 並輸出
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	//使用者的a_id
	$sql = "select a_id from user where id = '$user_id'";
	$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");
	$row = mysql_fetch_array($result);
	$a_id = $row['a_id'];
	
	// 查詢資料庫
	//$sql = "select * from discuss_info order by group_num,a_id";
	//linsy@20140313, 讓使用者可以根據討論區標題排序
	if($field != "")
		$sql = "select * from discuss_info order by $field $sort" . ",group_num,a_id";
	else
		$sql = "select * from discuss_info order by group_num,a_id";

	$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

	if(mysql_num_rows($result) > 0) {    // 檢查是否有討論區存在
		$counter = 0;

		// 輸出討論區一覽.
		while($row = mysql_fetch_array($result)) {
			// 判斷老師/學生, 如果使用者為學生, 需判斷分組討論區是否公開, 以及此學生是否屬於此組組員.
			if( session_check_teach($PHPSESSID) < 2 ) {
				//學生的
				if ( $row['access'] != "0" ) {
					$sql = "select * from discuss_group_map where discuss_id='".$row['a_id']."' and student_id ='$a_id'";
					$result2 = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
					if(mysql_num_rows($result2) == 0){
						continue;
					}
				}
			}
			$tpl->assign("DIS_ID", $row["a_id"]);
			$tpl->assign("DEL_NAME", "discuss_id[$counter]");
		  
			$tpl->assign("DIS_NAME", $row["discuss_name"]);
			$tpl->assign("DIS_COMMENT", $row["comment"]);

			// 判斷此討論區對目前使用者的訂閱狀況.
			$sql2 = "select * from discuss_subscribe where user_id='$user_id' and discuss_id=".$row["a_id"];
			$result2 = mysql_db_query($DB.$course_id, $sql2)  or die("資料庫查詢錯誤, $sql2");
			if(mysql_num_rows($result2) > 0) {
				if($version == 'C') {
					$tpl->assign("SUB_STATUS", "<font color='red'>已訂</font>");
				}
				else {
					$tpl->assign("SUB_STATUS", "<font color='red'>Subscribed</font>");
				}
			}
			else {
				if($version == 'C') {
					$tpl->assign("SUB_STATUS", '未訂');
				}
				else {
					$tpl->assign("SUB_STATUS", "Not Subscribed");
				}
			}

			// 判斷是否為分組討論區.
			if($row["group_num"] == 0) {
				if($version == "C") {
					$tpl->assign("DIS_TYPE", "一般討論區");
				}
				else {
					$tpl->assign("DIS_TYPE", "Normal");			     
				}
			}
			else {
				if($version == "C") {
					$tpl->assign("DIS_TYPE", "第".$row["group_num"]."小組討論區");
				}
				else {
					$tpl->assign("DIS_TYPE", "Team ".$row["group_num"]." discussion group");			  
				}
			}

			$tpl->assign("ART_LIST", "article_list.php?discuss_id=".$row["a_id"]."&group_num=".$row["group_num"]."&log=1&PHPSESSID=".$PHPSESSID);

			/* 紀錄討論區的log.
			if((session_check_teach($PHPSESSID) != 0) && (strcmp($guest,"1") != 0) && (session_check_teach($PHPSESSID) != 3))
				$tpl->assign("LOG_PRG", "onClick=\"window.open('../log.php?event_id=5&PHPSESSID=$PHPSESSID','log5','width=1,height=1');\"");
			else
			*/
			$tpl->assign("LOG_PRG", "");

			// 顏色控制.
			if($counter%2 == 0) 
				$tpl->assign("DISCOLOR", "#ffffff");
			else
				$tpl->assign("DISCOLOR", "#edf3fa");

			$tpl->parse(ROWL, ".discuss_list");
			$counter++;
		}
	}
	if ($counter == 0 ) {
		$tpl->assign("DIS_ID", "");
		$tpl->assign("DEL_NAME", "");
		$tpl->assign("DIS_TYPE", "");
		$tpl->assign("ART_LIST", "");
		$tpl->assign("DIS_NAME", "");
		$tpl->assign("DIS_COMMENT", "");
		$tpl->assign("LOG_PRG", "");
		$tpl->assign("SUB_STATUS", "");

		$tpl->parse(ROWL, ".discuss_list");
	}
   

	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>

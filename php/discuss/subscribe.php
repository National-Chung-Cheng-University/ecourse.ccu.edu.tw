<?
// 討論區訂閱程式
// param           : action       (SELF, decides go to part 1 or 2)
//       (at part1): nothing.
//                   all param is same as dis_list.php
//       (ar part2): discuss_id[] (SELF , 要訂閱的討論區編號)
//                   user_id      (session)
//                   course_id    (session)

	require 'fadmin.php';

	session_id($PHPSESSID);
	session_start();

	if ( $guest == "1" ) {
			show_page( "not_access.tpl" ,"對不起！您沒有權限使用此功能！！\n<br>Sorry, guest user cannot use this function.");
			exit;
	}
	
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	if(empty($action)) {
		// 輸出給使用者選擇的畫面
		// 基本上和dis_list.php裡的是一樣的, 為了某些原因才另外寫一個...

		// 如果使用者未登錄e-mail, 則要求使用者先登錄.

		$sql = "select email from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");
		if(mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			if(strlen($row["email"]) == 0) {
				show_page("not_access.tpl", "您尚未於系統中輸入e-mail資料.\n<br>You have not entered your e-mail account at our system.");
				exit();
			}
		}

		// 判斷老師/學生, 如果使用者為學生, 需判斷分組討論區是否公開, 以及此學生是否屬於此組組員.
		if(session_check_teach($PHPSESSID)==2) {
			// 老師的   全部討論區都可以看.
			$sql = "select * from discuss_info order by group_num,a_id";
		}
		else {
			// 學生的
			// 先從discuss_group 查出此使用者的組別.
			$sql = "select group_num from discuss_group where student_id='$user_id'";
			$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

			// 例外處理. 處理老師尚未分組的錯誤情況
			if(mysql_num_rows($result) > 0)  {
				$row = mysql_fetch_array($result);
				$grp_num = $row["group_num"];
				$sql = "select * from discuss_info where access=0 or group_num=$grp_num order by group_num,a_id";
			}
			else {
				$sql = "select * from discuss_info where access=0 order by group_num,a_id";
			}
		}

		// 查詢資料庫
		$result = mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

		if(mysql_num_rows($result) > 0) {    // 檢查是否有討論區存在
			include("class.FastTemplate.php3");
			$tpl = new FastTemplate("./templates");
			if($version == 'C') {
				$tpl->define(array(main => "subscribe.tpl"));
			}
			else {
				$tpl->define(array(main => "subscribe_E.tpl"));
			}
			$tpl->define_dynamic("discuss_list","main");
			// 用來做變數的...
			$counter = 0;

			// 輸出討論區一覽.
			while($row = mysql_fetch_array($result)) {
				$tpl->assign("DIS_ID", $row["a_id"]);		  
				$tpl->assign("DIS_NAME", $row["discuss_name"]);
				$tpl->assign("DIS_COMMENT", $row["comment"]);

				$tpl->assign("SUB_NAMEA", "subscribed[$counter]");
				$tpl->assign("SUB_NAMEB", "discuss_id[$counter]");
				$tpl->assign("SUB_NAMEC", "status[$counter]");

				// 判斷此討論區對目前使用者的訂閱狀況. not implemented at 7/26
				$sql2 = "select * from discuss_subscribe where user_id='$user_id' and discuss_id=".$row["a_id"];
				$result2 = mysql_db_query($DB.$course_id, $sql2) or die("資料庫查詢錯誤, $sql2");
				if(mysql_num_rows($result2) > 0) {
					$tpl->assign("SUB_CHECKED", "checked");
					$tpl->assign("SUB_STAT", "1");
				}
				else {
					$tpl->assign("SUB_CHECKED", "");
					$tpl->assign("SUB_STAT", "0");
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

				// 顏色控制.
				if($counter%2 == 0) 
					$tpl->assign("SUBCOLOR", "#ffffff");
				else
					$tpl->assign("SUBCOLOR", "#edf3fa");

				$tpl->parse(ROWL, ".discuss_list");
				$counter++;
			}
			$tpl->assign("PHP_ID", $PHPSESSID);
			$tpl->parse(BODY, "main");

			$tpl->FastPrint(BODY);
		}
		else {
			show_page("not_access.tpl", "目前沒有任何討論區存在.<br>\nNO discussion group exists now.", "", "<a href='dis_list.php'>Back</a>");
		}
	}
	else {
		// 實際處理寫入資料庫的部分.

		// 產生欲使用的sql語法. (insert/delete)
		for($i=0;$i<sizeof($status);$i++) {
			if( ($status[$i] == 1) && ($subscribed[$i] == 1) ) {
				// 已訂閱不停訂
			}
			else if( ($status[$i] == 0) && ($subscribed[$i] == 1) ) {
				// 新訂閱的討論區.
				$sql = "insert into discuss_subscribe(user_id,discuss_id) values('$user_id',".$discuss_id[$i].")";

				mysql_db_query($DB.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
				if( mysql_affected_rows() == 0 ) {
					show_page("not_access.tpl", "資料庫寫入失敗.", "", "<a href='dis_list.php'>Back</a>");
				}
			}
			else if( ($status[$i] == 1) && ($subscribed[$i] == 0) ) {
				// 退訂討論區.
				$sql = "delete from discuss_subscribe where user_id='$user_id' and discuss_id=".$discuss_id[$i];
				mysql_db_query($DB.$course_id, $sql)  or die("資料庫查詢錯誤, $sql");
				if( mysql_affected_rows() == 0 ) {
					show_page("not_access.tpl", "資料庫更新失敗.", "", "<a href='dis_list.php'>Back</a>");
				}
			}
			else {
				// 原本未訂閱, 同時無意訂閱此討論區
			}
		}
		
		header("Location: dis_list.php?PHPSESSID=$PHPSESSID");
	}
?>
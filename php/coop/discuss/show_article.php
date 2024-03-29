<?
	session_id($PHPSESSID);
	session_start();

//   param  : $discuss_id (article_list.php / SELF, required.)
//            $article_id (article_list.php / SELF, required.)
//            $parent  (SELF,optional)
//            $course_id  (session)
//            $sort

	
	require 'fadmin.php';
	update_status ("品味文章");
	if(session_check_teach($PHPSESSID) < 1 || check_group ( $course_id, $coopgroup, $coopcaseid ) == 0 ) {
		show_page("not_access.tpl", "你沒有權限執行此功能.\nYou have no permission to perform this function.");
		exit();
	}
	
	include("class.FastTemplate.php3");
	
	function GetUserName($user_id) {
		
		global $DBC, $DB;

		$sql = "select name,nickname from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");

		// check name field. if exists, use it as poster name.
		if(mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			if( strcmp($row["nickname"], "" )!=0) {
				$poster = $row["nickname"];
			}
			elseif(strcmp($row["name"], "" ) !=0 ) {
				$poster = $row["name"];
			}
			else {
				$poster = $user_id;
			}
		}
		else {
			// Default.
			$poster = $user_id;
		}

		return $poster;
	}

	$tpl = new FastTemplate("./templates");

	if($version == "C") {
		if(check_group ( $course_id, $coopgroup, $coopcaseid ) == 2) {
			$tpl->define(array(main => "show_article_tch.tpl"));
		}
		else {
			$tpl->define(array(main => "show_article_stu.tpl"));			
		}
	}
	else {
		if(check_group ( $course_id, $coopgroup, $coopcaseid ) == 2) {
			$tpl->define(array(main => "show_article_tch_E.tpl"));
		}
		else {
			$tpl->define(array(main => "show_article_stu_E.tpl"));		
		}
	}

	$tpl->define_dynamic("reply_list","main");

	$tpl->assign("SKINNUM", $skinnum);
	$tpl->assign("TITLE", "文章內容");
	$tpl->assign("DIS_ID", $discuss_id);
	$tpl->assign("PAGE", $page);

	
	if(isset($parent)) // 非第一篇文章
		$tpl->assign("PRT_ID", $parent);
	else {             // 第一篇文章
		$tpl->assign("PRT_ID", $article_id);
		$parent = $article_id;
	}
	
	$tpl->assign("ART_ID", $article_id);
    
	// 讀出文章
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
	
	$tablename = "discuss_".$coopcaseid."_".$discuss_id;
	$sql = "select *,TO_DAYS(created),TO_DAYS(NOW()) from $tablename where a_id=$article_id;";
	$result = mysql_db_query($DBC.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	
	// 設定主題, 文章內容等值
	$row = mysql_fetch_array($result);
	$poster = $row["poster"];
	$tpl->assign("POSTER", GetUserName($row["poster"]));
	$tpl->assign("CREATED", $row["created"]);
	$tpl->assign("ART_T", $row["title"]);

	// added at 01/11/22. 學生的刪除功能.
	if( check_group ( $course_id, $coopgroup, $coopcaseid ) != 2 ) {

		if( ( strcmp($user_id,$row["poster"])==0 ) && 
			( ( $row["parent"]!=0 ) || ( strcmp($row["created"], $row["replied"])==0 ) ) &&
			( (int)$row["TO_DAYS(NOW())"]-(int)$row["TO_DAYS(created)"]<1 ) ) 
		{

			if( $version == "C") {
				$tpl->assign("DELETELINK", "&nbsp;|&nbsp;<a href='del_article.php?discuss_id=$discuss_id&del_id[0]=$article_id' onclick=\"return confirm('確定要刪除此篇文章與相關檔案?')\">刪除此文章</a>");
			}
			else {
					$tpl->assign("DELETELINK", "&nbsp;|&nbsp;<a href='del_article.php?discuss_id=$discuss_id&del_id[0]=$article_id' onclick=\"return confirm('Are you sure?')\">刪除此文章</a>");		
			}

		}
		else {
			$tpl->assign("DELETELINK", NULL);
		}

	}


	// 改變回文內容. 將回覆的文章顏色改變.
	$body = explode("\n", htmlspecialchars($row["body"]) );
	$flag = false;
	for( $i=0; $i<sizeof($body); $i++) {
		$pos = strpos($body[$i], "&gt");
		if(  ($pos == 0) && ($pos !== false)  && ( !$flag ) ) {
			$flag = true;
			$body[$i] = "<font style='background-color: #D0E0D0' size='-1' color = '#005500'>".$body[$i];
		}
		else if ( $flag && ($pos === false) ) {
			$body[$i] = "</font>".$body[$i];
			$flag = false;
		}
	}

	$body = ereg_replace("\n", "<br>\n", implode("\n", $body));
	$body = ereg_replace("  ", "　", $body);
	$tpl->assign("ART_BODY", $body );


    // 產生相關檔案連結
	if(strlen($row["type"]) > 0) {
		$attach = "../../../$course_id/coop/$coopcaseid/$coopgroup/$discuss_id/$article_id.".$row["type"];
		$link = "/$course_id/coop/$coopcaseid/$coopgroup/$discuss_id/$article_id.".$row["type"];

		$tpl->assign("FILELINK", "<a href='$link'>$article_id.".$row["type"]."</a>");
		$tpl->assign("FILESIZE", filesize($attach));
    	}
	else {
	   	$tpl->assign("FILELINK", "");
	   	$tpl->assign("FILESIZE", "");
	}

	// 增加文章點閱次數
	if($row["parent"] == 0)
	   $sql = "update $tablename set viewed=viewed+1 where a_id=$article_id;";
	   
	mysql_db_query($DBC.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

	$tpl->assign("REPLY", "show_article.php?discuss_id=$discuss_id&article_id=$parent&page=$page&PHPSESSID=$PHPSESSID");
	
   	// 輸出相關文章鏈結, 此處選出第一篇文章外的文章
	if($row["parent"] == 0) {   // 正在show的是整個主題第一篇文章 
		$sql2 = "select * from $tablename where parent=$article_id order by a_id";

		// 產生相關文章連結之首篇文章連結
		$tpl->assign("PARENT", "show_article.php?discuss_id=$discuss_id&article_id=$article_id&page=$page");
		$tpl->assign("PAR_T", $row["title"]);
		$tpl->assign("PAR_P", GetUserName($row["poster"]));
		$tpl->assign("PAR_C", $row["created"]);
	}
	else {                      // 非第一篇文章
		$sql2 = "select * from $tablename where parent=$parent order by a_id";

		// 產生相關文章連結之首篇文章連結		
		$sql3 = "select * from $tablename where a_id=$parent";
		$result3 = mysql_db_query($DBC.$course_id, $sql3) or die("資料庫查詢錯誤, $sql3");
		if( $row3 = mysql_fetch_array($result3) ) {
			$tpl->assign("PARENT", "show_article.php?discuss_id=$discuss_id&article_id=$parent&page=$page");
			$tpl->assign("PAR_T", $row3["title"]);
			$tpl->assign("PAR_P", GetUserName($row3["poster"]));
			$tpl->assign("PAR_C", $row3["created"]);
		}
	}

	// 最新回覆文章在上面或下面
	if(isset($sort) && ($sort==1)) {
		$sql2.= " desc";
		$sort = 0;
	}
	else {
		$sort = 1;
	}

	// 改變文章排列順序連結.
	if($parent == $article_id) {
		$tpl->assign("GET_VARS", "discuss_id=$discuss_id&article_id=$article_id&sort=$sort");
	}
	else {
		$tpl->assign("GET_VARS", "parent=$parent&&discuss_id=$discuss_id&article_id=$article_id&sort=$sort");		
	}

	// error handle.
	$tpl->assign("REPLIER", "");
	$tpl->assign("REPLIED", "");
 	$tpl->assign("THIS_POINTER", "");
	if($version == "C") {
		$tpl->assign("REP_T", "目前沒有任何回應文章");
	}
	else {
		$tpl->assign("REP_T", "There is no reply about this atricle.");
	}

	$result2 = mysql_db_query($DBC.$course_id, $sql2) or die("資料庫查詢錯誤, $sql2");
	if(mysql_num_rows($result2) > 0) {
       	// 其他文章連結.
		while($row = mysql_fetch_array($result2)) {
			$tpl->assign("REPLY", "show_article.php?parent=$parent&discuss_id=$discuss_id&article_id=".$row["a_id"]."&page=$page&PHPSESSID=$PHPSESSID");
	      	$tpl->assign("REP_T", $row["title"]);
          	$tpl->assign("REPLIER", "| ".GetUserName($row["poster"])." |");
	      	$tpl->assign("REPLIED",$row["created"]);

			if($row["a_id"] == $article_id && $parent != $article_id) {
				$tpl->assign("THIS_POINTER", "==>");
			}
			else {
				$tpl->assign("THIS_POINTER", "");
			}
          
			$tpl->parse(ROWA, ".reply_list");
	   	}
	}

	// added in 9/12. handle voice player applet code.
	// Help data is in learn/package/xxxxxx.html .
	$objectcode = "<object classid=\"clsid:A809FC66-1FEB-11D5-A00F-00D0B74E04B7\" id=\"AudioBoard1\" ".
					"width=\"85\" height=\"33\" ".
					"codebase=\"http://$SERVER_NAME/learn/packages/audioboard.cab#version=1,0,0,1\" ".
					"standby=\"Loading AudioBoard Components\" ".
					"type=\"application/x-oleobject\">\n";
	// server name.
	$param[0] = "<param name=\"Server\" value=\"$SERVER_NAME\">";
	// voice file URL.
	$sql = "select sound from $tablename where a_id=$article_id";
	$result = mysql_db_query($DBC.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
	$row = mysql_fetch_array($result);
	$param[1] = "<param name=\"Url\" value=\"/discuss/attach/".$row["sound"]."\">";
	// download  voice filename.
	$param[2] = "<param name=\"FilePath\" value=\"c:\_download.gsm\">";
	// fixed param.
	$param[3] = "<param name=\"SystemMode\" value=\"101\">";
	$param[4] = "<param name=\"Codec\" value=\"1\">";
	$objectend = "</object>";
	$tpl->assign("PLAYER_CODE", $objectcode.implode("\n",$param).$objectend);

	$tpl->parse(BODY, "main");
	
	$tpl->FastPrint(BODY);
?>
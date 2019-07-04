<?
//   param  : $discuss_id (article_list.php / show_article.php, required.)
//            $parent  (show_article.php,optional)
//            $reply_id   (show_artcle.php, optional)
//            $course_id  (session)
//            $action  (SELF, optional)
//            $user_id    (session, is user->id)
//   log is required.
//   

	require 'fadmin.php';
	update_status ("文筆揮毫");
	include("mail.php");

	session_id($PHPSESSID);
	session_start();

	if(session_check_teach($PHPSESSID) < 1 || check_group ( $course_id, $coopgroup, $coopcaseid ) == 0 ) {
		show_page("not_access.tpl", "你沒有權限執行此功能.\nYou have no permission to perform this function.");
		exit();
	}

	// 將guest user排除.
	if( strcmp( $user_id, "guest" ) == 0 ) {
		show_page("not_access.tpl", "你沒有權限執行此功能.\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");

	$tablename = "discuss_".$coopcaseid."_".$discuss_id;

	if(empty($action)) {      // 發表新文章/回覆文章
		include("class.FastTemplate.php3");

		$tpl = new FastTemplate("./templates");

		if($version == "C") {
			$tpl->define(array(main => "post_article.tpl"));
		}
		else {
			$tpl->define(array(main => "post_article_E.tpl"));
		}
		
		$tpl->assign("SKINNUM", $skinnum);
		$tpl->assign("TITLE", "發表新文章/回覆文章");
		$tpl->assign("DIS_ID", $discuss_id);
		$tpl->assign("PAGE", $page);

		if(isset($parent) && isset($reply_id)) {    // 回覆文章
			// 將原文章內容取出
			$sql = "select * from $tablename where a_id=$reply_id";
			$result = mysql_db_query($DBC.$course_id, $sql) or die("資料庫查詢錯誤, $sql");

			if(	$row = mysql_fetch_array($result) )  {
				// 產生回覆文章的部分內容
				$title = "RE: ".$row["title"];
				$body = explode("\r\n",$row["body"]);
				for($i=0;$i<sizeof($body);$i++) {
					$body[$i] = "&gt".$body[$i]; 
				}
				$body = implode("\r\n",$body);

				$tpl->assign("ART_T", $title);
				$tpl->assign("ART_BODY", $body);
				$tpl->assign("PARENT", $parent);
			}
			else {
				show_page("not_access.tpl", "發生不明錯誤, 請稍後再試.\n<br>Undefined error occoured. Please try later.");
				exit();
			}
		}
		else {                  // 新文章
			$tpl->assign("ART_T", "");
			$tpl->assign("ART_BODY", "");
			$tpl->assign("PARENT", "0");
		}

		// added in 9/12. handle voice recorder applet code.
		// Help data is in /package/xxxxxx.html .
		// THIS FUNCTION REQUIRES TO ADD AN ACCOUNT ON SERVER (discuss/discuss@audio),
		// then add a symbolic link named 'attach', to the directory where u wish to upload the voice data.
		$objectcode = "<object classid=\"clsid:A809FC66-1FEB-11D5-A00F-00D0B74E04B7\" id=\"AudioBoard1\" ".
					"width=\"85\" height=\"33\" ".
					"codebase=\"http://$SERVER_NAME/learn/packages/audioboard.cab#version=1,0,0,1\" ".
					"standby=\"Loading AudioBoard Components\" ".
					"type=\"application/x-oleobject\">\n";
		// local temp voice file.
		$param[0] = "<param name=\"FilePath\" value=\"c:\_upload.gsm\">";
		// file upload mode.
		$param[1] = "<param name=\"UploadMode\" value=\"1\">";
		// server name.
		$param[2] = "<param name=\"server\" value=\"$SERVER_NAME\">";
		// server voice filename.
		// Notice: because it's unable to know article_id at this time,
		//         some problem has to be solved.
		//         find article number posted by current user first.
		$sql = "select * from $tablename where poster='$user_id'";
		$result = mysql_db_query($DBC.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
		$suppose_id = mysql_num_rows($result)+1;
		$param[3] = "<param name=\"UploadFile\" value =\"".mt_rand().".gsm\">";
		// fixed param.
		$param[4] = "<param name=\"SystemMode\" value=\"100\">";
		$param[5] = "<param name=\"SDThreshold\" value=\"30000\">";
		$param[6] = "<param name=\"RecordSeconds\" value=\"15\">";
		$param[7] = "<param name=\"SilenceCompensation\" value=\"2\">";
		$param[8] = "<param name=\"Codec\" value=\"1\">";
		$objectend = "</object>";
		$tpl->assign("RECORDER_CODE", $objectcode.implode("\n",$param).$objectend);

		$tpl->assign("SOUNDNAME", mt_rand().".gsm");

		$tpl->assign("PHP_ID", $PHPSESSID);

		$tpl->parse(BODY, "main");

		$tpl->FastPrint(BODY);
	}
	else {
		// 存入檔案的副檔名. 這是為了讓使用者能以正確的程式開啟夾檔....
		if(is_file($attach)) {
			$type = explode(".",$attach_name);
			$type = $type[sizeof($type)-1];
		}

		$created = date("Y/m/d H:i:s",time());
		$replied = $created;
		
		$sql =	"insert into $tablename(title,poster,created,replied,parent,body,viewed,type,sound) ".
				"values('$title','$user_id','$created','$replied',$parent,'$body',0,'$type','$sound');";
		mysql_db_query($DBC.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
		$this_id = mysql_insert_id();


		// 修改原來文章的 replied
		if($parent!=0) { 
			$sql = "update $tablename set replied='$replied' where a_id=$parent;";
			mysql_db_query($DBC.$course_id, $sql) or die("資料庫查詢錯誤, $sql");
		}

		// 實際存入的檔案名稱為 [此文章的編號].$type, 於/$course_id/board/$discuss_id/ 下.
		// 存放附加檔案
		if(is_file($attach)) {
			if( !fileupload( $attach, "../../../$course_id/coop/$coopcaseid/$coopgroup/$discuss_id", "$this_id.$type" ) ){
				show_page("not_access", "檔案寫入錯誤", "<a href='discuss.php'>回討論區一覽</a>");
			}
		}

		// 寫入log.
		// 老師和使用者都要, 除了guest user.
		if((session_check_teach($PHPSESSID) != 0) && ( strcmp( $user_id, "guest" ) !=0 ) && (check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2)) {
			add_log_coop( 6, $user_id, "", $course_id, "", "", $coopgroup, $coopcaseid );
		}

		if( $parent==0 ) {
			header("Location: show_article.php?discuss_id=$discuss_id&article_id=$this_id&PHPSESSID=$PHPSESSID");
		}
		else {
			header("Location: show_article.php?discuss_id=$discuss_id&article_id=$this_id&parent=$parent&PHPSESSID=$PHPSESSID");
		}
	}
?>
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
	update_status ("�嵧���@");
	include("mail.php");

	session_id($PHPSESSID);
	session_start();

	if(session_check_teach($PHPSESSID) < 1 || check_group ( $course_id, $coopgroup, $coopcaseid ) == 0 ) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.\nYou have no permission to perform this function.");
		exit();
	}

	// �Nguest user�ư�.
	if( strcmp( $user_id, "guest" ) == 0 ) {
		show_page("not_access.tpl", "�A�S���v�����榹�\��.\nYou have no permission to perform this function.");
		exit();
	}

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	$tablename = "discuss_".$coopcaseid."_".$discuss_id;

	if(empty($action)) {      // �o��s�峹/�^�Ф峹
		include("class.FastTemplate.php3");

		$tpl = new FastTemplate("./templates");

		if($version == "C") {
			$tpl->define(array(main => "post_article.tpl"));
		}
		else {
			$tpl->define(array(main => "post_article_E.tpl"));
		}
		
		$tpl->assign("SKINNUM", $skinnum);
		$tpl->assign("TITLE", "�o��s�峹/�^�Ф峹");
		$tpl->assign("DIS_ID", $discuss_id);
		$tpl->assign("PAGE", $page);

		if(isset($parent) && isset($reply_id)) {    // �^�Ф峹
			// �N��峹���e���X
			$sql = "select * from $tablename where a_id=$reply_id";
			$result = mysql_db_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");

			if(	$row = mysql_fetch_array($result) )  {
				// ���ͦ^�Ф峹���������e
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
				show_page("not_access.tpl", "�o�ͤ������~, �еy��A��.\n<br>Undefined error occoured. Please try later.");
				exit();
			}
		}
		else {                  // �s�峹
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
		$result = mysql_db_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
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
		// �s�J�ɮת����ɦW. �o�O���F���ϥΪ̯�H���T���{���}�ҧ���....
		if(is_file($attach)) {
			$type = explode(".",$attach_name);
			$type = $type[sizeof($type)-1];
		}

		$created = date("Y/m/d H:i:s",time());
		$replied = $created;
		
		$sql =	"insert into $tablename(title,poster,created,replied,parent,body,viewed,type,sound) ".
				"values('$title','$user_id','$created','$replied',$parent,'$body',0,'$type','$sound');";
		mysql_db_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
		$this_id = mysql_insert_id();


		// �ק��Ӥ峹�� replied
		if($parent!=0) { 
			$sql = "update $tablename set replied='$replied' where a_id=$parent;";
			mysql_db_query($DBC.$course_id, $sql) or die("��Ʈw�d�߿��~, $sql");
		}

		// ��ڦs�J���ɮצW�٬� [���峹���s��].$type, ��/$course_id/board/$discuss_id/ �U.
		// �s����[�ɮ�
		if(is_file($attach)) {
			if( !fileupload( $attach, "../../../$course_id/coop/$coopcaseid/$coopgroup/$discuss_id", "$this_id.$type" ) ){
				show_page("not_access", "�ɮ׼g�J���~", "<a href='discuss.php'>�^�Q�װϤ@��</a>");
			}
		}

		// �g�Jlog.
		// �Ѯv�M�ϥΪ̳��n, ���Fguest user.
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
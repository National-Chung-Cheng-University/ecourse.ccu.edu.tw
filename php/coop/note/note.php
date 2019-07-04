<?php
	require 'fadmin.php';
	update_status ("觀看筆記本");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID) && ($check = check_group ( $course_id, $coopgroup, $coopcaseid )) > 1 ) ) {
		show_page( "not_access.tpl" ,"您沒有權限使用此功能");
	}

	if ( isset($submit) && ( $submit == "刪除" || $submit == "Del" )  ) {
		del_file();
		show_main ();
	}
	else if ( isset($submit) && ( $submit == "分享" || $submit == "Share" )  ) {
		share_file();
		show_main ();
	}
	else if ( isset($submit) && ( $submit == "不分享" || $submit == "Not Share" )  ) {
		share_file(0);
		show_main ();
	}
	else if ( $action == "edit" ) {
		edit_file();
	}
	else if ( $action == "add" ) {
		add_file();
	}
	else if ( $action == "update_file" ) {
		if ( $subject == "" || $content == "" ) {
			show_edit( "update_file" , $subject, $content, $aid );
		}
		else {
			do_update();
		}
	}
	else if ( $action == "add_file" ) {
		if ( $subject == "" || $content == "" ) {
			show_edit( "add_file" , $subject, $content );
		}
		else {
			do_add();
		}
	}
	else if ( $action == "list" ) {
		show_list ();
	}
	else if ( $action == "dump" ) {
		file_dump ();
	}
	else if ( $action == "show" ) {
		show_content();
	}
	else if ( $action == "share_note" ) {
		share_note();
	}
	else
		show_main ();
	
	function del_file () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $coopgroup, $coopcaseid, $user_id, $PHPSESSID;
		$Q1 = "select * from note_".$coopcaseid." where group_num = '$coopgroup' and student_id = '".GetUserAID($user_id)."'";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			while ( $row = mysql_fetch_array( $result ) ) {
				global $$row['a_id'];
				if ( $$row['a_id'] == "NO" ) {
					$Q2 = "delete from note_".$coopcaseid." where a_id = '".$row['a_id']."'";
					if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
						$message = "$message - 資料庫刪除錯誤!!";
					}
					if( (check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2) && (strcmp($guest,"1") != 0) && (session_check_teach($PHPSESSID) != 3)) {
						add_log_coop( 9, $user_id, "1", $course_id, "", "", $coopgroup, $coopcaseid );
					}
				}
			}
		}
	}

	function share_file ( $type = 1 ) {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $coopgroup, $coopcaseid, $user_id;
		$Q1 = "select * from note_".$coopcaseid." where group_num = '$coopgroup' and student_id = '".GetUserAID($user_id)."'";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		else if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			while ( $row = mysql_fetch_array( $result ) ) {
				global $$row['a_id'];
				if ( $$row['a_id'] == "NO" ) {
					$Q2 = "update note_".$coopcaseid." set share = '$type' where a_id = '".$row['a_id']."'";
					if ( !($result2 = mysql_db_query( $DBC.$course_id, $Q2 ) ) ) {
						$message = "$message - 資料庫更新錯誤!!";
					}
	
				}
			}
		}
	}

	function edit_file () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $PHPSESSID, $a_id, $coopcaseid, $course_id;
		$Q1 = "select * from note_".$coopcaseid." where a_id = '$a_id'";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		if ( mysql_num_rows($result) != 0 ) {
			($row = mysql_fetch_array( $result )) || die ("$Q1");
			show_edit( "update_file" , $row['subject'], $row['content'], $row['a_id'] );
		}
		else
			show_main();
	}
	
	function add_file () {
		show_edit( "add_file" );
	}
	
	function do_update () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $user_id, $course_id, $content, $subject, $coopcaseid, $aid;
		
		$Q1 = "update note_".$coopcaseid." set content='$content', subject='$subject' where a_id = '$aid'";
		($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) || die ("資料庫連結錯誤");
		($result = mysql_db_query( $DBC.$course_id, $Q1 )) || die ("資料庫寫入錯誤$Q1");
		show_main();
	}

	function do_add () {
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $user_id, $course_id, $content, $subject, $coopgroup, $coopcaseid, $PHPSESSID;
		
		$Q1 = "insert into note_".$coopcaseid." ( group_num, student_id, subject, content ) values ( '$coopgroup', '".GetUserAID($user_id)."', '$subject', '$content' )";

		($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) || die ("資料庫連結錯誤");
		($result = mysql_db_query( $DBC.$course_id, $Q1 )) || die ("資料庫寫入錯誤$Q1");
		if( (check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2) && (strcmp($guest,"1") != 0) && (session_check_teach($PHPSESSID) != 3)) {
			add_log_coop( 9, $user_id, "", $course_id, "", "", $coopgroup, $coopcaseid );
		}
		show_main();
	}

	function show_edit ( $action, $subject="", $content="", $a_id="") {
		global $version, $skinnum, $PHPSESSID;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->assign( SKINNUM , $skinnum );
		if ( $version == "C" )
			$tpl->define ( array ( body => "note_edit.tpl" ) );
		else
			$tpl->define ( array ( body => "note_edit_E.tpl" ) );

		$tpl->assign( SUBJECT , "$subject" );
		$tpl->assign( PHPSID , "$PHPSESSID" );
		$tpl->assign( CONTENT , "$content" );
		$tpl->assign( AID , "$a_id" );
		$tpl->assign( ACT , "$action" );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}


	function show_main () {
		global $version, $check, $skinnum, $teacher, $course_id, $coopgroup, $coopcaseid, $user_id;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->define ( array ( body => "note.tpl" ) );

		$tpl->define_dynamic ( "course_list" , "body" );

		$color = "#4d6eb2";
		$tpl->assign( COLOR , $color );
		if ( $version == "C" ) {
			$tpl->assign( TITLE , "筆記本列表" );
			$tpl->assign( ADD , "<a href=\"./note.php?action=add_file\">新增筆記本</a>" );
			$tpl->assign( CHECK , "<font color=#FFFFFF><b>選取</b></font>" );
			$tpl->assign( DATE , "<font color=#FFFFFF><b>日期</b></font>" );
			$tpl->assign( SUBJECT , "<font color=#FFFFFF><b>標題</b></font>" );
			$tpl->assign( STATUS , "<font color=#FFFFFF><b>狀態</b></font>" );
			$tpl->assign( LINK , "<font color=#FFFFFF><b>檔案連結</b></font>" );
			$tpl->assign( DOWNLOAD , "<font color=#FFFFFF><b>檔案下載</b></font>" );
			$tpl->assign( SHARE , "分享" );
			$tpl->assign( NOT_SHA , "不分享" );
			$tpl->assign( DELETE , "刪除" );
			$tpl->assign( CLEAR , "清除選擇" );
		}
		else {
			$tpl->assign( TITLE , "Note List" );
			$tpl->assign( ADD , "<a href=\"./note.php?action=add_file\">New Note</a>" );
			$tpl->assign( CHECK , "<font color=#FFFFFF><b>Check</b></font>" );
			$tpl->assign( DATE , "<font color=#FFFFFF><b>Date</b></font>" );
			$tpl->assign( SUBJECT , "<font color=#FFFFFF><b>Subject</b></font>" );
			$tpl->assign( STATUS , "<font color=#FFFFFF><b>Status</b></font>" );
			$tpl->assign( LINK , "<font color=#FFFFFF><b>File Link</b></font>" );
			$tpl->assign( DOWNLOAD , "<font color=#FFFFFF><b>Download</b></font>" );
			$tpl->assign( SHARE , "Share" );
			$tpl->assign( NOT_SHA , "Not Share" );
			$tpl->assign( DELETE , "Del" );
			$tpl->assign( CLEAR , "Clear" );
		}
		$tpl->parse ( COURSE_LIST, ".course_list" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $message, $PHPSESSID, $group_id, $content, $url;
		$Q1 = "select * from note_".$coopcaseid." where group_num='$coopgroup' and student_id = '".GetUserAID($user_id)."' order by a_id";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			if ( $version == "C" )
				$message = "<font size=5 color=#0000ff>目前的筆記列表</font>";
			else
				$message = "<font size=5 color=#0000ff>The Note List</font>";
			$i = 0;
			$color = "#BFCEBD";
			while ( $row = mysql_fetch_array( $result ) ) {
				if ( $color == "#BFCEBD" )
					$color = "#D0DFE3";
				else
					$color = "#BFCEBD";
				$tpl->assign( COLOR , $color );
				$i ++;
				$tpl->assign( CHECK , "<input type=checkbox name=". $row['a_id'] ." value=NO onBlur=\"selected=(selected||this.checked);\">" );
				if ( $row['share'] == 1 ) {
					if ( $version == "C" ) {
						$tpl->assign( STATUS , "分享" );
					}
					else {
						$tpl->assign( STATUS , "Share" );
					}
				}
				else {
					if ( $version == "C" ) {
						$tpl->assign( STATUS , "未分享" );
					}
					else {
						$tpl->assign( STATUS , "Not Share" );
					}
				}
				$tpl->assign( DATE , $row['mtime'] );
				$tpl->assign( SUBJECT , $row['subject'] );
				if ( $version == "C" ) {
					$tpl->assign( LINK , "<a href=\"./note.php?a_id=".$row['a_id']."&action=edit\" >編輯</a>" );
					$tpl->assign( DOWNLOAD , "<a href=\"./note.php?a_id=".$row['a_id']."&action=dump\" >下載</a>" );
				}
				else {
					$tpl->assign( LINK , "<a href=\"./note.php?a_id=".$row['a_id']."&action=edit\" >Edit</a>" );
					$tpl->assign( DOWNLOAD , "<a href=\"./note.php?a_id=".$row['a_id']."&action=dump\" >Download</a>" );
				
				}
				$tpl->parse ( COURSE_LIST, ".course_list" );
			}
		}
		else if ( $version == "C" )
			$message = "<font size=4 color=#00aa00><b>尚未提供任何筆記檔案</b></font><br><br>";
		else
			$message = "<font size=4 color=#00aa00><b>There is no Note File</b></font><br><br>";

		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}

	function share_note () {
		global $version, $check, $skinnum, $course_id, $coopgroup, $coopcaseid;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->define ( array ( body => "share_note.tpl" ) );

		$tpl->define_dynamic ( "course_list" , "body" );

		$color = "#4d6eb2";
		$tpl->assign( COLOR , $color );
		if ( $version == "C" ) {
			$tpl->assign( TITLE , "筆記本列表" );
			$tpl->assign( ADD , "");
			$tpl->assign( CHECK , "<font color=#FFFFFF><b>編號</b></font>" );
			$tpl->assign( DATE , "<font color=#FFFFFF><b>日期</b></font>" );
			$tpl->assign( SUBJECT , "<font color=#FFFFFF><b>標題</b></font>" );
			$tpl->assign( STATUS , "<font color=#FFFFFF><b>狀態</b></font>" );
			$tpl->assign( LINK , "<font color=#FFFFFF><b>檔案連結</b></font>" );
			$tpl->assign( DOWNLOAD , "<font color=#FFFFFF><b>檔案下載</b></font>" );
		}
		else {
			$tpl->assign( TITLE , "Note List" );
			$tpl->assign( ADD , "" );
			$tpl->assign( CHECK , "<font color=#FFFFFF><b>No.</b></font>" );
			$tpl->assign( DATE , "<font color=#FFFFFF><b>Date</b></font>" );
			$tpl->assign( SUBJECT , "<font color=#FFFFFF><b>Subject</b></font>" );
			$tpl->assign( STATUS , "<font color=#FFFFFF><b>Status</b></font>" );
			$tpl->assign( LINK , "<font color=#FFFFFF><b>File Link</b></font>" );
			$tpl->assign( DOWNLOAD , "<font color=#FFFFFF><b>Download</b></font>" );
		}
		$tpl->parse ( COURSE_LIST, ".course_list" );
		
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $message, $PHPSESSID;
		$Q1 = "select * from note_".$coopcaseid." where group_num='$coopgroup' and share='1' order by a_id DESC";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			if ( $version == "C" )
				$message = "<font size=5 color=#0000ff>目前的筆記列表</font>";
			else
				$message = "<font size=5 color=#0000ff>The Note List</font>";
			$i = 0;
			$color = "#BFCEBD";
			while ( $row = mysql_fetch_array( $result ) ) {
				if ( $color == "#BFCEBD" )
					$color = "#D0DFE3";
				else
					$color = "#BFCEBD";
				$tpl->assign( COLOR , $color );
				$i ++;
				$tpl->assign( CHECK , "$i" );
				$tpl->assign( STATUS , "分享" );
				$tpl->assign( DATE , $row['mtime'] );
				$tpl->assign( SUBJECT , $row['subject'] );
				if ( $version == "C" ) {
					$tpl->assign( LINK , "<a href=\"./note.php?a_id=".$row['a_id']."&action=show\" target=\"_blank\" >開啟</a>" );
					$tpl->assign( DOWNLOAD , "<a href=\"./note.php?a_id=".$row['a_id']."&action=dump\" >下載</a>" );
				}
				else {
					$tpl->assign( LINK , "<a href=\"./note.php?a_id=".$row['a_id']."&action=show\" target=\"_blank\" >View</a>" );
					$tpl->assign( DOWNLOAD , "<a href=\"./note.php?a_id=".$row['a_id']."&action=dump\" >Download</a>" );
				
				}
				$tpl->parse ( COURSE_LIST, ".course_list" );
			}
		}
		else if ( $version == "C" )
			$message = "<font size=4 color=#00aa00><b>尚未提供任何筆記檔案</b></font><br><br>";
		else
			$message = "<font size=4 color=#00aa00><b>There is no Note File</b></font><br><br>";

		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}

	function show_content () {
		global $version, $check, $skinnum, $course_id, $coopgroup, $coopcaseid, $a_id, $user_id;
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->assign( SKINNUM , $skinnum );
		$tpl->define ( array ( body => "show_note.tpl" ) );

		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $message, $PHPSESSID, $content;
		$Q1 = "select * from note_".$coopcaseid." where group_num='$coopgroup' and a_id='$a_id' and share='1'";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			$row = mysql_fetch_array( $result );
			$tpl->assign( SUBJECT , $row['subject'] );
			$content = $row['content'];
			$content = str_replace ( "\n", "<br>", $content );
			$tpl->assign( CONTENT , $content );
		}
		else if ( $version == "C" )
			$message = "<font size=4 color=#00aa00><b>沒有任何筆記檔案</b></font><br><br>";
		else
			$message = "<font size=4 color=#00aa00><b>There is no Note </b></font><br><br>";

		$tpl->assign( MES , $message );

		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
	
	function file_dump () {
		global $version, $check, $course_id, $coopgroup, $coopcaseid, $a_id;

		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		$Q1 = "select * from note_".$coopcaseid." where group_num='$coopgroup' and a_id='$a_id' and ( share='1' or student_id = '".GetUserAID($user_id)."' )";
		if ( !($sqllink = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$message = "$message - 資料庫連結錯誤!!";
		}
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$message = "$message - 資料庫讀取錯誤!!";
		}
		else if ( mysql_num_rows( $result ) != 0 ) {
			$row = mysql_fetch_array( $result );
			$content = $row['content'];
		}
		
		$ext = 'txt';
		$mime_type = (PMA_USR_BROWSER_AGENT == 'IE' || PMA_USR_BROWSER_AGENT == 'OPERA')
		? 'application/octetstream'
		: 'application/octet-stream';
		
		header('Content-Type: ' . $mime_type);
		// lem9 & loic1: IE need specific headers
		if (PMA_USR_BROWSER_AGENT == 'IE') {
			header('Content-Disposition: inline; filename="' . $a_id . '.' . $ext . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		} else {
			header('Content-Disposition: attachment; filename="' . $a_id . '.' . $ext . '"');
			header('Expires: 0');
			header('Pragma: no-cache');
		}
		echo $content;
		
	}
	
	function GetUserAID($user_id) {

		global $DB;

		$sql = "select a_id from user where id='$user_id'";
		$result = mysql_db_query($DB, $sql) or die("資料庫查詢錯誤, $sql");

		// check name field. if exists, use it as poster name.
		$row = mysql_fetch_array( $result );
		
		return $row['a_id'];
	}
?>
<?php
	require 'fadmin.php';
	if ( !(isset($PHPSESSID) && session_check_stu($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
		exit;
	}

	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "select id, name, skill, job, experience, introduction, email, php FROM user where a_id ='$user_aid'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "$message - 資料庫連結錯誤!!";
	}
	else if ( !($result = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "$message - 資料庫讀取錯誤!!";
		show_page ( "not_access.tpl", $message, "", "<a href=./guest.php>回上一頁</a>" );
	}
	else if( $row = mysql_fetch_array( $result ) ) {
		if ( $row['php'] != NULL )
			header( "Location: ".$row['php']);
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		$tpl->define ( array ( body => "TTDATAQuery1.tpl") );
		//$tpl->assign ( ID, $row['id'] );
		$tpl->assign ( NAME, $row['name'] );
		$tpl->assign ( SKILL, $row['skill'] );
		if ( is_file( "../../studentPage/".$row['id'].".gif" ) ) {
			$tpl->assign ( IMAGE, "<img src=\"../../studentPage/".$row['id'].".gif\" width=30%>" );
		}
		else {
			$tpl->assign ( IMAGE, "" );
		}
		$job = $row['job'];
		if($job == "00")
			$tpl->assign( JOB , "N/A" );
		else if($job == "01")
			$tpl->assign( JOB , "電子業" );
		else if($job == "02")
			$tpl->assign( JOB , "資訊業" );
		else if($job == "03")
			$tpl->assign( JOB , "服務業" );
		else if($job == "04")
			$tpl->assign( JOB , "自由業" );
		else if($job == "05")
			$tpl->assign( JOB , "傳播業" );
		else if($job == "06")
			$tpl->assign( JOB , "金融業" );
		else if($job == "07")
			$tpl->assign( JOB , "營建業" );
		else if($job == "08")
			$tpl->assign( JOB , "醫藥業" );
		else if($job == "09")
			$tpl->assign( JOB , "學術單位" );
		else if($job == "010")
			$tpl->assign( JOB , "政府單位" );
		else if($job == "011")
			$tpl->assign( JOB , "學生" );
		else if($job == "012")
			$tpl->assign( JOB , "其它" );
		else
			$tpl->assign( JOB , "" );

		$content = $row['introduction'];
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign ( INTRO, $content );
		$content = $row['experience'];
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign ( EXPER, $content );
		$tpl->assign ( EMAIL, $row['email'] );
		if ( $query == 1 )
			$tpl->assign ( RET, "<a href=# onClick=\"self.close();return false;\">關閉視窗</a>" );
		else
			$tpl->assign ( RET, "<a href=./guest.php>回前一頁</a>" );
		$tpl->parse( BODY, "body");
		$tpl->FastPrint("BODY");
	}
	else
		show_page ( "not_access.tpl", "沒有資料" , "", "<a href=./guest.php>回上一頁</a>" );

?>
<?php
	require 'fadmin.php';
	update_status ("閱讀公告內容");
	if ( !(isset($PHPSESSID) && session_check_teach($PHPSESSID) && check_group ( $course_id, $coopgroup, $coopcaseid ) != 0) ) {
		show_page( "not_access.tpl" ,"權限錯誤");
	}

	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	$tpl->define ( array ( body => "content.tpl") );
	$tpl->assign ( SKINNUM, $skinnum );
	$result;
	$Q1 = "select subject, content, begin_day FROM news_".$coopcaseid." where a_id ='$a_id' and group_num='".$coopgroup."'";
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "資料庫連結錯誤!!";
	}else {
		if ( !($result = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
			$error = "資料庫讀取錯誤!!";
		}
		add_log_coop ( 7, "", $a_id, $course_id, "", "", $coopgroup, $coopcaseid );
	}
	if ( mysql_num_rows( $result ) != 0 ) {
		$row = mysql_fetch_array( $result );
		$tpl->assign ( TITLE, $row['subject'] );
		$content = $row['content'];
		$content = str_replace ( "\n", "<BR>", $content );
		$tpl->assign ( NEWS,  $content );
		$tpl->assign ( DAY,  $row['begin_day'] );
		if ( $version == "C" )
			$error = "最新消息";
		else
			$error = "New News";
	}
	else 
		$error = "資料不存在!!!";

	$tpl->assign ( MES,  $error );
	if ( $version == "C" ) {
		$tpl->assign ( SUBJECT, "標題" );
		$tpl->assign ( DATE, "發佈日期" );
		$tpl->assign ( CONTENT, "內容" );
		$tpl->assign ( CLOSE, "關閉視窗" );
	}
	
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");

?>
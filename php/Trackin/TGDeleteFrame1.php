<?php
require 'fadmin.php';
update_status ("處理成績");

if(isset($PHPSESSID) && session_check_teach($PHPSESSID) == 2)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	if ( $action == "del" ) {
		for( $i = 0; $i < $total; $i++ )
		{
			$check = "check_".$i;
			$examid = $$check;
			if ( $examid != "" ) {
				$Q1 = "DELETE FROM take_exam WHERE exam_id='$examid'";
				if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
				}
				$Q2 = "DELETE FROM exam WHERE a_id='$examid'";
				if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
					show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
				}
			}
		}
		if($version == "C")
			$message = "刪除完成!";
		else
			$message = "Delete Completely!";
		show_page_d();
	}
	else
		show_page_d();

}
else
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}

function show_page_d () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $version, $message, $course_id, $skinnum;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT a_id, name, percentage FROM exam where is_online='0' order by name";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫寫入錯誤!!" );
	}

	if( ($num = mysql_num_rows($result1)) != 0 )
	{
		include("class.FastTemplate.php3");
		$tpl=new FastTemplate("./templates");
		if ( $version == "C" )
			$tpl->define(array(main=>"TGDeleteFrame1.tpl"));
		else
			$tpl->define(array(main=>"TGDeleteFrame1_E.tpl"));
		$tpl->define_dynamic("row","main");
		$tpl->assign( SKINNUM , $skinnum );
		$i = 0;
		$color = "#E6FFFC";
		while ( $rows = mysql_fetch_row( $result1 ) ) {
			if ( $color == "#E6FFFC" )
				$color = "#F0FFEE";
			else
				$color = "#E6FFFC";
			$tpl->assign( COLOR , $color );
			$tpl->assign(NUM,$i);
			$tpl->assign(TESTAID,$rows[0]);
			$tpl->assign(TESTNAME,$rows[1]);
			$tpl->assign(TESTRATIO,$rows[2]);
			$tpl->parse(ROWS,".row");
			$i ++;
		}
		$tpl->assign(TOTAL,$num);
		$tpl->assign(ACT,"del");
		$tpl->assign(MESSAGE,$message);
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else
	{
		if( $version=="C" ) {
			show_page( "not_access.tpl" ,"目前沒有考試項目!");
			exit;
		}
		else {
			show_page( "not_access.tpl" ,"No Test Record!");
			exit;
		}
	}
}
?>
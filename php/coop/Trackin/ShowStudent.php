<?php
require 'fadmin.php';
update_status ("管理專案");

if( !(isset($PHPSESSID) && (session_check_teach($PHPSESSID) >= 2 || check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2)) )
{
	if( $version=="C" )
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
	else
		show_page( "not_access.tpl" ,"You have No Permission!!");
}

	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $coopcaseid, $version, $course_id, $message, $skinnum, $coopgroup;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		show_page( "not_access.tpl" ,"資料庫連結錯誤!!" );
	}
	$Q1 = "SELECT * from coop_".$coopcaseid."_group where group_num = '$coopgroup'";
	if ( !($result1 = mysql_db_query( $DBC.$course_id, $Q1 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!" );
	}
	if ( mysql_num_rows($result1) != 0 ) {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate("./templates");
		if($version == "C")
			$tpl->define(array(main=>"ShowStudent.tpl"));
		else
			$tpl->define(array(main=>"ShowStudent_E.tpl"));
		$tpl->define_dynamic("grade","main");
		$tpl->assign( SKINNUM , $skinnum );
		$color == "#F0FFEE";
		while ( $rows = mysql_fetch_array($result1) )
		{
			if ( $color == "#F0FFEE" )
				$color = "#E6FFFC";
			else
				$color = "#F0FFEE";
			$tpl->assign( COLOR , $color );
			$Q2 = "SELECT * from user where id = '".$rows['student_id']."'";
			($result2 = mysql_db_query( $DB, $Q2 ) ) or die ("資料庫讀取錯誤!!");
			($rows2 = mysql_fetch_array ( $result2 )) or die ("資料庫讀取錯誤!!");
			
			$tpl->assign(GID,$rows['group_num']);
			$tpl->assign(SNO,$rows2['id']);
			$tpl->assign(SNN,$rows2['name']);

			$tpl->assign( AID, $rows2['a_id'] );
			$tpl->assign( CASEID, $coopcaseid );
			$tpl->parse(GRADE,".grade");
		}
		$tpl->parse(BODY,"main");
		$tpl->FastPrint("BODY");
	}
	else {
		if( $version=="C" )
			show_page( "not_access.tpl" ,"此課程尚未有任何學生!!");
		else
			show_page( "not_access.tpl" ,"There is no student in this class!!");
	}
?>
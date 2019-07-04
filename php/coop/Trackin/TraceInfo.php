<?php
require 'fadmin.php';
update_status ("個人使用紀錄");
if ( $case_id != NULL && $teacher == 1) {
	$coopcaseid = $case_id;
}
if ( $group_id != NULL && $teacher == 1) {
	$coopgroup = $group_id;
}
if(!(isset($PHPSESSID) && ($check = session_check_teach($PHPSESSID)) && check_group ( $course_id, $coopgroup, $coopcaseid ) >= 2) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}
global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "資料庫連結錯誤!!" );
	return;
}

if($check == 2)
{
	if($aid != NULL)
		$a_id = $aid;
  	else {
    		show_page( "not_access.tpl" ,"學號錯誤");
		exit;
	}
}
else
{
	$Q = "select id from user where a_id = '$aid'";
	if ( !($resultOBJ = mysql_db_query( $DB, $Q ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!1" );
		exit;
	}
	if ( !($row = mysql_fetch_array ( $resultOBJ )) ) {
		show_page( "not_access.tpl" ,"使用者資料錯誤1!!$Q" );
		exit;
	}
	
	$Q0 = "Select group_num From coop_".$coopcaseid."_group Where student_id='".$row['id']."'";
	if ( !($resultOBJ0 = mysql_db_query( $DBC.$course_id, $Q0 ) ) ) {
		show_page( "not_access.tpl" ,"資料庫讀取錯誤!!1" );
		exit;
	}
	if ( !($row0 = mysql_fetch_array ( $resultOBJ0 )) ) {
		show_page( "not_access.tpl" ,"使用者資料錯誤1!!$Q0" );
		exit;
	}
	
	if($row0['group_num'] != $coopgroup )
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
	$a_id = $aid;
	$case_id = $coopcaseid;
	$group_id = $coopgroup;
}

$Q0 = "Select name, id From user Where a_id = '$a_id'";
$Q1 = "Select tag3, mtime From log_".$case_id." Where user_id = '$a_id' AND event_id = '1' and group_num = '$group_id'";
$Q2 = "Select tag3, mtime From log_".$case_id." Where user_id = '$a_id' AND event_id = '2' and group_num = '$group_id'";
$Q3 = "Select tag3, mtime From log_".$case_id." Where user_id = '$a_id' AND event_id = '3' and group_num = '$group_id'";
$Q4 = "Select tag3, mtime From log_".$case_id." Where user_id = '$a_id' AND event_id = '4' and group_num = '$group_id'";
$Q5 = "Select tag3, mtime From log_".$case_id." Where user_id = '$a_id' AND event_id = '5' and group_num = '$group_id'";
$Q6 = "Select tag3, mtime From log_".$case_id." Where user_id = '$a_id' AND event_id = '6' and group_num = '$group_id'";
$Q8 = "Select tag3, mtime From log_".$case_id." Where user_id = '$a_id' AND event_id = '8' and group_num = '$group_id'";
$Q9 = "Select tag3, mtime From log_".$case_id." Where user_id = '$a_id' AND event_id = '9' and group_num = '$group_id'";

$Q10 = "Select tag3 From log_".$case_id." Where user_id = '$a_id' AND event_id = '10' and group_num = '$group_id'";
if ( !($resultOBJ0 = mysql_db_query( $DB, $Q0 )) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤0!!" );
	exit;
}
if ( !($row0 = mysql_fetch_array ( $resultOBJ0 )) ) {
	show_page( "not_access.tpl" ,"使用者資料錯誤02!!" );
	exit;
}

if ( !($resultOBJ1 = mysql_db_query( $DBC.$course_id, $Q1)) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤2!!" );
	exit;
}
$row1 = mysql_fetch_array ( $resultOBJ1 );

if ( !($resultOBJ2 = mysql_db_query( $DBC.$course_id, $Q2)) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤2!!" );
	exit;
}
$row2 = mysql_fetch_array ( $resultOBJ2 );

if ( !($resultOBJ3 = mysql_db_query( $DBC.$course_id, $Q3)) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤3!!" );
	exit;
}
$row3 = mysql_fetch_array ( $resultOBJ3 );

if ( !($resultOBJ4 = mysql_db_query( $DBC.$course_id, $Q4 )) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤4$Q4!!" );
	exit;
}
$row4 = mysql_fetch_array ( $resultOBJ4 );

if ( !($resultOBJ5 = mysql_db_query( $DBC.$course_id, $Q5 )) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤5!!" );
	exit;
}
$row5 = mysql_fetch_array ( $resultOBJ5 );

if ( !($resultOBJ6 = mysql_db_query( $DBC.$course_id, $Q6 )) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤6!!" );
	exit;
}
$row6 = mysql_fetch_array ( $resultOBJ6 );

if ( !($resultOBJ8 = mysql_db_query( $DBC.$course_id, $Q8 )) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤8!!" );
	exit;
}
$row8 = mysql_fetch_array ( $resultOBJ8 );

if ( !($resultOBJ9 = mysql_db_query( $DBC.$course_id, $Q9 )) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤9!!" );
	exit;
}
$row9 = mysql_fetch_array ( $resultOBJ9 );

if ( !($resultOBJ10 = mysql_db_query( $DBC.$course_id, $Q10 )) ) {
	show_page( "not_access.tpl" ,"資料庫讀取錯誤10!!" );
	exit;
}
$row10 = mysql_fetch_array ( $resultOBJ10 );

if( mysql_num_rows( $resultOBJ0 ) != 0 )
{
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version=="C")
		$tpl->define(array(student_info => "TraceInfo.tpl"));
	else
		$tpl->define(array(student_info => "TraceInfo_E.tpl"));
	$tpl->define_dynamic("row", "student_info");
	$tpl->assign(STUDENT_NAME, $row0['name']);
	$tpl->assign(STUDENT_ID, $row0['id']);
}
else {
	show_page( "not_access.tpl" ,"使用者不存在!!" );
	exit;
}

if( mysql_num_rows( $resultOBJ1 ) != 0 ) {
	$tpl->assign(LOGIN_TIMES, $row1['tag3']);
	$tempDate=array(substr($row1['mtime'],0,4),substr($row1['mtime'],4,2),substr($row1['mtime'],6,2));
	$tempTime=array(substr($row1['mtime'],8,2),substr($row1['mtime'],10,2),substr($row1['mtime'],12,2));
	$date=implode("-",$tempDate)." ".implode(":",$tempTime);
	$tpl->assign(LAST_LOGIN, $date);
}
else {
	$tpl->assign(LOGIN_TIMES, "0");
	if($version=="C")
		$tpl->assign(LAST_LOGIN, "尚未登入過");
	else
		$tpl->assign(LAST_LOGIN, "Never Login");
}

if( mysql_num_rows( $resultOBJ2 ) != 0 ) {
	$tpl->assign(VG_TIMES, $row2['tag3']);
	$tempDate=array(substr($row2['mtime'],0,4),substr($row2['mtime'],4,2),substr($row2['mtime'],6,2));
	$tempTime=array(substr($row2['mtime'],8,2),substr($row2['mtime'],10,2),substr($row2['mtime'],12,2));
	$date=implode("-",$tempDate)." ".implode(":",$tempTime);
	$tpl->assign(LAST_VG, $date);
}
else {
	$tpl->assign(VG_TIMES, "0");
	if($version=="C")
		$tpl->assign(LAST_VG, "尚未觀看過");
	else
		$tpl->assign(LAST_VG, "Never view");
}

if( mysql_num_rows( $resultOBJ3 ) != 0 ) {
	$tpl->assign(GUEST_TIMES, $row3['tag3']);
	$tempDate=array(substr($row3['mtime'],0,4),substr($row3['mtime'],4,2),substr($row3['mtime'],6,2));
	$tempTime=array(substr($row3['mtime'],8,2),substr($row3['mtime'],10,2),substr($row3['mtime'],12,2));
	$date=implode("-",$tempDate)." ".implode(":",$tempTime);
	$tpl->assign(LAST_GUEST, $date);
}
else {
	$tpl->assign(GUEST_TIMES, "0");
	if($version=="C")
		$tpl->assign(LAST_GUEST, "尚未留言過");
	else
		$tpl->assign(LAST_GUEST, "Never post");
}

if( mysql_num_rows( $resultOBJ4 ) != 0 ) {
	$tpl->assign(CHAT_TIMES, $row4['tag3']);
}
else {
	$tpl->assign(CHAT_TIMES, "0");
}

if( mysql_num_rows( $resultOBJ5 ) != 0 ) {
	$tpl->assign(VIEW_TIMES, $row5['tag3']);
	$tempDate=array(substr($row5['mtime'],0,4),substr($row5['mtime'],4,2),substr($row5['mtime'],6,2));
	$tempTime=array(substr($row5['mtime'],8,2),substr($row5['mtime'],10,2),substr($row5['mtime'],12,2));
	$date=implode("-",$tempDate)." ".implode(":",$tempTime);
	$tpl->assign(LAST_VIEW, $date);
}
else {
	$tpl->assign(VIEW_TIMES, "0");
	if($version=="C")
		$tpl->assign(LAST_VIEW, "尚未瀏覽過");
	else
		$tpl->assign(LAST_VIEW, "Never view");
}

if( mysql_num_rows( $resultOBJ6 ) != 0 ) {
	$tpl->assign(POST_TIMES, $row6['tag3']);
	$tempDate=array(substr($row6['mtime'],0,4),substr($row6['mtime'],4,2),substr($row6['mtime'],6,2));
	$tempTime=array(substr($row6['mtime'],8,2),substr($row6['mtime'],10,2),substr($row6['mtime'],12,2));
	$date=implode("-",$tempDate)." ".implode(":",$tempTime);
	$tpl->assign(LAST_POST, $date);
}
else {
	$tpl->assign(POST_TIMES, "0");
	if($version=="C")
		$tpl->assign(LAST_POST, "尚未發表過");
	else
		$tpl->assign(LAST_POST, "Never post");
}

if( mysql_num_rows( $resultOBJ8 ) != 0 ) {
	$tpl->assign(SHARE, $row8['tag3']);
}
else {
	$tpl->assign(SHARE, "0");
}

if( mysql_num_rows( $resultOBJ9 ) != 0 ) {
	$tpl->assign(NOTE, $row9['tag3']);
}
else {
	$tpl->assign(NOTE, "0");
}

if( mysql_num_rows( $resultOBJ10 ) != 0 )
	$tpl->assign(STAY_TIME, (int)($row10['tag3']/60) ." : ". $row10['tag3']%60);
else
	$tpl->assign(STAY_TIME, "0 : 0");

$tpl->assign(SKINNUM, $skinnum);
$tpl->parse(BODY, "student_info");
$tpl->FastPrint("BODY");
?>

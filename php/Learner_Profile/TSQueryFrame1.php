<?php
/**************************/
/*檔名:TSQueryFrame1.php*/
/*說明:教師觀看學生基本資料*/
/*相關檔案:*/
/*************************/
require 'fadmin.php';
update_status ("學生查詢");
include("Generate_studinfo.php");

if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}
if($check != 2)
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

global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
$Q1 = "Select course_no From course Where a_id = '$course_id'";
$Q2 = "Select authorization From user Where id = '$user_id'";
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo ( "資料庫連結錯誤!!" );
	return;
}
if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) ) {
	echo ( "資料庫讀取錯誤!!" );
	return;
}
$row = mysql_fetch_array ( $resultOBJ );
if ( !($resultOBJ2 = mysql_db_query( $DB, $Q2 ) ) ) {
	echo ( "資料庫讀取錯誤!!" );
	return;
}
$row2 = mysql_fetch_array ( $resultOBJ2 );

if($version=="C") {
	if ( $row2['authorization'] < 2 ) {
		if ( $nocredit != 1 )
			$file_name="../../$course_id/student_info/t_".$row['course_no'].".bin";
		else
			$file_name="../../$course_id/student_info/t_".$row['course_no']."nocredit.bin";
	}
	else {
		if ( $nocredit != 1 )
			$file_name="../../$course_id/student_info/ta_".$row['course_no'].".bin";
		else
			$file_name="../../$course_id/student_info/ta_".$row['course_no']."nocredit.bin";
	}
}
else {
	if ( $row2['authorization'] < 2 ) {
		if ( $nocredit != 1 )
			$file_name="../../$course_id/student_info/t_".$row['course_no']."_E.bin";
		else
			$file_name="../../$course_id/student_info/t_".$row['course_no']."nocredit_E.bin";
	}
	else {
		if ( $nocredit != 1 )
			$file_name="../../$course_id/student_info/ta_".$row['course_no']."_E.bin";
		else
			$file_name="../../$course_id/student_info/ta_".$row['course_no']."nocredit_E.bin";
	}
}
if(file_exists($file_name)) {
	$file=fopen("$file_name","r");
	$contents = fread ($file, filesize ($file_name));
	echo( $contents );
	fclose ( $file );
//	header("Location:$file_name");
}
else {
	if( $version=="C" )
		show_page( "not_access.tpl" ,"目前無任何學生資料");
	else
		show_page( "not_access.tpl" ,"No data now!!");
}
?>

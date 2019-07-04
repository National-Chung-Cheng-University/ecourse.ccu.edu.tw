<?php
if($_SERVER["REMOTE_ADDR"] != '140.123.29.235' && $_SERVER["REMOTE_ADDR"] != '140.123.4.53')
	die();
	
require 'fadmin.php';
global $DB_SERVER, $DB_LOGIN, $DB_PASSWORD, $DB;
if (!($link = mysql_pconnect($DB_SERVER, $DB_LOGIN, $DB_PASSWORD)))
    die("資料庫連結錯誤");

//取得本學期資料
$sql = "SELECT year, term FROM this_semester";
if ($result = mysql_db_query($DB, $sql))
{
	if(($row = mysql_fetch_array($result))==0)
		die("本學期資料不存在");	
}
else
	die("資料庫讀取錯誤$sql");
	
$year = $row['year'];
$term = $row['term'];	
//echo "This semester is:".$year."學年第".$term."學期<br>";
//--------------------------------------------------------------
  
  
//取得某門課程資訊(根據課程編號)
$course_no = $_GET['course_no'];
$sql = "SELECT c.a_id, c.name 
		FROM course AS c, teach_course AS tc 
		WHERE c.course_no = '$course_no' 
		AND c.a_id = tc.course_id 
		AND tc.year = '$year' 
		AND tc.term = '$term' 
		";
if ($result = mysql_db_query($DB, $sql))
{
	if(($row = mysql_fetch_array($result))==0)
		die("課程資料不存在");	
}
else
	die("資料庫讀取錯誤$sql");

$course_id = $row['a_id'];
$course_name = $row['name'];
//echo "course_id: " .$course_id. ", course_no: ".$course_no.", course_name: ".$course_name."<br>";
//--------------------------------------------------------------

//取得某門課程在本學期修課名單(根據課程編號)
$sql = "SELECT u.id, u.name 
		FROM take_course AS tc, user AS u 
		WHERE tc.course_id = '$course_id' 
		AND tc.year = '$year' 
		AND tc.term = '$term' 
		AND tc.validated = '1' 
		AND tc.credit = '1' 
		AND tc.student_id = u.a_id 
		ORDER by u.id
		";
if ($result = mysql_db_query($DB, $sql))
{
	if((mysql_num_rows($result))==0)
		die("修課清單不存在");	
}
else
	die("資料庫讀取錯誤$sql");


while($row = mysql_fetch_assoc($result))
{
	$student_no = $row['id'];
	$student_name = $row['name'];
	echo $student_name.",".$student_no."<br>";
}
//--------------------------------------------------------------	
?>

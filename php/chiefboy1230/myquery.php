<?php
require 'common.php';
function countNum($role, $year, $term)
{
if($role == 1)	//教師
	$Q="select SUM(tag3) from teach_course as tc, log as l where tc.year = '$year' and tc.term = '$term' and l.event_id='1' and tc.teacher_id = l.user_id";
elseif($role == 3)	//學生
	$Q="select SUM(tag3) from take_course as tc, log as l where tc.year = '$year' and tc.term = '$term' and l.event_id='1' and tc.student_id = l.user_id";
else
	echo "參數錯誤；請洽管理員";

$result=mysql_db_query("study",$Q);
$my_count = 0;

if( mysql_num_rows($result)){
	$temp=mysql_fetch_array($result);
	$my_count = $temp['SUM(tag3)'];
	
}

if ($role == 1)
	echo "$year" . "學年度第" . $term . "學期教師累計登入人數：".$my_count."<br>";
elseif($role == 3)
	echo "$year" . "學年度第" . $term . "學期學生累計登入人數：".$my_count."<br>";
else
	echo "未預期輸出";

}


$link=mysql_pconnect("$DB_SERVER","$DB_LOGIN","$DB_PASSWORD");

$Q="select SUM(tag3) from user,log where authorization='1' and event_id='1' and user.a_id=log.user_id order by user.id ";

$result=mysql_db_query("study",$Q);
$my_count = 0;
if( mysql_num_rows($result)){
	$temp=mysql_fetch_array($result);
	$my_count=$temp['SUM(tag3)'];
	
}
echo "目前教師累計登入人數：".$my_count."<br>";

countNum('1', '97', '1');
countNum('1', '97', '2');
countNum('1', '98', '1');
countNum('1', '98', '2');
countNum('1', '99', '1');
countNum('1', '99', '2');
countNum('1', '100', '1');

$Q="select SUM(tag3) from user,log where authorization='3' and event_id='1' and user.a_id=log.user_id order by user.id ";

$result=mysql_db_query("study",$Q);
$my_count = 0;
if( mysql_num_rows($result)){
	$temp=mysql_fetch_array($result);
	$my_count = $temp['SUM(tag3)'];
	
}
echo "目前學生累計登入人數：".$my_count."<br>";

countNum('3', '97', '1');
countNum('3', '97', '2');
countNum('3', '98', '1');
countNum('3', '98', '2');
countNum('3', '99', '1');
countNum('3', '99', '2');
countNum('3', '100', '1');
?>

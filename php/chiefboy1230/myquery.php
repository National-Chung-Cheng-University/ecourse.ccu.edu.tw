<?php
require 'common.php';
function countNum($role, $year, $term)
{
if($role == 1)	//�Юv
	$Q="select SUM(tag3) from teach_course as tc, log as l where tc.year = '$year' and tc.term = '$term' and l.event_id='1' and tc.teacher_id = l.user_id";
elseif($role == 3)	//�ǥ�
	$Q="select SUM(tag3) from take_course as tc, log as l where tc.year = '$year' and tc.term = '$term' and l.event_id='1' and tc.student_id = l.user_id";
else
	echo "�Ѽƿ��~�F�Ь��޲z��";

$result=mysql_db_query("study",$Q);
$my_count = 0;

if( mysql_num_rows($result)){
	$temp=mysql_fetch_array($result);
	$my_count = $temp['SUM(tag3)'];
	
}

if ($role == 1)
	echo "$year" . "�Ǧ~�ײ�" . $term . "�Ǵ��Юv�֭p�n�J�H�ơG".$my_count."<br>";
elseif($role == 3)
	echo "$year" . "�Ǧ~�ײ�" . $term . "�Ǵ��ǥͲ֭p�n�J�H�ơG".$my_count."<br>";
else
	echo "���w����X";

}


$link=mysql_pconnect("$DB_SERVER","$DB_LOGIN","$DB_PASSWORD");

$Q="select SUM(tag3) from user,log where authorization='1' and event_id='1' and user.a_id=log.user_id order by user.id ";

$result=mysql_db_query("study",$Q);
$my_count = 0;
if( mysql_num_rows($result)){
	$temp=mysql_fetch_array($result);
	$my_count=$temp['SUM(tag3)'];
	
}
echo "�ثe�Юv�֭p�n�J�H�ơG".$my_count."<br>";

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
echo "�ثe�ǥͲ֭p�n�J�H�ơG".$my_count."<br>";

countNum('3', '97', '1');
countNum('3', '97', '2');
countNum('3', '98', '1');
countNum('3', '98', '2');
countNum('3', '99', '1');
countNum('3', '99', '2');
countNum('3', '100', '1');
?>

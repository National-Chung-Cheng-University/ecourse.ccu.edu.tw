<?php
/*
	author: rja
	�o��{���O���Ӹ� mmc �P�B�P�@���ҵ{���H����T�Ϊ�
	���N�O�b���U"�H����T"�ɡA�h mmc �P�B���v��(�H����T)
*/
require_once 'common.php';
require_once 'fadmin.php';
#require_once 'passwd_encryption.php';
require_once 'my_rja_db_lib.php';

//error_reporting(256);

global $course_id;
global $user_id;
global $DB;
//�O��쥻�� originDB�A�{�������A�_��
$originDB = $DB;
#global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

$Q1= "select name from course where a_id = $course_id";

$DB='study';
$courseName  = query_db_to_value($Q1);
$courseName  = urlencode($courseName);

$synUrl = "http://mmc.elearning.ccu.edu.tw/my_get_on_line_video_syn.php?courseId=$course_id&courseName=$courseName";
$page = file_get_contents($synUrl);

//�o�̪� DB �n�令�ثe�ҵ{�� DB 
$DB = 'study'.$course_id;

if(preg_match('/^\d+\|@a/', $page) ){

	$Q2 = "delete from on_line where link like 'http://mmc.elearning.ccu.edu.tw%' ";
	query_db($Q2);
	$allVodeoLink = explode("\n", $page);

	foreach ($allVodeoLink as $key => $value ){
		if (empty($value)) break;

		list($pubRecordingId, $teacherName, $courseName, $courseId, $subject, $date ) = explode('|@a' , $value);
		$subject = urldecode($subject);
		$courseName = urldecode($courseName);



		$pubUrl = "http://mmc.elearning.ccu.edu.tw/pub_recording_view.php?id=$pubRecordingId";
		$query = "INSERT INTO on_line ( a_id , date , subject , link ,  mtime )
			VALUES ( '','$date', '$subject', '$pubUrl', NOW( )); ";

		query_db($query);
	}

}elseif (preg_match('/nothingnew/', $page)){

        $Q2 = "delete from on_line where link like 'http://mmc.elearning.ccu.edu.tw%' ";
        query_db($Q2);
}

$DB = $originDB ;

?>


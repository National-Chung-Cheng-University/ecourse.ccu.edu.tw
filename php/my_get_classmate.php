<?php
/*
   author: rja
   這支程式是拿來給 mmc 查 某課程的全班同學的 姓名及email 用的，包括老師會放在第一個
   傳入參數是老師姓名及課程名稱
   先用老師姓名及課程名稱查 course_id
   有了 course_id 再查全班同學，這一步會較容易


 */
require_once 'common.php';
require_once 'fadmin.php';
#require_once 'passwd_encryption.php';
require_once 'my_rja_db_lib.php';

#error_reporting(256);

$query_this_semeter = "SELECT * FROM this_semester";
$this_semeter = flatArray(query_db_to_array($query_this_semeter));
$this_year = $this_semeter[0];
$this_term = $this_semeter[1];

$teacherName = $_REQUEST['teacherName'];
$courseName = $_REQUEST['courseName'];
$Q1 = "select course.a_id as courseId, user.name as name, user.email as email from course, teach_course tc, user 
where course.a_id = tc.course_id and user.a_id = tc.teacher_id and 
course.name = '$courseName'  and user.name = '$teacherName'  and tc.year = $this_year and tc.term = $this_term";

//print $Q1;

$teacherInfo = query_db_to_array($Q1);
//var_dump ( $teacherInfo);
//老師的資訊 (以後會有助教)
if(!empty($teacherInfo)){
	foreach ($teacherInfo as $value){
		print urlencode($value['name']) . '|' . ($value['email']) . "\n"; 
	}
}else {
//沒有老師資訊，可見不在 ecourse 上，可能要去cyber上找
print '';
exit;

}

$courseId = ( $teacherInfo[0]['courseId']);

$Q1 = "select user.name as name, user.email as email from course, take_course tc, user 
where course.a_id = tc.course_id and user.a_id = tc.student_id
and course.a_id = $courseId and tc.year = $this_year and tc.term = $this_term";

//print $Q1;

$studentInfo = query_db_to_array($Q1);
//學生的資訊 
if(!empty($studentInfo)){
	foreach ($studentInfo as $value){
		print urlencode($value['name']) . '|' . ($value['email']) . "\n"; 
	}
}
//var_dump ( $teacherInfo);
?>

<?php
/*
   author: rja
   �o��{���O���ӵ� mmc �d �Y�ҵ{�����Z�P�Ǫ� �m�W��email �Ϊ��A�]�A�Ѯv�|��b�Ĥ@��
   �ǤJ�ѼƬO�Ѯv�m�W�νҵ{�W��
   ���ΦѮv�m�W�νҵ{�W�٬d course_id
   ���F course_id �A�d���Z�P�ǡA�o�@�B�|���e��


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
//�Ѯv����T (�H��|���U��)
if(!empty($teacherInfo)){
	foreach ($teacherInfo as $value){
		print urlencode($value['name']) . '|' . ($value['email']) . "\n"; 
	}
}else {
//�S���Ѯv��T�A�i�����b ecourse �W�A�i��n�hcyber�W��
print '';
exit;

}

$courseId = ( $teacherInfo[0]['courseId']);

$Q1 = "select user.name as name, user.email as email from course, take_course tc, user 
where course.a_id = tc.course_id and user.a_id = tc.student_id
and course.a_id = $courseId and tc.year = $this_year and tc.term = $this_term";

//print $Q1;

$studentInfo = query_db_to_array($Q1);
//�ǥͪ���T 
if(!empty($studentInfo)){
	foreach ($studentInfo as $value){
		print urlencode($value['name']) . '|' . ($value['email']) . "\n"; 
	}
}
//var_dump ( $teacherInfo);
?>

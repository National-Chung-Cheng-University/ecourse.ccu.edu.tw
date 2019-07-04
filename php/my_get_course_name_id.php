<?php
require_once 'common.php';
require_once 'fadmin.php';
require_once 'my_rja_db_lib.php';


global $course_id;
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $link;

//query teacher id 



//$name= iconv( "utf-8", "big5",$_GET['name']);
$name= $_GET['name'];



//$name='黃仁竑';

//$name='蔡昌富';
$Q1 = "select  distinct course.name as name,  course.a_id as id  from user,teach_course as tc,course, this_semester as s where user.name ='$name' and user.a_id= tc.teacher_id and tc.course_id= course.a_id and tc.year = s.year and tc.term = s.term ";
//print $Q1;


//暫存 cyberccu 上可以加上 year , term 
//select teach_course.year as year, teach_course.term as term, course.name as name from user,teach_course,course where user.name ='黃仁竑' and user.a_id= teach_course.teacher_id and teach_course.course_id= course.a_id

$courseNameAndId=query_db_to_array($Q1);
//print_r($courseNameAndId);

$ret = ''; 
if(!empty($courseNameAndId) ) {
	foreach ($courseNameAndId as $value){
		$ret .= $value['name'] .'|@a';
	}

	$ret = ereg_replace('\|@a$', '', $ret);
	$ret .= "\n";

	foreach ($courseNameAndId as $value){
		$ret .= $value['id'] .'|@a';
	}
}

echo (ereg_replace('\|@a$', '', $ret));



?>

<?php
require_once 'common.php';
require_once 'fadmin.php';


global $course_id;
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $link;


//echo "iamserver4";
//print_r($_GET);
if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) 
	die( "資料庫連結錯誤!!" );

//$teacherName = iconv( "utf-8", "big5",$_GET['teacherName']);
//$courseName  = iconv( "utf-8", "big5",$_GET['courseName']);

//$teacherName='黃仁竑';
//$courseName = '電腦網路';
//$Q1 = "select * from user,teach_course,course_no where name = '$name' ";
$Q1 = "select  course.a_id as course_id from user,teach_course as tc ,course, this_semester as s  where user.name ='$teacherName'and course.name='$courseName' and user.a_id= teach_course.teacher_id and teach_course.course_id= course.a_id and tc.year = s.year and tc.term = s.term
";

//暫存 cyberccu 上可以加上 year , term 
//select teach_course.year as year, teach_course.term as term, course.name as name from user,teach_course,course where user.name ='黃仁竑' and user.a_id= teach_course.teacher_id and teach_course.course_id= course.a_id

$courseId=query_db_to_array($Q1);

if(! empty($courseId[0][0]))
echo($courseId[0][0]);
else echo 'Error: courseId empty.';

exit;

//下面應該就沒有用了



foreach ($courseName as $value){
$ret .= $value[0] .'|';
}
echo (ereg_replace('\|$', '', $ret));
function query_db($query, $column) {

	global $DB;
	if ( $result1 = mysql_db_query( $DB, $query) ) {

		if ( mysql_num_rows( $result1 ) != 0 ) {
			$row_result= mysql_fetch_array( $result1 );
			if ( is_null($row_result["$column"]))echo "server4: some thing wrong. Query result is null?!";

			return  $row_result["$column"];
		}
		else echo "some thing wrong. Query result is null?";
	}
	else echo "some thing wrong. Can't Query?";
}


function query_db_to_array($query) {

	global $DB;
	if ( $queryObj = mysql_db_query( $DB, $query) ) {

		while ($row_result = mysql_fetch_row ($queryObj)) 
			if (! is_null($row_result) ) $return_result[]=$row_result;


		if ( is_null($row_result)) echo "mmc: some thing wrong. Query result is null?!";
		return  $return_result;
	}
	else echo "mmc: some thing wrong. Can't Query?";
	echo mysql_errno().": ".mysql_error()."<BR>";
}


?>

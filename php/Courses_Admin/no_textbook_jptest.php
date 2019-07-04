<?php

// 查看已上傳及未上傳課程教材之系所

require 'fadmin.php';
global $DB, $version,$skinnum;
$count = 0;			//總課程數
$has_texbook = 0;		//有大綱的課程數
$no_textbook = 0;		//無大綱的課程數

//選出該系所以下的所有課程
//$Q3 = "select distinct course.a_id course_id, course.name cname, course.course_no from course, teach_course where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."' order by course.group_id, course.course_no";
$Q3 = "select course.a_id course_id, course.name cname, course.course_no from course where course_no='3152702_01'";
echo $Q3."<br>";
echo $DB;
if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) )
{
	echo "$message - 資料庫讀取錯誤3!!";
}
echo "OK";

//該系所　所有的課程總數
$dept_total=mysql_num_rows($result3);
echo $dept_total;

//看有幾門課是已上傳
$self_count=0;
while($row3 = mysql_fetch_array($result3))
{
	$count++;
	
	//若目錄下除了misc外沒有其他檔案 就是沒上傳
	$dir = "../../$row3[course_id]/textbook/";
	echo "dir:".$dir."!";
	$handle = opendir($dir);
	$own_text = 0; //判斷是否無其他檔案
	while (false !==($file = readdir($handle))) {
	   echo "@".$file."@";
		if($file!="misc" && $file!="." && $file!=".."){
			$own_text = 1;
			//echo $file;
			break;
		}
	}
	echo "own_text:".$own_text."!";
}
	
?>
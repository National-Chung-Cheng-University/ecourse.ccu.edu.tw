<?php

// �d�ݤw�W�ǤΥ��W�ǽҵ{�Ч����t��

require 'fadmin.php';
global $DB, $version,$skinnum;
$count = 0;			//�`�ҵ{��
$has_texbook = 0;		//���j�����ҵ{��
$no_textbook = 0;		//�L�j�����ҵ{��

//��X�Өt�ҥH�U���Ҧ��ҵ{
//$Q3 = "select distinct course.a_id course_id, course.name cname, course.course_no from course, teach_course where teach_course.year='".$row0[year]."' and teach_course.term='".$row0[term]."' and teach_course.course_id=course.a_id and course.group_id='".$row2[a_id]."' order by course.group_id, course.course_no";
$Q3 = "select course.a_id course_id, course.name cname, course.course_no from course where course_no='3152702_01'";
echo $Q3."<br>";
echo $DB;
if ( !($result3 = mysql_db_query( $DB, $Q3 ) ) )
{
	echo "$message - ��ƮwŪ�����~3!!";
}
echo "OK";

//�Өt�ҡ@�Ҧ����ҵ{�`��
$dept_total=mysql_num_rows($result3);
echo $dept_total;

//�ݦ��X���ҬO�w�W��
$self_count=0;
while($row3 = mysql_fetch_array($result3))
{
	$count++;
	
	//�Y�ؿ��U���Fmisc�~�S����L�ɮ� �N�O�S�W��
	$dir = "../../$row3[course_id]/textbook/";
	echo "dir:".$dir."!";
	$handle = opendir($dir);
	$own_text = 0; //�P�_�O�_�L��L�ɮ�
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
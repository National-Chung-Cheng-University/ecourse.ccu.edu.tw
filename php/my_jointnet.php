<?php
/*
	author: rja
	�o��{���O���Ӷ} mmc �Y�ɷ|ĳ�Ϊ��A�����D�٦��S����
*/
require_once 'common.php';
require_once 'fadmin.php';

//�o��{�����ӬO�ǥͶi�J�����줽�� mmc �ɥΪ�

global $course_id;
global $user_id;
global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;


if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
	echo( "��Ʈw�s�����~!!" );
	exit;
}

//�ثe���Ҽ{�@�ӽҵ{���G�ӥH�W���Ѯv�����p�A�p�G���n�Ҽ{�o�ر��p�A�N�O�⤣�P�Ѯv���m�W�C�X�ӡA�ǵ� mmc�Ammc �W�t�@��{���A�P�_���ӦѮv���}�Y�ɷ|ĳ��
$Q1 = "select name from teach_course as t1, user as t2 where course_id= '$course_id' and  t1.teacher_id = t2.a_id and authorization = 1 and t2.name !='name' ;  ";
$teacher_name=query_db($Q1, 'name');



$teacher_name_encode = urlencode($teacher_name);
//find meetingId from remote mmc
$teacher_id=file_get_contents("http://mmc.elearning.ccu.edu.tw/get_joinnet.php?teacher_name=$teacher_name_encode");

//print "select name from teach_course as t1, user as t2 where course_id= '20331' and  t1.teacher_id = t2.a_id and authorization = 1 and t2.name !='name' and t2.name !=null";
//var_dump($meetingid);
//print_r($meetingid);
if (!is_numeric($teacher_id) ) 
{
	echo("some thing wrong: return value is not numeric. <b>Debug code: $teacher_id, $teacher_name</b>");
}

/* �U���o�ӳ����A�O�d���p�G�b�ǥͶi�J mmc �Y�ɷ|ĳ�ɡA�O�_�i�H��ܦ۶�m�W
   mark �����o�q�A�O�۰����ǥͶ�m�W
   �ثe�w�]�O���ǥͦۤv��
*/

/*
//query this student 
$Q3 = "select * from user where id= '$user_id' ";
$this_user_name=query_db($Q3, 'name');
//print 'here'.$this_user_name;



//$this_user_name = iconv('big5', 'utf-8', $this_user_name);
$this_user_name = urlencode($this_user_name);

//header("Location: http://mmc.elearning.ccu.edu.tw/gotomeeting.php?u=$meetingid&c=visit&name=$this_user_name");
*/

header("Location: http://mmc.elearning.ccu.edu.tw/gotomeeting.php?u=$teacher_id&c=visit");

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



?>

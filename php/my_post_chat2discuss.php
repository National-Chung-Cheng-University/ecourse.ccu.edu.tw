<?php

require_once('fadmin.php') ; 
require_once('my_rja_db_lib.php');

global $DB, $DB_SERVER, $DB_LOGIN, $DB_PASSWORD, $course_id, $discuss_id; ; 




$course_id = $_POST['course_id'] ; 
//$course_id = '20331';
$chattext  = $_POST['chattext'] ; 
//$chattext  = "testtest"; 
$meeting_title 	= $_POST['meeting_title'] ; 
$teacher_id 	= $_POST['teacher_id'] ; 
//$teacher_id = '2'; 


//�P�B�쪺�Q�װϪ��W
$discuss_board_title = '�u�W�Q�װϿ��v�ɤ�r����';

$course_db = $DB.$course_id ; 
mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");


$discuz_exist = "select count(a_id) from discuss_info where discuss_name like '$discuss_board_title' ";
echo $discuz_exist ."<hr/>"; 
$flag_discuz_exist  = query_db_to_value($discuz_exist, $course_db);


if( $flag_discuz_exist == 1) {
	$get_discuz_aid = " select a_id from discuss_info where discuss_name like '$discuss_board_title'" ; 
	//echo $get_discuz_aid . "<hr/>";
	$a_id = query_db_to_value($get_discuz_aid, $course_db);

}else {
	$a_id = 0 ;
}

$tablename = 'discuss_'.$a_id ;
echo $tablename ; 
//�s�W�@�ӰQ�װ�
if( $a_id == 0 )  { 
// reassign $tablename 
	
	$insert_discuz_info = "insert into discuss_info(discuss_name,comment,group_num,access) "
		."values('$discuss_board_title','$discuss_board_title','0','0');";
	
	query_db($insert_discuz_info, $course_db) ;
	
	$find_max_a_id = "select max(a_id) from discuss_info " ; 
	$max_a_id 	= query_db_to_value($find_max_a_id, $course_db) ; 
	$tablename = 'discuss_'.$max_a_id ;
	
	$create_discuss =  "create table $tablename (".
					"       a_id INT NOT NULL AUTO_INCREMENT,".
					"       title VARCHAR(64),".
					"       poster VARCHAR(64),".
					"       created DATETIME,".
					"       replied DATETIME,".
					"       parent INT,".
					"       body BLOB,".
					"       viewed MEDIUMINT,".
					"       type VARCHAR(32),".
					"       sound VARCHAR(64),".
					"       PRIMARY KEY(a_id)".
					");";
	//echo $create_discuss ; 
	// �s�W�Q�װ� table 
	query_db($create_discuss, $course_db) ; 
				
	// �s�W�Q�װϬ���
	$Q2 = "alter table user_profile add ".$tablename." Blob";
	
	if (!mysql_db_query($DB.$course_id,$Q2))
		die("��Ʈw�d�߿��~, $Q2");
}
// else 
// $tablename = 'disscus'.$a_id ; 

$created_time =  date("Y/m/d H:i:s",time());
$sql =  "insert into $tablename(title,poster,created,replied,parent,body,viewed,type,sound) ".
	" values('{$meeting_title}','{$teacher_id}','{$created_time}','{$created_time}',0,'{$chattext}',0,'','');";
	//echo $sql ;
	query_db($sql ,$course_db);
?>

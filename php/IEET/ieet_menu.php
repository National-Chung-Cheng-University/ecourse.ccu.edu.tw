<?php
/* �@�̡G�d�B��
 * �\��G�C�XIEET/index.php�����䪺�ﶵ*/
	include("class.FastTemplate.php3");
	
	require 'fadmin.php';

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");
	
	//��user_id�o��group_id�A�оǥؼЬO�H�t�����
	$result = mysql_db_query($DB, "select * from `user` where id='$user_id'") or die("Query Error");

	$row = mysql_fetch_array($result);
	if( empty($row) )
		die("No Data"); 
	$group_name = $row['name'];
	$tmp = mysql_db_query($DB, "select * from `course_group` where name='$group_name'") or die("Query Error in get group_id");
	$tmp2 = mysql_fetch_array($tmp);
	$group_id = $tmp2['a_id'];
	
	$sql = "select * from IEET_ClassGoal where group_id=$group_id order by ClassGoalNo";
	$result = mysql_db_query($DB, $sql) or die("Query Error in get ClassGoal");

	$tpl = new FastTemplate("./templates");
	
	$tpl->define(array(main => "ieet_menu.tpl"));
	$tpl->assign(BACK, "");
	$tpl->define_dynamic("goal_list", "main");
	
	$row = mysql_fetch_array($result);
	if( empty($row) ){
	//�b�L����оǮ֤߯�O�ɶi�J���B
			$tpl->assign(NUM, "");
			$tpl->assign(TITLE, "");
	}else{
		while( !empty($row) ){
			$tpl->assign(NUM, $row['ClassGoalNo']);
			$tpl->assign(TITLE, $row['title']);
			$tpl->parse(GOAL_LIST,".goal_list");
			$row = mysql_fetch_array($result);
		}
	}
	
	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>
<?php
/* 作者：吳朋憲
 * 功能：列出IEET/index.php中左邊的選項*/
	include("class.FastTemplate.php3");
	
	require 'fadmin.php';

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
	
	//由user_id得到group_id，教學目標是以系為單位
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
	//在無任何教學核心能力時進入此處
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
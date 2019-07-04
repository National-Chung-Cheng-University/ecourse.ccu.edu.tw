<?php
	require 'fadmin.php'
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>教學目標</title>
</head>
</html>

<?php

	include("class.FastTemplate.php3");
	//require 'common.php';
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
	$result = mysql_query("select * from `user` where id='$user_id'") or die("Query Error");
	$row = mysql_fetch_array($result);
	if( empty($row) )
		die("No Data"); 
	$group_name = $row['name'];
	$tmp = mysql_query("select * from `course_group` where name='$group_name'") or die("Query Error in get group_id");
	$tmp2 = mysql_fetch_array($tmp);
	$group_id = $tmp2['a_id'];
	
	$tpl = new FastTemplate("./templates");
	
	$tpl->define(array(main => "display.tpl"));
	$tpl->assign(GROUP, $group_name);
	$tpl->define_dynamic(goal_list, main);
	$tpl->define_dynamic(ability_list, main);
	
	$result = mysql_query("select * from IEET_ClassGoal where group_id=$group_id order by ClassGoalNo");
	$max = 0;
	while($row = mysql_fetch_array($result)){
		$goal_index[$max++] = $row['ClassGoal_Index'];
		$tpl->assign(GOALIND, $row['ClassGoalNo']);
		$tpl->assign(GOALTITLE, $row['title']);
		$tpl->assign(GOALCONT, $row['content']);
		
		$tpl->parse(GOAL_LIST, ".goal_list");
	}
	
	for($t = 0 ; $t < $max ; $t++){
		//linsy@20140213, 需要加入group_id
		//$result = mysql_query("select *from `IEET_CoreAbilities` where ClassGoal_Index=$goal_index[$t] order by CoreAbilitiesNo");
		$result = mysql_query("select *from `IEET_CoreAbilities` where group_id=$group_id and ClassGoal_Index=$goal_index[$t] order by CoreAbilitiesNo");
		while($row = mysql_fetch_array($result)){
			$tpl->assign(GOAIND, $t+1);
			$tpl->assign(CORIND, $row['CoreAbilitiesNo']);
			$tpl->assign(CORCONT, $row['content']);
			
			$tpl->parse(ABILITY_LIST, ".ability_list");
		}
	}
	
	$tpl->parse(BODY, main);

	$tpl->FastPrint(BODY);
?>
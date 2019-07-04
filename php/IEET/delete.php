<?php
	require 'fadmin.php';
	
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
	
	$No = $number;
	
	if($goal == 0){
		$result = mysql_query("select * from `IEET_ClassGoal` where group_id=$group_id and ClassGoalNo=$No");
		$row = mysql_fetch_array($result);
		
		$result = mysql_query("DELETE from `IEET_CoreAbilities` where group_id=$group_id and ClassGoal_Index=$row[ClassGoal_Index]");
		$result = mysql_query("DELETE from `IEET_ClassGoal` where group_id=$group_id and ClassGoalNo=$No");
		$result = mysql_query("UPDATE `IEET_ClassGoal` SET ClassGoalNo = ClassGoalNo-1 where group_id=$group_id and ClassGoalNo > $No");
	}
	else{
		$result = mysql_query("select * from `IEET_ClassGoal` where group_id=$group_id and ClassGoalNo=$goal");
		$row = mysql_fetch_array($result);
		$goal_index = $row['ClassGoal_Index'];
		
		$reuslt = mysql_query("DELETE from `IEET_CoreAbilities` where ClassGoal_Index=$goal_index and CoreAbilitiesNo=$No");
		$result = mysql_query("UPDATE `IEET_CoreAbilities` SET CoreAbilitiesNo = CoreAbilitiesNo-1 where ClassGoal_Index=$goal_index and CoreAbilitiesNo > $No");
	}
	
	$re_url = "location:./ieet_main.php";
	if($goal != 0)
		$re_url = $re_url."?goal=".$goal;
	header($re_url);
?>

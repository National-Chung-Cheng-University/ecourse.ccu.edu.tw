<?php
	//require 'common.php';
	require 'fadmin.php';

	/*修改資料庫中的教育目標/教育核心能力，並存回資料庫中*/
	function modify_data($goal_index, $table, $group_id, $No, $title, $content){
		global $DB;
		if($goal_index == null)
			$command = "UPDATE `$table` SET title='$title', content='$content' where group_id=$group_id and ClassGoalNo=$No";
		else
			$command = "UPDATE `$table` SET content='$content' where ClassGoal_Index=$goal_index and CoreAbilitiesNo=$No";
		$result = mysql_db_query($DB, $command);
		return $result;
	}
	
	
	function insert_data($goal_index, $table, $group_id, $No, $title, $content){
		global $DB;
		if($goal_index == null){
			$sql = "UPDATE $table SET ClassGoalNo = ClassGoalNo+1 where group_id=$group_id and ClassGoalNo >= $No";
			$result = mysql_db_query($DB,$sql);
			$sql = "INSERT INTO $table (group_id, ClassGoalNo, title, content) VALUES ($group_id, $No, '$title', '$content')";
			$result = mysql_db_query($DB,$sql);
		}
		else{
			$sql = "UPDATE $table SET CoreAbilitiesNo = CoreAbilitiesNo+1 where ClassGoal_Index=$goal_index and CoreAbilitiesNo >= $No";
			$result = mysql_db_query($DB, $sql);
			$sql = "INSERT INTO $table (group_id, CoreAbilitiesNo, ClassGoal_Index, content) VALUES ($group_id, $No, $goal_index, '$content')";
			$result = mysql_db_query($DB, $sql);
		}
	}
	
	function valid($goal, $title, $content){
		$f = 0;
		if($content == null || $content == "")
			$f = 1;
		if($goal == 0 && ($title == null || $title == ""))
			$f = 1;
		if($f == 1){
			$re_url = "location:./ieet_main.php?goal=".$goal."&error=資料請勿空白";
			header($re_url);
			exit(0);
		}
	}
	
	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("資料庫連結錯誤");
	valid($goal, $title, $content);

	if($goal == 0){							// 教學目標
		$table = "IEET_ClassGoal";
		$sql = "select * from $table where group_id=$group_id";
		$ClassGoal_index = null;
		$no_str = "ClassGoalNo";
	}
	else{													//教學核心能力
		$table = "IEET_CoreAbilities";
		
		$sql = "select * from `IEET_ClassGoal` where group_id=$group_id and ClassGoalNo=$goal";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$goal_index = $row['ClassGoal_Index'];
		
		$sql = "select * from $table where ClassGoal_Index=$goal_index";
		$no_str = "CoreAbilitiesNo";
	}

	$result = mysql_db_query($DB, $sql) or die("Query Error in get num");
	$num = mysql_num_rows($result);
	
	if($addkind == "insert")
		$new = $insert;
	else if($addkind == "append"){
		$new = $num + 1;
	}else{
		$new = $replace;
	}
	
	if($addkind == "insert"){								//insert
		insert_data($goal_index, $table, $group_id, $new, $title, $content);
	}else if( $addkind == "append" ){					//append
		if($goal == 0)									//新增教學目標
			$sql = "INSERT INTO $table (group_id, $no_str, title, content) VALUES ($group_id, $new, '$title', '$content')";
		else	//新增教學核心能力
			$sql = "INSERT INTO $table (group_id, $no_str, ClassGoal_Index, content) VALUES ($group_id, $new, $goal_index, '$content')";
		$result = mysql_db_query($DB, $sql) or die("Append Error");
	}else{													//modify
		modify_data($goal_index, $table, $group_id, $new, $title, $content);
	}
	
	$re_url = "location:./ieet_main.php";
	if($goal != 0)
		$re_url = $re_url."?goal=".$goal."&reload=1";
	header($re_url);
?>
<?php
/* �@�̡G�d�B��
 * �\��G�C�XIEET/index.php���k�䪺�Ҧ����e*/
	include("class.FastTemplate.php3");
	
	require 'fadmin.php';

	mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("��Ʈw�s�����~");

	$result = mysql_db_query($DB, "select * from `user` where id='$user_id'") or die("Query Error");
	$row = mysql_fetch_array($result);
	if( empty($row) )
		die("No Data"); 
	$group_name = $row['name'];
	$tmp = mysql_db_query($DB, "select * from `course_group` where name='$group_name'") or die("Query Error in get group_id");
	$tmp2 = mysql_fetch_array($tmp);
	$group_id = $tmp2['a_id'];

	if( $goal == null )
		$goal = 0;
		
	if($goal == 0){											//goal
		$table = "IEET_ClassGoal";
		$order = "ClassGoalNo";
		$another = "";
		$tmp_goal = "";
		$str_tit = "�Ш|�ؼ�";
		$str_con = "���e";
		$tit_goal = "<tr bgcolor='#cdeffc'><td colspan='3' align='center'>�Ш|�ؼСG<input type='text' name='title' size='32'></td></tr>";
		$guide = $str_tit;
		$reload = "onLoad=\"parent.menu.location.reload();\"";
	}
	else{													//core abilities
		$table = "IEET_CoreAbilities";
		$order = "CoreAbilitiesNo";
		
		$sql = "select * from `IEET_ClassGoal` where group_id=$group_id and ClassGoalNo=$goal";
		$result = mysql_db_query($DB, $sql);
		$row = mysql_fetch_array($result);
		$goal_index = $row['ClassGoal_Index'];
		
		$another = "and ClassGoal_Index=$goal_index";
		$tmp_goal = $goal.".";
		$str_tit = "";
		$str_con = "�Ш|�֤߯�O";
		$tit_goal = "";
		$guide = $str_con."�G".$row['title'];
		$reload = "";
	}
	$string = (strlen($str_tit) > strlen($str_con)) ? $str_tit : $str_con;
		
	$sql = "select * from $table where group_id=$group_id $another order by $order";
	$result = mysql_db_query($DB, $sql);
	$num = mysql_num_rows($result);
	for($t = 0 ; $t < $num ; $t++)
		$arr[$t] = $t + 1;

	$tpl = new FastTemplate("./templates");
	
	$tpl->define(array(main => "ieet_main.tpl"));
	$tpl->define_dynamic("goal_list", "main");
	
	$tpl->assign(_GROUP, $group_name);
	$tpl->assign(EDITOR, $guide);
	$tpl->assign(STR_TIT, $str_tit);
	$tpl->assign(STR_CON, $str_con);
	$tpl->assign(GROUPID, $group_id);
	$tpl->assign(CURRENT, $goal);
	$tpl->assign(TIT_GOAL, $tit_goal);
	$tpl->assign(ERROR, "<span style=\"color:red;\">".$error."</span>");
	
	$flag = 0;
	while($row = mysql_fetch_array($result)){
		$flag = 1;
		$tpl->assign(NUM, $tmp_goal.$row[$order]);
		$tpl->assign(TITLE, $row['title']);
		$tpl->assign(CONTENT, $row['content']);
		$tpl->assign(SEC, $row[$order]);
		$tpl->assign(CURR, $goal);
		$tpl->assign(AID, $group_id);
		
		$tpl->parse(GOAL_LIST,".goal_list");
	}
	if($flag == 0){
		$tpl->assign(NUM, "");
		$tpl->assign(TITLE, "");
		$tpl->assign(CONTENT, "");
	}

	$replace = "<select name='replace'><option value=0>�п�ܭקﶵ��</option>";
	for($t = 0 ; $t < $num ; $t++){
		$replace = $replace."<option value=".$arr[$t].">�ק�".$string."�� ".$arr[$t]."��</option>";
	}
	$replace = $replace."</select>";
	$tpl->assign(REPLACE, $replace);
	$tpl->assign("RELOAD_CTRL", $reload);
	
	$insert = "<select name='insert'><option value=0>�п�ܴ��J�I</option>";
	for($t = 0 ; $t < $num ; $t++){
		$insert = $insert."<option value=".$arr[$t].">�b".$string."�� ".$arr[$t]."���e���J</option>";
	}
	$insert = $insert."</select>";
	$tpl->assign(INSERT, $insert);
	
	$tpl->parse(BODY, "main");

	$tpl->FastPrint(BODY);
?>
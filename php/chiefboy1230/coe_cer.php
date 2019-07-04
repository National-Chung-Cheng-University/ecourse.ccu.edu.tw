<?php
	function logincount($group_id)
	{
		global $DB_SERVER, $DB_LOGIN, $DB_PASSWORD, $DB;
		mysql_connect($DB_SERVER, $DB_LOGIN, $DB_PASSWORD);
		
		$sql = "
		SELECT DISTINCT student_id 
		FROM `take_course` 
		WHERE `group_id` = '$group_id' AND year = '100'
		";
		
		$result = mysql_db_query($DB,$sql);
		
		while($row = mysql_fetch_array($result))
		{
		
			$sql1 = "select tag3 from log where event_id='1' and user_id = $row[student_id]";
			$result1 = mysql_db_query($DB,$sql1);
			$row1 = mysql_fetch_array($result1);
			$count += $row1['tag3'];
		
		}
		
		$sql = "
		SELECT name	
		FROM `course_group` 
		WHERE `a_id` = '$group_id'
		";
		
		$result = mysql_db_query($DB,$sql);
		$row = mysql_fetch_array($result);
		$output = array($row['name'],$count);
			
		return $output;
	}


	require_once('fadmin.php');

	$coes = array(11,12,13,14,15,16,17,18,19,20,21,90,105,118,119);	//College of Engineering's group_id
	$idvcount = array();
	echo "<table border='1' align='center'><tr><td>開課單位</td><td>累計登入次數</td></tr>";

	foreach($coes as $coe)
	{
		$idvcount = logincount($coe);
		if($idvcount[1] !== NULL && $idvcount[1] !== 0)
			echo "<tr><td>" . $idvcount[0] . "</td><td>" . $idvcount[1] . "</td></tr>";
		$total += $idvcount[1];
	}

	echo "<tr><td colspan='2'>總共累計登入次數：" . $total . "</td></tr>";
	echo "</table>";

?>
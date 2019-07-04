<?php
	require 'fadmin.php';
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
	$group_id = Get_group_id($course_id);
	$space="&nbsp;";
	update_status ("課程內涵");

	//判別大學部or研究所
	if($group_id == 11){
		$dep = "大學部";
		$AbilitiesNumber = 11;
	}
	else if ($group_id == 12){
		$dep = "研究所";
		$AbilitiesNumber = 8;
	}
	else if ($group_id == 15){
                $dep = "大學部";
                $AbilitiesNumber = 12;

        }
        else if ($group_id == 16){
                $dep = "研究所";
                $AbilitiesNumber = 8;
        }


	//取得課程名稱
	$SQL_Select = "SELECT name FROM course WHERE a_id = '$course_id' AND group_id = '$group_id' ";
	if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
		$message = "'$SQL_Select' 資料庫讀取錯誤!!<br>";
		echo $message;
	}
	$ClassTopicNumber = mysql_num_rows( $result);
	$row = mysql_fetch_array( $result );
	$name = $row['name'];

	/*      製作表單     */
	$HTMLForm = 
	"<div align=\"center\">課程代碼：" . $course_id . "   課程名稱：" . $name . "</div><br>
	<table border=\"1\" align=\"center\">
		<tr>
			<td align=\"center\" rowspan=\"2\">課程大綱</td>
			<td colspan=\"".$AbilitiesNumber."\" align=\"center\">" . $dep . "自定之核心能力</td>
		</tr>
		<tr>";
	for($i=1; $i<=$AbilitiesNumber; $i++)
		$HTMLForm = $HTMLForm . "<td>核力能力" . $i . "</td>";

	$HTMLForm = $HTMLForm . "</tr>";

	//統計核心能力數量 初始
	$TotalCount = array();
	for($i=1; $j<=$AbilitiesNumber; $j++)
		$TotalCount[$i] = 0;

	//課程大綱裡面勾選出來相對應的核心能力
    $SQL_Select = "SELECT ClassTopicNo, ClassTopic, CoreAbilities FROM IEEE_CourseIntro_ClassTopic WHERE course_id = '$course_id' AND group_id = '$group_id' ORDER BY ClassTopicNo ASC";
    if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
        $message = "'$SQL_Select' 資料庫讀取錯誤!!<br>";
        echo $message;
    }
    $ClassTopicNumber = mysql_num_rows( $result);

	//$HTMLForm = $HTMLForm . "<tr><td colspan=\"".($AbilitiesNumber+1)."\" align=\"center\">課程大綱</td></tr>";
	for($i=1; $i<=$ClassTopicNumber; $i++){ //row
		$row = mysql_fetch_array($result);
		$HTMLForm = $HTMLForm .
			"<tr><td>" . $row['ClassTopic'] . $space . "</td>";

		$ClassTopic_CoreAbilitiesTmp = split(",", $row['CoreAbilities']);
		for($j=1; $j<=$AbilitiesNumber; $j++){  //colum
			$checked = "&nbsp;";
			for($k=0; $k<count($ClassTopic_CoreAbilitiesTmp); $k++)
				if($j == $ClassTopic_CoreAbilitiesTmp[$k]){
					$checked = "V";
					$TotalCount[$j]++;
				}
			$HTMLForm = $HTMLForm . "<td align=\"center\">" . $checked . "</td>";
		}
	}
	
	/*
	//取得作業的核心能力
    $SQL_Select = "SELECT name, percentage, CoreAbilities FROM homework ORDER BY name ASC";
    if ( !($result = mysql_db_query( $DB.$course_id, $SQL_Select ) ) ) {
        $message = "'$SQL_Select' 資料庫讀取錯誤!!<br>";
        echo $message;
    }
    $homeworkNumber = mysql_num_rows( $result);

	$HTMLForm = $HTMLForm . "<tr><td colspan=\"".($AbilitiesNumber+1)."\" align=\"center\">作業</td></tr>";
	for($i=1; $i<=$homeworkNumber; $i++){ //row
		$row = mysql_fetch_array($result);
		$HTMLForm = $HTMLForm .
			"<tr><td>" . $row['name'] . $space . "</td>";

		$ClassTopic_CoreAbilitiesTmp = split(",", $row['CoreAbilities']);
		for($j=1; $j<=$AbilitiesNumber; $j++){  //colum
			$checked = "&nbsp;";
			for($k=0; $k<count($ClassTopic_CoreAbilitiesTmp); $k++){
				if($j == $ClassTopic_CoreAbilitiesTmp[$k]){
					$checked = "V";
					$totalScore[$j] = $totalScore[$j] + $row['percentage'];
				}
			}
			$HTMLForm = $HTMLForm . "<td align=\"center\">" . $checked . "</td>";
		}
	}
	
	//取得測驗的核心能力 先列出線上測驗 再列出其他測驗
    $SQL_Select = "SELECT name, percentage, CoreAbilities FROM exam ORDER BY is_online DESC, a_id ASC";
    if ( !($result = mysql_db_query( $DB.$course_id, $SQL_Select ) ) ) {
        $message = "'$SQL_Select' 資料庫讀取錯誤!!<br>";
        echo $message;
    }
    $homeworkNumber = mysql_num_rows( $result);

	$HTMLForm = $HTMLForm . "<tr><td colspan=\"".($AbilitiesNumber+1)."\" align=\"center\">測驗</td></tr>";
	for($i=1; $i<=$homeworkNumber; $i++){ //row
		$row = mysql_fetch_array($result);
		$HTMLForm = $HTMLForm .
			"<tr><td>" . $row['name'] . $space . "</td>";

		$ClassTopic_CoreAbilitiesTmp = split(",", $row['CoreAbilities']);
		for($j=1; $j<=$AbilitiesNumber; $j++){  //colum
			$checked = "&nbsp;";
			for($k=0; $k<count($ClassTopic_CoreAbilitiesTmp); $k++){
				if($j == $ClassTopic_CoreAbilitiesTmp[$k]){
					$checked = "V";
					$totalScore[$j] = $totalScore[$j] + $row['percentage'];
				}
			}
			$HTMLForm = $HTMLForm . "<td align=\"center\">" . $checked . "</td>";
		}
	}
	*/
	
	//列出總計的資料
	$HTMLForm = $HTMLForm .	"<tr><td>總計</td>";
	for($i=1; $i<=$AbilitiesNumber; $i++)
		$HTMLForm = $HTMLForm . "<td align=\"center\">".$space.$TotalCount[$i].$space."</td>";

	//列出各佔多少百分比
	if($ClassTopicNumber==0) $ClassTopicNumber=1; //避免除以0
	$HTMLForm = $HTMLForm .	"</tr><tr><td>百分比</td>";
	for($i=1; $i<=$AbilitiesNumber; $i++)
		$HTMLForm = $HTMLForm . "<td align=\"center\">".$space.round(100*$TotalCount[$i]/$ClassTopicNumber)."%</td>";

	$HTMLForm = $HTMLForm . 
		"</tr>
	</table>";

	echo $HTMLForm;


function Get_group_id($a_id){
        //SQL Server的資料
        global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

        //從資料庫取得group_id
        $SQL_Select = "SELECT group_id FROM course WHERE a_id = '$a_id'";
        if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
                $message = "function Get_group_id($a_id) 資料庫讀取錯誤!!<br>";
                echo $message;
        }
        $row = mysql_fetch_array( $result );

        return $row['group_id'];
}

?>

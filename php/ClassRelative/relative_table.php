<?php
	require 'fadmin.php';
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
	$group_id = Get_group_id($course_id);
	$space="&nbsp;";
	update_status ("�ҵ{���[");

	//�P�O�j�ǳ�or��s��
	if($group_id == 11){
		$dep = "�j�ǳ�";
		$AbilitiesNumber = 11;
	}
	else if ($group_id == 12){
		$dep = "��s��";
		$AbilitiesNumber = 8;
	}
	else if ($group_id == 15){
                $dep = "�j�ǳ�";
                $AbilitiesNumber = 12;

        }
        else if ($group_id == 16){
                $dep = "��s��";
                $AbilitiesNumber = 8;
        }


	//���o�ҵ{�W��
	$SQL_Select = "SELECT name FROM course WHERE a_id = '$course_id' AND group_id = '$group_id' ";
	if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
		$message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
		echo $message;
	}
	$ClassTopicNumber = mysql_num_rows( $result);
	$row = mysql_fetch_array( $result );
	$name = $row['name'];

	/*      �s�@���     */
	$HTMLForm = 
	"<div align=\"center\">�ҵ{�N�X�G" . $course_id . "   �ҵ{�W�١G" . $name . "</div><br>
	<table border=\"1\" align=\"center\">
		<tr>
			<td align=\"center\" rowspan=\"2\">�ҵ{�j��</td>
			<td colspan=\"".$AbilitiesNumber."\" align=\"center\">" . $dep . "�۩w���֤߯�O</td>
		</tr>
		<tr>";
	for($i=1; $i<=$AbilitiesNumber; $i++)
		$HTMLForm = $HTMLForm . "<td>�֤O��O" . $i . "</td>";

	$HTMLForm = $HTMLForm . "</tr>";

	//�έp�֤߯�O�ƶq ��l
	$TotalCount = array();
	for($i=1; $j<=$AbilitiesNumber; $j++)
		$TotalCount[$i] = 0;

	//�ҵ{�j���̭��Ŀ�X�Ӭ۹������֤߯�O
    $SQL_Select = "SELECT ClassTopicNo, ClassTopic, CoreAbilities FROM IEEE_CourseIntro_ClassTopic WHERE course_id = '$course_id' AND group_id = '$group_id' ORDER BY ClassTopicNo ASC";
    if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
        $message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
        echo $message;
    }
    $ClassTopicNumber = mysql_num_rows( $result);

	//$HTMLForm = $HTMLForm . "<tr><td colspan=\"".($AbilitiesNumber+1)."\" align=\"center\">�ҵ{�j��</td></tr>";
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
	//���o�@�~���֤߯�O
    $SQL_Select = "SELECT name, percentage, CoreAbilities FROM homework ORDER BY name ASC";
    if ( !($result = mysql_db_query( $DB.$course_id, $SQL_Select ) ) ) {
        $message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
        echo $message;
    }
    $homeworkNumber = mysql_num_rows( $result);

	$HTMLForm = $HTMLForm . "<tr><td colspan=\"".($AbilitiesNumber+1)."\" align=\"center\">�@�~</td></tr>";
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
	
	//���o���窺�֤߯�O ���C�X�u�W���� �A�C�X��L����
    $SQL_Select = "SELECT name, percentage, CoreAbilities FROM exam ORDER BY is_online DESC, a_id ASC";
    if ( !($result = mysql_db_query( $DB.$course_id, $SQL_Select ) ) ) {
        $message = "'$SQL_Select' ��ƮwŪ�����~!!<br>";
        echo $message;
    }
    $homeworkNumber = mysql_num_rows( $result);

	$HTMLForm = $HTMLForm . "<tr><td colspan=\"".($AbilitiesNumber+1)."\" align=\"center\">����</td></tr>";
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
	
	//�C�X�`�p�����
	$HTMLForm = $HTMLForm .	"<tr><td>�`�p</td>";
	for($i=1; $i<=$AbilitiesNumber; $i++)
		$HTMLForm = $HTMLForm . "<td align=\"center\">".$space.$TotalCount[$i].$space."</td>";

	//�C�X�U���h�֦ʤ���
	if($ClassTopicNumber==0) $ClassTopicNumber=1; //�קK���H0
	$HTMLForm = $HTMLForm .	"</tr><tr><td>�ʤ���</td>";
	for($i=1; $i<=$AbilitiesNumber; $i++)
		$HTMLForm = $HTMLForm . "<td align=\"center\">".$space.round(100*$TotalCount[$i]/$ClassTopicNumber)."%</td>";

	$HTMLForm = $HTMLForm . 
		"</tr>
	</table>";

	echo $HTMLForm;


function Get_group_id($a_id){
        //SQL Server�����
        global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

        //�q��Ʈw���ogroup_id
        $SQL_Select = "SELECT group_id FROM course WHERE a_id = '$a_id'";
        if ( !($result = mysql_db_query( $DB, $SQL_Select ) ) ) {
                $message = "function Get_group_id($a_id) ��ƮwŪ�����~!!<br>";
                echo $message;
        }
        $row = mysql_fetch_array( $result );

        return $row['group_id'];
}

?>

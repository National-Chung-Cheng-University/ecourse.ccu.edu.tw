<?php
	/* ------------------------------------------------------ */
	/* assistantquestionary_showresult.php                    */
	/* Written by carlyle.                                    */
	/* Modify by ghost777.                                    */
	/* ------------------------------------------------------ */
	
	require 'fadmin.php';
	update_status ("�[�ݰݨ����G");
			
	if (isset($PHPSESSID) && session_check_stu($PHPSESSID) ) //�ק�i�Ӫ�����
	{
		global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$user_id;
		if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)))
			show_page("not_access.tpl","��Ʈw�s�����~!!");
		
		$Q1 = "SELECT group_id FROM course WHERE a_id = '$courseid'"; 
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		else{
			$row1 = mysql_fetch_array($result);
		}
			
		$Q1 = "SELECT name FROM course_group WHERE a_id = '$row1[0]'"; 
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		else
			$row1 = mysql_fetch_array($result);
		
		$Q1 = "SELECT is_showname FROM questionary WHERE group_name = '$row1[0]'"; 
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		else {
			if (!mysql_num_rows($result)) 
				die('�t��|���o�G�ݨ�!!');
			else {
				$row1 = mysql_fetch_array($result);
				$is_showname = $row1[0]; //�O�_�O�W
			}
		}	
				
		if ($action == 'showdetail') {
			ShowDetail();
		} else {
			if ($is_showname == '0' || $showstatistics == '1') //���O�W or �O�W�ɿ���[�ݾ���έp���
				ShowAnonymousResult();
			else { //�O�W
				echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=big5" />
		<title>�ǥͦC��</title>';
				echo '<style type="text/css">';
				echo '<!--';
				echo 'body {background-image: url(/images/skin1/bbg.gif) ; font-size: 13px}';
				echo '-->';
				echo '</style>';
				echo '</head>';
				echo '<script language = javascript>';
				echo 'function setPointer(theRow, thePointerColor) {';
				echo '    if (typeof(theRow.style) == "undefined")';
				echo '        return false;';
				echo '    if (typeof(document.getElementsByTagName) != "undefined")';
				echo '        var theCells = theRow.getElementsByTagName("td");';
				echo '    else if (typeof(theRow.cells) != "undefined")';
				echo '        var theCells = theRow.cells;';
				echo '    else';
				echo '        return false;';
				echo '    var rowCellsCnt  = theCells.length;';
				echo '    for (var c = 0; c < rowCellsCnt; c++)';
				echo '        theCells[c].style.backgroundColor = thePointerColor;';
				echo '    return true;';
				echo '}';
				echo '</script>';
		
				$Q1 = "SELECT name FROM course WHERE a_id = '$courseid'"; 
				if (!($result = mysql_db_query($DB,$Q1)))
					show_page("not_access.tpl","��ƮwŪ�����~!!");
				else {
					if (!mysql_num_rows($result)) 
						die('�䤣��courseid�������ҵ{�W��!!!');
					else
						$row1 = mysql_fetch_array($result);
				}
				
				$Q1 = "SELECT student_id FROM take_course WHERE course_id = '$courseid' and year='$year' and term = '$term'"; 
				if (!($result = mysql_db_query($DB,$Q1)))
					show_page("not_access.tpl","��ƮwŪ�����~!!");
				else {
					$student_count = mysql_num_rows($result); //�����Ҫ��ǥ��`��
					if (!$student_count) die('���ҵ{�|�����ǥ�!!!');
				}
				
				echo '<p>�ҵ{�W��: <b>'.$row1[0].'</b></p>'; //display �ҵ{�W��
				echo '<b>�U��P�Ǧn�A�Ш̷ӦѮv�Ҷ�g�ҵ{����i�H���i�A���U���֤߯�O�������ӵ��q�A�O�_�q�ײߥ��ҵ{�����i���������֤߯�O�C�Ҧp��1.1 ��T�u�{������¦���Ѥ��l���P�F�Ѫ���O���O�_�̦Ѯv��w�p����k�A�q�ײ߳o���Ҧӯ�R���i���Ѯv�w�����ؼСC</b><br><br>';
				echo '<table width="509" border=1 bordercolor=#9FAE9D>
		<tr><td width="499"><Table border=0><tr bgcolor="#4d6eb2"><td width="161"><div align="center"><font color="#FFFFFF">�Ǹ�</font></div></td><td width="166"><div align="center"><font color="#FFFFFF">�m�W</font></div></td><td width="170"><div align="center"><font color="#FFFFFF">�ݨ����G</font></div></td></tr>';
				
				$total_ready = 0; //�w��g�ݨ����H��
				$total_unready = 0; //�|����g�ݨ����H��
				for ($i = 1;$i <= $student_count;$i++) {
					$row1 = mysql_fetch_array($result); //$row1[0] = student's a_id
					
					$Q2 = "SELECT id,name FROM user WHERE a_id = '$row1[0]'"; 
					if (!($result2 = mysql_db_query($DB,$Q2)))
						show_page("not_access.tpl","��ƮwŪ�����~!!");
					else { 
						if (!mysql_num_rows($result2)) continue; //�o�ӾǥͳQ�R���F?? ���ޥL
						$row2 = mysql_fetch_array($result2);
						$student_id = $row2[0];
						$student_name = $row2[1];
					}
				
					$Q2 = "SELECT count(*) FROM questionary_r WHERE student_id = '$student_id' and student_name = '$student_name' and course_id = '$courseid'"; 
					if (!($result2 = mysql_db_query($DB,$Q2)))
						show_page("not_access.tpl","��ƮwŪ�����~!!");
					else { 
						$row2 = mysql_fetch_array($result2);
						if ($row2[0] == 0) {
							$total_unready++; //����g���H�ƥ[�@
							if ($is_showname == '0') continue; //�Y�O���O�W,�|����g�ݨ����N���C�X
							$current_status = '<font color=red>�|����g</font>';
						} else {
							$total_ready++; //�w��g���H�ƥ[�@
							$current_status = '<a href="assistantquestionary_showresult.php?action=showdetail&courseid='.$courseid.'&studentid='.$student_id.'&year='.$year.'&term='.$term.'">�[�ݵ��G</a>';
						}
					}
					
					echo '<tr onmouseover="setPointer(this,\'#C6E6DE\')" onmouseout="setPointer(this,\'#BFCEBD\')"><td height="23" bordercolor="#000000" bgcolor="#BFCEBD"><div align="center">'.$student_id.'</div></td><td bordercolor="#000000" bgcolor="#BFCEBD"><div align="center">'.$student_name.'</div></td><td bordercolor="#000000" bgcolor="#BFCEBD"><div align="center">'.$current_status.'</div></td></tr>';
				}
	
				echo '</table></td></tr></table><br><br>';
				echo '<a href="assistantquestionary_showresult.php?courseid='.$courseid.'&showstatistics=1&year='.$year.'&term='.$term.'" target="_blank">����ݨ����G�έp</a><br><br>';
				echo '*�w��g�ݨ��H��: '.$total_ready.'<br>';
				echo '&nbsp;����g�ݨ��H��: <font color="#FF0000">'.$total_unready.'</font><br>';
				echo '</body></html>';
			}
		}
	}

	/* ���O�W or �O�W�ɿ���[�ݾ���έp��� */
	function ShowAnonymousResult()
	{
		global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$courseid,$message,$user_id,$year,$term;
		$group_id = Get_group_id($courseid); //���o�����Ҫ�group_id
		
		/* ���X�ҵ{�W�� */
		$Q1 = "SELECT name FROM course WHERE a_id = '$courseid'"; 
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		else {
			$row1 = mysql_fetch_array($result);
			$course_name = $row1['name']; //�o���Ҫ��W��
		}
		
		/* �ˬd�o��ҬO�_���ǥ� */
		$Q1 = "SELECT student_id FROM take_course WHERE course_id = '$courseid' and year='$year' and term = '$term'"; 
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		else {
			$student_count = mysql_num_rows($result); //�o���Ҫ��ǥ��`��
			if (!$student_count) die('���ҵ{�|�����ǥ�!!');
		}
		
		PrintDetailPageHeader('�ҵ{�W��: <b>'.$course_name.'</b><br><br>');
		
		/* ���X�o���Ҫ��ݨ��D�� */
		$Q1 = "SELECT IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo,IEET_CoreAbilities.content FROM IEEE_CourseIntro_CoreAbilities,IEET_ClassGoal,IEET_CoreAbilities WHERE IEEE_CourseIntro_CoreAbilities.course_id = '$courseid' and IEEE_CourseIntro_CoreAbilities.isChecked = '1' and IEEE_CourseIntro_CoreAbilities.ClassGoal_Index = IEET_ClassGoal.ClassGoal_Index and IEEE_CourseIntro_CoreAbilities.CoreAbilities_Index = IEET_CoreAbilities.CoreAbilities_Index ORDER BY IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo";
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		else {
			$q_count = mysql_num_rows($result);
			if (!$q_count) die('���ҵ{�|���]�w�ݨ����e!');
		}

		/* ���X�o�ӽҵ{�Ҧ��ǥͪ����� */
		//$Q1 = "SELECT student_name,answer FROM questionary_r WHERE course_id = '$courseid'";
		$Q1="SELECT u.id, u.name, q.answer FROM take_course as t, user as u, questionary_r as q WHERE t.course_id='".$courseid."' and t.year='".$year."' and t.term='".$term."' and t.student_id=u.a_id and u.id=q.student_id and q.course_id='".$courseid."' order by q.student_id";

		if (!($result2 = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		else {
			$r_count = mysql_num_rows($result2); //�Ҧ��w��ݨ����ǥ��`��
		}
		/* �v�D�ˬd�U�ӿﶵ���^���H�� */
		for ($q_index=0;$q_index<$q_count;$q_index++) {
			$row_q = mysql_fetch_array($result); //$row_q[2] = �o�D���D��
			
			$select_A_count = 0; //�o�D��A���H��
			$select_B_count = 0; //�o�D��B���H��
			$select_C_count = 0; //�o�D��C���H��
			$select_D_count = 0; //�o�D��D���H��
			$select_E_count = 0; //�o�D��E���H��
			
			/* �p��o�D�����ӿﶵ�U���X�ӤH�� */
			if($r_count!=0){ //�p�G�^���ݨ��H�Ƥ��O0�H�~���έp
				mysql_data_seek($result2,0); //�Npointer���^�Ĥ@�C
				for ($sr=0;$sr<$r_count;$sr++) {
					$row1 = mysql_fetch_array($result2);
					$tmpans = substr($row1['answer'],$q_index,1); //���X�o�Ӿǥͪ��o�@�D������
					
					if ($tmpans == 'A') 
						$select_A_count++;
					else if ($tmpans == 'B') 
						$select_B_count++;
					else if ($tmpans == 'C') 
						$select_C_count++;
					else if ($tmpans == 'D') 
						$select_D_count++;
					else if ($tmpans == 'E') 
						$select_E_count++;
				}
			}
			
			if ($q_index % 2 == 1) 
				echo '  <tr onmouseover="setPointer(this,\'#E8FCDA\')" onmouseout="setPointer(this,\'\')">';
			else
				echo '  <tr bgcolor="#CCCCCC" onmouseover="setPointer(this,\'#E8FCDA\')" onmouseout="setPointer(this,\'#CCCCCC\')">';
			if($group_id==12)
				echo '    <td height="35"><b>G'.$row_q[1].'</b> '.$row_q[2].'</td>';
			else
				echo '    <td height="35"><b>'.$row_q[0].'.'.$row_q[1].'</b> '.$row_q[2].'</td>';
			echo '    <td><div align="center">'.$select_A_count.'</div></td>';
			echo '    <td><div align="center">'.$select_B_count.'</div></td>';
			echo '    <td><div align="center">'.$select_C_count.'</div></td>';
			echo '    <td><div align="center">'.$select_D_count.'</div></td>';
			echo '    <td><div align="center">'.$select_E_count.'</div></td></tr>';
		}
		echo '</table>';
		//��questionary_r�o��table�����X�ǥͫ�ĳ
	        $stu_sug="";
        	$sug_count=0;
	        $sql = "select * from questionary_r where course_id = $courseid";
        	$result = mysql_query($sql) or die("questionary_r query error!");
	        while( $row=mysql_fetch_array($result) ){
        	        if( $row['suggestion'] !='' || $row['suggestion'] !=null ){
                	        $sug_count++;
                        	$stu_sug = $stu_sug.$sug_count.". ".$row['suggestion'].chr(13).chr(10);
                	}
        	}
	        if( $stu_sug == '' ){
        	        $stu_sug="�ثe�L����߱o�P��ĳ";
        	}
                echo '<table><tr><td><br><br></td></tr>';
                echo '<tr><td><font color="blue">��o��Ҫ��߱o�P��ĳ</font></td></tr>';
                echo '<tr><td><textarea name="suggestion" ROWS=10 COLS=100 />'.$stu_sug.'</textarea></td></tr></table>';

		echo '<br><br>';
		echo '*�w��g�ݨ��H��: '.$r_count.'<br>';
		echo '&nbsp;����g�ݨ��H��: <font color="#FF0000">'.($student_count - $r_count).'</font><br>';
		echo '</body>';
		echo '</html>';
	}
	
	/* �O�W�벼��,��ܬY��ǥͪ��ݨ����G */	
	function ShowDetail()
	{
		global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$courseid,$studentid,$message,$user_id;
		
		$Q1 = "SELECT student_name,answer FROM questionary_r WHERE course_id = '$courseid' and student_id='$studentid'";
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		else
			$row1 = mysql_fetch_array($result);
			
		$student_name = $row1[0]; //�ǥͩm�W
		$student_answer = $row1[1]; //�ǥͪ��ݨ�����

		$Q1 = "SELECT IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo,IEET_CoreAbilities.content FROM IEEE_CourseIntro_CoreAbilities,IEET_ClassGoal,IEET_CoreAbilities WHERE IEEE_CourseIntro_CoreAbilities.course_id = '$courseid' and IEEE_CourseIntro_CoreAbilities.isChecked = '1' and IEEE_CourseIntro_CoreAbilities.ClassGoal_Index = IEET_ClassGoal.ClassGoal_Index and IEEE_CourseIntro_CoreAbilities.CoreAbilities_Index = IEET_CoreAbilities.CoreAbilities_Index ORDER BY IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo";

		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		else {
			$r_count = mysql_num_rows($result);
			if (!$r_count) die('���ҵ{�|���]�w�ݨ����e!');
		}

		PrintDetailPageHeader('�ǥͩm�W: <b>'.$student_name.'</b><br><br>');
	
		for ($i = 1;$i <= $r_count; $i++) {			
			$row1 = mysql_fetch_array($result);
			
			if ($i > strlen($student_answer)) break; //�o��Y����,�N�O�����D�F!!!
			$tmpans = substr($student_answer,($i-1),1); //���X�Y�@�D������
			
			if ($i % 2 == 1) 
				echo '  <tr onmouseover="setPointer(this,\'#E8FCDA\')" onmouseout="setPointer(this,\'\')">';
			else
				echo '  <tr bgcolor="#CCCCCC" onmouseover="setPointer(this,\'#E8FCDA\')" onmouseout="setPointer(this,\'#CCCCCC\')">';
			echo '    <td height="35"><b>'.$row1[0].'.'.$row1[1].'</b> '.$row1[2].'</td>';
			echo '    <td><div align="center">';
			if ($tmpans == 'A')	echo 'v';
			echo '    </div></td>';
			echo '    <td><div align="center">';
			if ($tmpans == 'B')	echo 'v';
			echo '    </div></td>';
			echo '    <td><div align="center">';
			if ($tmpans == 'C')	echo 'v';
			echo '    </div></td>';
			echo '    <td><div align="center">';
			if ($tmpans == 'D')	echo 'v';
			echo '    </div></td>';
			echo '    <td><div align="center">';
			if ($tmpans == 'E')	echo 'v';
			echo '    </div></td>';
			echo '  </tr>';
		}			
		
		echo '</table>';
		echo '<br><p><input type=button value=�^�ǥͦC�� onclick="javascript:history.back()"></p>';
		echo '</body>';
		echo '</html>';
	}
	
	function PrintDetailPageHeader($title)
	{
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml">';
		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=big5" />';
		echo '<title>�[�ݰݨ����G</title>';
		echo '<style type="text/css">';
		echo '<!--';
		echo 'body {background-image: url(/images/skin1/bbg.gif) ; font-size: 13px}';
		echo '.bfont {font-size: 9pt}';
		echo '.style1 {color: #FFFFFF}';
		echo '.style3 {color: #FFFF00; font-weight: bold; }';
		echo '-->';
		echo '</style>';
		echo '</head>';
		echo '<script language = javascript>';
		echo 'function setPointer(theRow, thePointerColor) {';
		echo '    if (typeof(theRow.style) == "undefined")';
		echo '        return false;';
		echo '    if (typeof(document.getElementsByTagName) != "undefined")';
		echo '        var theCells = theRow.getElementsByTagName("td");';
		echo '    else if (typeof(theRow.cells) != "undefined")';
		echo '        var theCells = theRow.cells;';
		echo '    else';
		echo '        return false;';
		echo '    var rowCellsCnt  = theCells.length;';
		echo '    for (var c = 0; c < rowCellsCnt; c++)';
		echo '        theCells[c].style.backgroundColor = thePointerColor;';
		echo '    return true;';
		echo '}';
		echo '</script>';
		echo '<body>';
		echo $title;
		echo '<b>�U��P�Ǧn�A�Ш̷ӦѮv�Ҷ�g�ҵ{����i�H���i�A���U���֤߯�O�������ӵ��q�A�O�_�q�ײߥ��ҵ{�����i���������֤߯�O�C�Ҧp��1.1 ��T�u�{������¦���Ѥ��l���P�F�Ѫ���O���O�_�̦Ѯv��w�p����k�A�q�ײ߳o���Ҧӯ�R���i���Ѯv�w�����ؼСC</b><br><br>';
		echo '<table width="99%" border="0">';
		echo '  <tr>';
		echo '    <td width="42%" rowspan="2" bgcolor="#4d6eb2"><div align="center" class="style3">�ݨ��D��</div></td>';
		echo '    <td height="27" colspan="5" bgcolor="#4d6eb2"><div align="center" class="style3">�ﶵ</div></td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td width="11%" height="24" bgcolor="#4d6eb2"><div align="center" class="style1">A: �R���i��</div></td>';
		echo '    <td width="14%" bgcolor="#4d6eb2"><div align="center" class="style1">B: ���j�����i��</div></td>';
		echo '    <td width="10%" bgcolor="#4d6eb2"><div align="center" class="style1">C: �����i��</div></td>';
		echo '    <td width="13%" bgcolor="#4d6eb2"><div align="center" class="style1">D: ���j�������i��</div></td>';
		echo '    <td width="10%" bgcolor="#4d6eb2"><div align="center" class="style1">E: �������i��</div></td>';
		echo '  </tr>';
	}

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

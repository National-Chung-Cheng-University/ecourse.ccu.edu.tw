<?php
	/* ------------------------------------------------------ */
	/* assistantquestionary.php                               */
	/* Written by carlyle.                                    */
	/* ------------------------------------------------------ */
	
	require 'fadmin.php';
	update_status ("�u�W�ݨ���");
	
	if (isset($PHPSESSID) && (session_check_teach($PHPSESSID)))
	{
		global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$course_id,$message,$user_id;

	 	if ($course_id == '' || $course_id == -1)
			die('Invalid <b>course_id</b> !');
			
		if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD))) {
			show_page("not_access.tpl","��Ʈw�s�����~!!");
			exit;
		}
		
		if ($cancel != '') header("Location: http://$SERVER_NAME/php/news/news.php"); //������g�ݨ�
			
		if ($action == 'completequestionary') { //ú��ݨ�
			/* �ˬd�O�_�Ҧ����D�����^�� */
			$answer = ''; //�s�ǥͪ�����
			$j = 0;
			for ($i = 1;$i <= $q_count;$i++) {
				$tmp = 'selection_'.$i;
				if ($$tmp == '') {
					$j = 1;
					break;
				} else
					$answer = $answer.$$tmp;
			}
			
			if ($j != 1) { //��g���G�s�Jdatabase
				$Q1 = "SELECT count(*) FROM questionary_r WHERE course_id = '$course_id' and student_id='$user_id'";
				if (!($result = mysql_db_query($DB,$Q1)))
					show_page("not_access.tpl","��ƮwŪ�����~!!");
				else
					$row1 = mysql_fetch_array($result);
					
				if ($row1[0] == 0) { //�ǥͥ���L���ݨ�
					$Q1 = "SELECT id FROM questionary_r ORDER BY id DESC LIMIT 0, 1"; 
					if (!($result_tmp = mysql_db_query($DB,$Q1)))
						show_page("not_access.tpl","��ƮwŪ�����~!!");
					else {
						$row_tmp = mysql_fetch_array($result_tmp);
						$lastid = $row_tmp[0] + 1; //get last id
					}
							
					$Q1 = "SELECT name FROM user WHERE id='$user_id'";
					if (!($result = mysql_db_query($DB,$Q1)))
						show_page("not_access.tpl","��ƮwŪ�����~!!");
					else
						$row1 = mysql_fetch_array($result); //$row1[0] = �ǥͦW��
					
					
					//chiefboy1230@20120109�A�ѨM�\�\�\�����D�C
					/*
					if($user_id == "400410035")
					{
						//$user_name = iconv("big5","UTF-8",$row1[0]);
						$row1[0] ="";
					}
					*/
					$row1[0] = addslashes($row1[0]);
					
					
					$Q1 = "INSERT INTO questionary_r 
	(id,course_id,student_id,student_name,answer,suggestion) VALUES('$lastid','$course_id','$user_id','$row1[0]','$answer','$suggestion')";					
				} else { //�ǥͤw��L���ݨ�
					$Q1 = "UPDATE questionary_r SET answer = '$answer', suggestion='$suggestion' WHERE course_id = '$course_id' and student_id = '$user_id'";	
				}
				//var_dump($Q1);
				
				if (!($result = mysql_db_query($DB,$Q1)))
					show_page("not_access.tpl","1��ƮwŪ�����~!!");
				else
					header("Location: http://$SERVER_NAME/php/questionary/assistant_student_show.php");
			} else
				ShowQuestionaries(1); //����
		} else if ($action == 'takequestionary') { //��g�ݨ�
			ShowQuestionaries(0);
		} else {
			$Q1 = "SELECT group_id,name FROM course WHERE a_id='$course_id'";
			if (!($result = mysql_db_query($DB,$Q1)))
				show_page("not_access.tpl","��ƮwŪ�����~!!");
			else {
				$row1 = mysql_fetch_array($result); //$row1[0] = group_id
				$course_name = $row1[1]; //$row1[1] = �ҵ{�W��
			}
						
			$Q1 = "SELECT name FROM course_group WHERE a_id='$row1[0]'";
			if (!($result = mysql_db_query($DB,$Q1)))
				show_page("not_access.tpl","��ƮwŪ�����~!!");
			else
				$row1 = mysql_fetch_array($result); //$row1[0] = �t�ҦW��
			
			$Q1 = "SELECT id,group_name,beg_time,end_time,is_public,is_showname FROM questionary WHERE group_name='$row1[0]'";
			if (!($result = mysql_db_query($DB,$Q1)))
				show_page("not_access.tpl","��ƮwŪ�����~!!");
			else {
				if (!mysql_num_rows($result)) 
					die('�L�t��ݨ����!');
				else {
					$row1 = mysql_fetch_array($result);
					if ($row1[5] == '1') //�O�W
						$is_showname = '<b>�O�W</b>';
					else
						$is_showname = '���O�W';
				}
			}
			
			$Q1 = "SELECT group_name,beg_time,end_time FROM questionary WHERE id = '$row1[0]' and is_public = '1' and end_time > '".date("Y-m-d H:i:s")."'";
			if (!($result = mysql_db_query($DB,$Q1)))
				show_page("not_access.tpl","��ƮwŪ�����~!!");
			else {
				if (!mysql_num_rows($result)) 
					die('�L�ݨ��i��!');
				else
					$row1 = mysql_fetch_array($result);
			}
			
			$Q1 = "SELECT count(*) FROM questionary_r WHERE course_id = '$course_id' and student_id='$user_id'";
			if (!($result_tmp = mysql_db_query($DB,$Q1)))
				show_page("not_access.tpl","��ƮwŪ�����~!!");
			else {
				$row_tmp = mysql_fetch_array($result_tmp);
				if ($row_tmp[0] == 0) {
					$current_status = '<font color="#FF0000">�|����g</font>';
					$current_action = '��g�ݨ�';
				} else {
					$current_status = '<b>�w��g����</b>';
					$current_action = '���s��g';
				}
			}
			
			echo '<br><table border="0" align="center" cellpadding="0" cellspacing="0" width="92%"><tr><td><div align="right"><img src="/images/skin1/bor/bor_01.GIF" width="12" height="11"></div></td><td><div align="center"><img src="/images/skin1/bor/bor_02.GIF" width="100%" height="11"></div></td><td><div align="left"><img src="/images/skin1/bor/bor_03.GIF" width="17" height="11"></div></td></tr><tr><td height=10>';
			
			echo '<div align="right"><img src="/images/skin1/bor/bor_04.GIF" width="12" height="100%"></div></td><td bgcolor="#CCCCCC"><table cellpadding=3 align=center border=0 bordercolorlight="#666666" bordercolordark="#FFFFFF" width="100%" cellspacing="1"><tr bgcolor=#000066 align=center><td colspan="6"><font color="#FFFF00"><b>�t������ݨ��լd</b></font></td></tr><tr bgcolor=#000066 align=center><td width="22%" bgcolor="#000066"><div align="center"><FONT color=#ffffff>�ҵ{�W��</FONT></div></td><td width="19%" bgcolor="#000066"><div align="center"><FONT color=#ffffff>�}�l�ɶ�</font></div></td><td width="18%" bgcolor="#000066"><div align="center"><font color="#FFFFFF">�����ɶ�</font></div></td><td width="11%" bgcolor="#000066"><div align="center"><font color="#ffffff">����</font></div></td><td width="15%" bgcolor="#000066"><div align="center"><font color="#FFFFFF">�ثe���A</font></div></td><td width="14%" bgcolor="#000066"><div align="center"><font color="#FFFFFF">�ʧ@</font></div></td></tr><tr bgcolor="#F0FFEE"><td height="19" align=left><div align="center">'.$course_name.'</div></td><td align=left><div align="center">'.$row1[1].'</div></td><td align=left><div align="center">'.$row1[2].'</div></td><td align=left><div align="center">'.$is_showname.'</div></td><td align=left><div align="center">'.$current_status.'</div></td><td align=left><div align="center"><a href="../questionary/assistantquestionary.php?action=takequestionary">'.$current_action.'</a></div></td></tr></table></td>';
			
			echo '<td height=10><div align="left"><img src="/images/skin1/bor/bor_06.GIF" width="17" height="100%"></div></td></tr><tr><td><div align="right"><img src="/images/skin1/bor/bor_07.GIF" width="12" height="17"></div></td><td><div align="center"><img src="/images/skin1/bor/bor_08.GIF" width="100%" height="17"></div></td><td><div align="left"><img src="/images/skin1/bor/bor_09.GIF" width="17" height="17"></div></td></tr></table><br>';
		}
	} else
		die('�v�����~!');
		
	
	function ShowQuestionaries($err)
	{
		global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$course_id,$message,$user_id;
		
		$Q0 = "SELECT group_id,name FROM course WHERE a_id='$course_id'";
                if (!($result = mysql_db_query($DB,$Q0)))
                	show_page("not_access.tpl","��ƮwŪ�����~!!");
                else {
                	$row0 = mysql_fetch_array($result); //$row0[0] = group_id
                	$course_name = $row0[1]; //$row0[1] = �ҵ{�W��
                }

		$Q1 = "SELECT IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo,IEET_CoreAbilities.content FROM IEEE_CourseIntro_CoreAbilities,IEET_ClassGoal,IEET_CoreAbilities WHERE IEEE_CourseIntro_CoreAbilities.course_id = '$course_id' and IEEE_CourseIntro_CoreAbilities.isChecked = '1' and IEEE_CourseIntro_CoreAbilities.ClassGoal_Index = IEET_ClassGoal.ClassGoal_Index and IEEE_CourseIntro_CoreAbilities.CoreAbilities_Index = IEET_CoreAbilities.CoreAbilities_Index ORDER BY IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo";
		
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","��ƮwŪ�����~!!");
		else {
			$r_count = mysql_num_rows($result);
			if (!$r_count) die('�����ҦѮv�|���Ŀ�ݨ��D��!');
		}
	
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml">';
		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=big5" />';
		echo '<title>��g�ݨ�</title>';
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
		echo '<p>�ҵ{�W��: <b>'.$course_name.'</b></p>'; //display �ҵ{�W��
		echo '<b>�U��P�Ǧn�A�Ш̷ӦѮv�Ҷ�g�ҵ{����i�H���i�A���U���֤߯�O�������ӵ��q�A�O�_�q�ײߥ��ҵ{�����i���������֤߯�O�C�Ҧp��1.1 ��T�u�{������¦���Ѥ��l���P�F�Ѫ���O���O�_�̦Ѯv��w�p����k�A�q�ײ߳o���Ҧӯ�R���i���Ѯv�w�����ؼСC</b><br><br>';
		echo '<form method=POST action=assistantquestionary.php>';
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

		
		for ($i = 1;$i <= $r_count; $i++) {
			$row1 = mysql_fetch_array($result);
			
			$currentsel = 'selection_'.$i;				
			global $$currentsel;
			
			if ($i % 2 == 1) 
				echo '  <tr onmouseover="setPointer(this,\'#E8FCDA\')" onmouseout="setPointer(this,\'\')">';
			else
				echo '  <tr bgcolor="#CCCCCC" onmouseover="setPointer(this,\'#E8FCDA\')" onmouseout="setPointer(this,\'#CCCCCC\')">';
			echo '    <td height="35"><b>'.$row1[0].'.'.$row1[1].'</b> '.$row1[2].'</td>';
			echo '    <td><div align="center">';
			if ($$currentsel == 'A')
				echo '      <input type="radio" name="selection_'.$i.'" value="A" checked />';
			else
				echo '      <input type="radio" name="selection_'.$i.'" value="A" />';
			echo '    </div></td>';
			echo '    <td><div align="center">';
			if ($$currentsel == 'B')
				echo '      <input type="radio" name="selection_'.$i.'" value="B" checked/>';
			else
				echo '      <input type="radio" name="selection_'.$i.'" value="B" />';
			echo '    </div></td>';
			echo '    <td><div align="center">';
			if ($$currentsel == 'C')
				echo '      <input type="radio" name="selection_'.$i.'" value="C" checked/>';
			else
				echo '      <input type="radio" name="selection_'.$i.'" value="C" />';
			echo '    </div></td>';
			echo '    <td><div align="center">';
			if ($$currentsel == 'D')
				echo '      <input type="radio" name="selection_'.$i.'" value="D" checked/>';
			else
				echo '      <input type="radio" name="selection_'.$i.'" value="D" />';
			echo '    </div></td>';
			echo '    <td><div align="center">';
			if ($$currentsel == 'E')
				echo '      <input type="radio" name="selection_'.$i.'" value="E" checked/>';
			else
				echo '      <input type="radio" name="selection_'.$i.'" value="E" />';
			echo '    </div></td>';
			echo '  </tr>';
		}			

		//20100317 �[�W��ĳ���
		echo '<tr><td><br><br></td></tr>';
		echo '<tr><td><font color="blue">��o��Ҫ��߱o�P��ĳ</font></td></tr>';
		echo '<tr><td><textarea name="suggestion" ROWS=10 COLS=100 /></textarea></td></tr>';
		//	
		echo '</table>';
		echo '<input type=hidden name=q_count value='.$r_count.'>';
		echo '<input type=hidden name=action value=completequestionary>';
		echo '<br><p><input type=submit value=ú��ݨ�> <input type=submit name=cancel value=������g></p>';
		if ($err == 1) echo '<p><font color=red>�нT��^���Ҧ����D!!!</color></p>';
		echo '</form>';
		echo '</body>';
		echo '</html>';
	}
	 function Fix_Backslash($org_str) {
      if ( mysql_client_encoding() != "big5" ) return $org_str;

      $tmp_length = strlen($org_str);

      for ( $tmp_i=0; $tmp_i<$tmp_length; $tmp_i++ ) {
        $ascii_str_a = substr($org_str, $tmp_i , 1);
        $ascii_str_b = substr($org_str, $tmp_i+1, 1);

        $ascii_value_a = ord($ascii_str_a);
        $ascii_value_b = ord($ascii_str_b);

        if ( $ascii_value_a > 128 ) {
          if ( $ascii_value_b == 92 ) {
            $org_str = substr($org_str, 0, $tmp_i+2) . substr($org_str,$tmp_i+3);
            $tmp_length = strlen($org_str);
          }
          $tmp_i++;
        }
      }

      $tmp_length = strlen($org_str);
      if ( substr($org_str, ($tmp_length-1), 1) == "\\" ) $org_str .= chr(32);

      $org_str = str_replace("\\0", "\ 0", $org_str);
      return $org_str;
    }	
?>

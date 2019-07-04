<?php
	/* ------------------------------------------------------ */
	/* assistantquestionary.php                               */
	/* Written by carlyle.                                    */
	/* ------------------------------------------------------ */
	
	require 'fadmin.php';
	update_status ("線上問卷中");
	
	if (isset($PHPSESSID) && (session_check_teach($PHPSESSID)))
	{
		global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$course_id,$message,$user_id;

	 	if ($course_id == '' || $course_id == -1)
			die('Invalid <b>course_id</b> !');
			
		if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD))) {
			show_page("not_access.tpl","資料庫連結錯誤!!");
			exit;
		}
		
		if ($cancel != '') header("Location: http://$SERVER_NAME/php/news/news.php"); //取消填寫問卷
			
		if ($action == 'completequestionary') { //繳交問卷
			/* 檢查是否所有問題都有回答 */
			$answer = ''; //存學生的答案
			$j = 0;
			for ($i = 1;$i <= $q_count;$i++) {
				$tmp = 'selection_'.$i;
				if ($$tmp == '') {
					$j = 1;
					break;
				} else
					$answer = $answer.$$tmp;
			}
			
			if ($j != 1) { //填寫結果存入database
				$Q1 = "SELECT count(*) FROM questionary_r WHERE course_id = '$course_id' and student_id='$user_id'";
				if (!($result = mysql_db_query($DB,$Q1)))
					show_page("not_access.tpl","資料庫讀取錯誤!!");
				else
					$row1 = mysql_fetch_array($result);
					
				if ($row1[0] == 0) { //學生未填過此問卷
					$Q1 = "SELECT id FROM questionary_r ORDER BY id DESC LIMIT 0, 1"; 
					if (!($result_tmp = mysql_db_query($DB,$Q1)))
						show_page("not_access.tpl","資料庫讀取錯誤!!");
					else {
						$row_tmp = mysql_fetch_array($result_tmp);
						$lastid = $row_tmp[0] + 1; //get last id
					}
							
					$Q1 = "SELECT name FROM user WHERE id='$user_id'";
					if (!($result = mysql_db_query($DB,$Q1)))
						show_page("not_access.tpl","資料庫讀取錯誤!!");
					else
						$row1 = mysql_fetch_array($result); //$row1[0] = 學生名稱
					
					
					//chiefboy1230@20120109，解決許功蓋的問題。
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
				} else { //學生已填過此問卷
					$Q1 = "UPDATE questionary_r SET answer = '$answer', suggestion='$suggestion' WHERE course_id = '$course_id' and student_id = '$user_id'";	
				}
				//var_dump($Q1);
				
				if (!($result = mysql_db_query($DB,$Q1)))
					show_page("not_access.tpl","1資料庫讀取錯誤!!");
				else
					header("Location: http://$SERVER_NAME/php/questionary/assistant_student_show.php");
			} else
				ShowQuestionaries(1); //重填
		} else if ($action == 'takequestionary') { //填寫問卷
			ShowQuestionaries(0);
		} else {
			$Q1 = "SELECT group_id,name FROM course WHERE a_id='$course_id'";
			if (!($result = mysql_db_query($DB,$Q1)))
				show_page("not_access.tpl","資料庫讀取錯誤!!");
			else {
				$row1 = mysql_fetch_array($result); //$row1[0] = group_id
				$course_name = $row1[1]; //$row1[1] = 課程名稱
			}
						
			$Q1 = "SELECT name FROM course_group WHERE a_id='$row1[0]'";
			if (!($result = mysql_db_query($DB,$Q1)))
				show_page("not_access.tpl","資料庫讀取錯誤!!");
			else
				$row1 = mysql_fetch_array($result); //$row1[0] = 系所名稱
			
			$Q1 = "SELECT id,group_name,beg_time,end_time,is_public,is_showname FROM questionary WHERE group_name='$row1[0]'";
			if (!($result = mysql_db_query($DB,$Q1)))
				show_page("not_access.tpl","資料庫讀取錯誤!!");
			else {
				if (!mysql_num_rows($result)) 
					die('無系辦問卷資料!');
				else {
					$row1 = mysql_fetch_array($result);
					if ($row1[5] == '1') //記名
						$is_showname = '<b>記名</b>';
					else
						$is_showname = '不記名';
				}
			}
			
			$Q1 = "SELECT group_name,beg_time,end_time FROM questionary WHERE id = '$row1[0]' and is_public = '1' and end_time > '".date("Y-m-d H:i:s")."'";
			if (!($result = mysql_db_query($DB,$Q1)))
				show_page("not_access.tpl","資料庫讀取錯誤!!");
			else {
				if (!mysql_num_rows($result)) 
					die('無問卷可填!');
				else
					$row1 = mysql_fetch_array($result);
			}
			
			$Q1 = "SELECT count(*) FROM questionary_r WHERE course_id = '$course_id' and student_id='$user_id'";
			if (!($result_tmp = mysql_db_query($DB,$Q1)))
				show_page("not_access.tpl","資料庫讀取錯誤!!");
			else {
				$row_tmp = mysql_fetch_array($result_tmp);
				if ($row_tmp[0] == 0) {
					$current_status = '<font color="#FF0000">尚未填寫</font>';
					$current_action = '填寫問卷';
				} else {
					$current_status = '<b>已填寫完畢</b>';
					$current_action = '重新填寫';
				}
			}
			
			echo '<br><table border="0" align="center" cellpadding="0" cellspacing="0" width="92%"><tr><td><div align="right"><img src="/images/skin1/bor/bor_01.GIF" width="12" height="11"></div></td><td><div align="center"><img src="/images/skin1/bor/bor_02.GIF" width="100%" height="11"></div></td><td><div align="left"><img src="/images/skin1/bor/bor_03.GIF" width="17" height="11"></div></td></tr><tr><td height=10>';
			
			echo '<div align="right"><img src="/images/skin1/bor/bor_04.GIF" width="12" height="100%"></div></td><td bgcolor="#CCCCCC"><table cellpadding=3 align=center border=0 bordercolorlight="#666666" bordercolordark="#FFFFFF" width="100%" cellspacing="1"><tr bgcolor=#000066 align=center><td colspan="6"><font color="#FFFF00"><b>系辦期末問卷調查</b></font></td></tr><tr bgcolor=#000066 align=center><td width="22%" bgcolor="#000066"><div align="center"><FONT color=#ffffff>課程名稱</FONT></div></td><td width="19%" bgcolor="#000066"><div align="center"><FONT color=#ffffff>開始時間</font></div></td><td width="18%" bgcolor="#000066"><div align="center"><font color="#FFFFFF">結束時間</font></div></td><td width="11%" bgcolor="#000066"><div align="center"><font color="#ffffff">類型</font></div></td><td width="15%" bgcolor="#000066"><div align="center"><font color="#FFFFFF">目前狀態</font></div></td><td width="14%" bgcolor="#000066"><div align="center"><font color="#FFFFFF">動作</font></div></td></tr><tr bgcolor="#F0FFEE"><td height="19" align=left><div align="center">'.$course_name.'</div></td><td align=left><div align="center">'.$row1[1].'</div></td><td align=left><div align="center">'.$row1[2].'</div></td><td align=left><div align="center">'.$is_showname.'</div></td><td align=left><div align="center">'.$current_status.'</div></td><td align=left><div align="center"><a href="../questionary/assistantquestionary.php?action=takequestionary">'.$current_action.'</a></div></td></tr></table></td>';
			
			echo '<td height=10><div align="left"><img src="/images/skin1/bor/bor_06.GIF" width="17" height="100%"></div></td></tr><tr><td><div align="right"><img src="/images/skin1/bor/bor_07.GIF" width="12" height="17"></div></td><td><div align="center"><img src="/images/skin1/bor/bor_08.GIF" width="100%" height="17"></div></td><td><div align="left"><img src="/images/skin1/bor/bor_09.GIF" width="17" height="17"></div></td></tr></table><br>';
		}
	} else
		die('權限錯誤!');
		
	
	function ShowQuestionaries($err)
	{
		global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$course_id,$message,$user_id;
		
		$Q0 = "SELECT group_id,name FROM course WHERE a_id='$course_id'";
                if (!($result = mysql_db_query($DB,$Q0)))
                	show_page("not_access.tpl","資料庫讀取錯誤!!");
                else {
                	$row0 = mysql_fetch_array($result); //$row0[0] = group_id
                	$course_name = $row0[1]; //$row0[1] = 課程名稱
                }

		$Q1 = "SELECT IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo,IEET_CoreAbilities.content FROM IEEE_CourseIntro_CoreAbilities,IEET_ClassGoal,IEET_CoreAbilities WHERE IEEE_CourseIntro_CoreAbilities.course_id = '$course_id' and IEEE_CourseIntro_CoreAbilities.isChecked = '1' and IEEE_CourseIntro_CoreAbilities.ClassGoal_Index = IEET_ClassGoal.ClassGoal_Index and IEEE_CourseIntro_CoreAbilities.CoreAbilities_Index = IEET_CoreAbilities.CoreAbilities_Index ORDER BY IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo";
		
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		else {
			$r_count = mysql_num_rows($result);
			if (!$r_count) die('此門課老師尚未勾選問卷題目!');
		}
	
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml">';
		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=big5" />';
		echo '<title>填寫問卷</title>';
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
		echo '<p>課程名稱: <b>'.$course_name.'</b></p>'; //display 課程名稱
		echo '<b>各位同學好，請依照老師所填寫課程為何可以培養你的各項核心能力的說明來評量你是否從修習本課程中有養成相關的核心能力。例如”1.1 資訊工程相關基礎知識之吸收與了解的能力”是否依老師原預計的方法，從修習這門課而能充分養成老師預期的目標。</b><br><br>';
		echo '<form method=POST action=assistantquestionary.php>';
		echo '<table width="99%" border="0">';
		echo '  <tr>';
		echo '    <td width="42%" rowspan="2" bgcolor="#4d6eb2"><div align="center" class="style3">問卷題目</div></td>';
		echo '    <td height="27" colspan="5" bgcolor="#4d6eb2"><div align="center" class="style3">選項</div></td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td width="11%" height="24" bgcolor="#4d6eb2"><div align="center" class="style1">A: 充分養成</div></td>';
		echo '    <td width="14%" bgcolor="#4d6eb2"><div align="center" class="style1">B: 絕大部分養成</div></td>';
		echo '    <td width="10%" bgcolor="#4d6eb2"><div align="center" class="style1">C: 部份養成</div></td>';
		echo '    <td width="13%" bgcolor="#4d6eb2"><div align="center" class="style1">D: 絕大部分未養成</div></td>';
		echo '    <td width="10%" bgcolor="#4d6eb2"><div align="center" class="style1">E: 完全未養成</div></td>';
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

		//20100317 加上建議欄位
		echo '<tr><td><br><br></td></tr>';
		echo '<tr><td><font color="blue">對這堂課的心得與建議</font></td></tr>';
		echo '<tr><td><textarea name="suggestion" ROWS=10 COLS=100 /></textarea></td></tr>';
		//	
		echo '</table>';
		echo '<input type=hidden name=q_count value='.$r_count.'>';
		echo '<input type=hidden name=action value=completequestionary>';
		echo '<br><p><input type=submit value=繳交問卷> <input type=submit name=cancel value=取消填寫></p>';
		if ($err == 1) echo '<p><font color=red>請確實回答所有問題!!!</color></p>';
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

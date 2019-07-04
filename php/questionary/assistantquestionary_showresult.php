<?php
	/* ------------------------------------------------------ */
	/* assistantquestionary_showresult.php                    */
	/* Written by carlyle.                                    */
	/* Modify by ghost777.                                    */
	/* ------------------------------------------------------ */
	
	require 'fadmin.php';
	update_status ("觀看問卷結果");
			
	if (isset($PHPSESSID) && session_check_stu($PHPSESSID) ) //修改進來的身分
	{
		global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$user_id;
		if (!($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)))
			show_page("not_access.tpl","資料庫連結錯誤!!");
		
		$Q1 = "SELECT group_id FROM course WHERE a_id = '$courseid'"; 
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		else{
			$row1 = mysql_fetch_array($result);
		}
			
		$Q1 = "SELECT name FROM course_group WHERE a_id = '$row1[0]'"; 
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		else
			$row1 = mysql_fetch_array($result);
		
		$Q1 = "SELECT is_showname FROM questionary WHERE group_name = '$row1[0]'"; 
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		else {
			if (!mysql_num_rows($result)) 
				die('系辦尚未發佈問卷!!');
			else {
				$row1 = mysql_fetch_array($result);
				$is_showname = $row1[0]; //是否記名
			}
		}	
				
		if ($action == 'showdetail') {
			ShowDetail();
		} else {
			if ($is_showname == '0' || $showstatistics == '1') //不記名 or 記名時選擇觀看整體統計資料
				ShowAnonymousResult();
			else { //記名
				echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=big5" />
		<title>學生列表</title>';
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
					show_page("not_access.tpl","資料庫讀取錯誤!!");
				else {
					if (!mysql_num_rows($result)) 
						die('找不到courseid對應的課程名稱!!!');
					else
						$row1 = mysql_fetch_array($result);
				}
				
				$Q1 = "SELECT student_id FROM take_course WHERE course_id = '$courseid' and year='$year' and term = '$term'"; 
				if (!($result = mysql_db_query($DB,$Q1)))
					show_page("not_access.tpl","資料庫讀取錯誤!!");
				else {
					$student_count = mysql_num_rows($result); //此門課的學生總數
					if (!$student_count) die('此課程尚未有學生!!!');
				}
				
				echo '<p>課程名稱: <b>'.$row1[0].'</b></p>'; //display 課程名稱
				echo '<b>各位同學好，請依照老師所填寫課程為何可以培養你的各項核心能力的說明來評量你是否從修習本課程中有養成相關的核心能力。例如”1.1 資訊工程相關基礎知識之吸收與了解的能力”是否依老師原預計的方法，從修習這門課而能充分養成老師預期的目標。</b><br><br>';
				echo '<table width="509" border=1 bordercolor=#9FAE9D>
		<tr><td width="499"><Table border=0><tr bgcolor="#4d6eb2"><td width="161"><div align="center"><font color="#FFFFFF">學號</font></div></td><td width="166"><div align="center"><font color="#FFFFFF">姓名</font></div></td><td width="170"><div align="center"><font color="#FFFFFF">問卷結果</font></div></td></tr>';
				
				$total_ready = 0; //已填寫問卷的人數
				$total_unready = 0; //尚未填寫問卷的人數
				for ($i = 1;$i <= $student_count;$i++) {
					$row1 = mysql_fetch_array($result); //$row1[0] = student's a_id
					
					$Q2 = "SELECT id,name FROM user WHERE a_id = '$row1[0]'"; 
					if (!($result2 = mysql_db_query($DB,$Q2)))
						show_page("not_access.tpl","資料庫讀取錯誤!!");
					else { 
						if (!mysql_num_rows($result2)) continue; //這個學生被刪除了?? 不管他
						$row2 = mysql_fetch_array($result2);
						$student_id = $row2[0];
						$student_name = $row2[1];
					}
				
					$Q2 = "SELECT count(*) FROM questionary_r WHERE student_id = '$student_id' and student_name = '$student_name' and course_id = '$courseid'"; 
					if (!($result2 = mysql_db_query($DB,$Q2)))
						show_page("not_access.tpl","資料庫讀取錯誤!!");
					else { 
						$row2 = mysql_fetch_array($result2);
						if ($row2[0] == 0) {
							$total_unready++; //未填寫的人數加一
							if ($is_showname == '0') continue; //若是不記名,尚未填寫問卷的就不列出
							$current_status = '<font color=red>尚未填寫</font>';
						} else {
							$total_ready++; //已填寫的人數加一
							$current_status = '<a href="assistantquestionary_showresult.php?action=showdetail&courseid='.$courseid.'&studentid='.$student_id.'&year='.$year.'&term='.$term.'">觀看結果</a>';
						}
					}
					
					echo '<tr onmouseover="setPointer(this,\'#C6E6DE\')" onmouseout="setPointer(this,\'#BFCEBD\')"><td height="23" bordercolor="#000000" bgcolor="#BFCEBD"><div align="center">'.$student_id.'</div></td><td bordercolor="#000000" bgcolor="#BFCEBD"><div align="center">'.$student_name.'</div></td><td bordercolor="#000000" bgcolor="#BFCEBD"><div align="center">'.$current_status.'</div></td></tr>';
				}
	
				echo '</table></td></tr></table><br><br>';
				echo '<a href="assistantquestionary_showresult.php?courseid='.$courseid.'&showstatistics=1&year='.$year.'&term='.$term.'" target="_blank">整體問卷結果統計</a><br><br>';
				echo '*已填寫問卷人數: '.$total_ready.'<br>';
				echo '&nbsp;未填寫問卷人數: <font color="#FF0000">'.$total_unready.'</font><br>';
				echo '</body></html>';
			}
		}
	}

	/* 不記名 or 記名時選擇觀看整體統計資料 */
	function ShowAnonymousResult()
	{
		global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$courseid,$message,$user_id,$year,$term;
		$group_id = Get_group_id($courseid); //取得此門課的group_id
		
		/* 取出課程名稱 */
		$Q1 = "SELECT name FROM course WHERE a_id = '$courseid'"; 
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		else {
			$row1 = mysql_fetch_array($result);
			$course_name = $row1['name']; //這門課的名稱
		}
		
		/* 檢查這堂課是否有學生 */
		$Q1 = "SELECT student_id FROM take_course WHERE course_id = '$courseid' and year='$year' and term = '$term'"; 
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		else {
			$student_count = mysql_num_rows($result); //這門課的學生總數
			if (!$student_count) die('此課程尚未有學生!!');
		}
		
		PrintDetailPageHeader('課程名稱: <b>'.$course_name.'</b><br><br>');
		
		/* 取出這門課的問卷題目 */
		$Q1 = "SELECT IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo,IEET_CoreAbilities.content FROM IEEE_CourseIntro_CoreAbilities,IEET_ClassGoal,IEET_CoreAbilities WHERE IEEE_CourseIntro_CoreAbilities.course_id = '$courseid' and IEEE_CourseIntro_CoreAbilities.isChecked = '1' and IEEE_CourseIntro_CoreAbilities.ClassGoal_Index = IEET_ClassGoal.ClassGoal_Index and IEEE_CourseIntro_CoreAbilities.CoreAbilities_Index = IEET_CoreAbilities.CoreAbilities_Index ORDER BY IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo";
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		else {
			$q_count = mysql_num_rows($result);
			if (!$q_count) die('此課程尚未設定問卷內容!');
		}

		/* 取出這個課程所有學生的答案 */
		//$Q1 = "SELECT student_name,answer FROM questionary_r WHERE course_id = '$courseid'";
		$Q1="SELECT u.id, u.name, q.answer FROM take_course as t, user as u, questionary_r as q WHERE t.course_id='".$courseid."' and t.year='".$year."' and t.term='".$term."' and t.student_id=u.a_id and u.id=q.student_id and q.course_id='".$courseid."' order by q.student_id";

		if (!($result2 = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		else {
			$r_count = mysql_num_rows($result2); //所有已填問卷的學生總數
		}
		/* 逐題檢查各個選項的回答人數 */
		for ($q_index=0;$q_index<$q_count;$q_index++) {
			$row_q = mysql_fetch_array($result); //$row_q[2] = 這題的題目
			
			$select_A_count = 0; //這題選A的人數
			$select_B_count = 0; //這題選B的人數
			$select_C_count = 0; //這題選C的人數
			$select_D_count = 0; //這題選D的人數
			$select_E_count = 0; //這題選E的人數
			
			/* 計算這題的五個選項各有幾個人選 */
			if($r_count!=0){ //如果回答問卷人數不是0人才做統計
				mysql_data_seek($result2,0); //將pointer移回第一列
				for ($sr=0;$sr<$r_count;$sr++) {
					$row1 = mysql_fetch_array($result2);
					$tmpans = substr($row1['answer'],$q_index,1); //取出這個學生的這一題的答案
					
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
		//由questionary_r這個table中取出學生建議
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
        	        $stu_sug="目前無任何心得與建議";
        	}
                echo '<table><tr><td><br><br></td></tr>';
                echo '<tr><td><font color="blue">對這堂課的心得與建議</font></td></tr>';
                echo '<tr><td><textarea name="suggestion" ROWS=10 COLS=100 />'.$stu_sug.'</textarea></td></tr></table>';

		echo '<br><br>';
		echo '*已填寫問卷人數: '.$r_count.'<br>';
		echo '&nbsp;未填寫問卷人數: <font color="#FF0000">'.($student_count - $r_count).'</font><br>';
		echo '</body>';
		echo '</html>';
	}
	
	/* 記名投票時,顯示某位學生的問卷結果 */	
	function ShowDetail()
	{
		global $DB_SERVER,$DB_LOGIN,$DB,$DB_PASSWORD,$courseid,$studentid,$message,$user_id;
		
		$Q1 = "SELECT student_name,answer FROM questionary_r WHERE course_id = '$courseid' and student_id='$studentid'";
		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		else
			$row1 = mysql_fetch_array($result);
			
		$student_name = $row1[0]; //學生姓名
		$student_answer = $row1[1]; //學生的問卷答案

		$Q1 = "SELECT IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo,IEET_CoreAbilities.content FROM IEEE_CourseIntro_CoreAbilities,IEET_ClassGoal,IEET_CoreAbilities WHERE IEEE_CourseIntro_CoreAbilities.course_id = '$courseid' and IEEE_CourseIntro_CoreAbilities.isChecked = '1' and IEEE_CourseIntro_CoreAbilities.ClassGoal_Index = IEET_ClassGoal.ClassGoal_Index and IEEE_CourseIntro_CoreAbilities.CoreAbilities_Index = IEET_CoreAbilities.CoreAbilities_Index ORDER BY IEET_ClassGoal.ClassGoalNo,IEET_CoreAbilities.CoreAbilitiesNo";

		if (!($result = mysql_db_query($DB,$Q1)))
			show_page("not_access.tpl","資料庫讀取錯誤!!");
		else {
			$r_count = mysql_num_rows($result);
			if (!$r_count) die('此課程尚未設定問卷內容!');
		}

		PrintDetailPageHeader('學生姓名: <b>'.$student_name.'</b><br><br>');
	
		for ($i = 1;$i <= $r_count; $i++) {			
			$row1 = mysql_fetch_array($result);
			
			if ($i > strlen($student_answer)) break; //這行若成立,就是有問題了!!!
			$tmpans = substr($student_answer,($i-1),1); //取出某一題的答案
			
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
		echo '<br><p><input type=button value=回學生列表 onclick="javascript:history.back()"></p>';
		echo '</body>';
		echo '</html>';
	}
	
	function PrintDetailPageHeader($title)
	{
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml">';
		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=big5" />';
		echo '<title>觀看問卷結果</title>';
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
		echo '<b>各位同學好，請依照老師所填寫課程為何可以培養你的各項核心能力的說明來評量你是否從修習本課程中有養成相關的核心能力。例如”1.1 資訊工程相關基礎知識之吸收與了解的能力”是否依老師原預計的方法，從修習這門課而能充分養成老師預期的目標。</b><br><br>';
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
	}

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

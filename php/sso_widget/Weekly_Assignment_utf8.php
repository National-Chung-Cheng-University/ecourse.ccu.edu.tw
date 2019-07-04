<body>
<table width="200" border="0" cellspacing="2" cellpadding="0" align="center">  
  <tr>
  
  <td>
    <a id="assignment" class="menu" href="#" onclick="javascript:ShowContent('hw_1_1')" style="background-color=yellow">
	<img src="http://cih.elearning.ccu.edu.tw/php/sso_widget/icon/homework.png" style="max-height: 24px; max-width: 24px"  />
    作業</a>
  </td>			
  <td>
    <a id="exam" class="menu" href="#" onclick="javascript:ShowContent('hw_2_1')">
	<img src="http://cih.elearning.ccu.edu.tw/php/sso_widget/icon/test.png"  style="max-height: 24px; max-width: 24px" />
	測驗</a>
  </td>
  <td>
    <a id="questionary" class="menu" href="#" onclick="javascript:ShowContent('hw_3_1')">
    <img src="http://cih.elearning.ccu.edu.tw/php/sso_widget/icon/ask.png"  style="max-height: 24px; max-width: 24px" />
    問卷</a>
  </td>
  </tr>
  <tr>
  <td>
    <a id="news" class="menu" href="#" onclick="javascript:ShowContent('hw_4_1')">
	<img src="http://cih.elearning.ccu.edu.tw/php/sso_widget/icon/a1.png"  style="max-height: 24px; max-width: 24px" />
    公告</a>
  </td>
  <td>
    <a id="textbook" class="menu" href="#" onclick="javascript:ShowContent('hw_5_1')">
	<img src="http://cih.elearning.ccu.edu.tw/php/sso_widget/icon/Book.png"  style="max-height: 24px; max-width: 24px" />
    教材</a>
  </td>
  <td>  
    <a id="score" class="menu" href="#" onclick="javascript:ShowContent('hw_6_1')">
	<img src="http://cih.elearning.ccu.edu.tw/php/sso_widget/icon/Search.png"  style="max-height: 24px; max-width: 24px" />
    成績</a>
  </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
<?php
require 'fadmin.php';

//初始化
define("DEBUG", false);
define("WIDTH", '90%');
$sso_ip = array('140.123.4.205', '140.123.4.210', '140.123.4.10', '140.123.29.235', '140.123.4.217', '140.123.19.217');

if(DEBUG)
{
	$today = '2012-11-26';
	$now_time = '2012-11-26 00:00:00';
	$id = '601410024';
	$year = '101';
	$term = '1';
}
else
{	
	$is_sso = FALSE;
	foreach($sso_ip as $index => $ip)
	{		
		if($_SERVER['REMOTE_ADDR'] == $ip)
		{
			$is_sso = TRUE;			
		}
	}
	
	if(!$is_sso)
	{
		die("請透過中正SSO連線");
	}
	
	$today = date("Y-m-d");
	$now_time = date('Y-m-d H:i:s');
	//接收SSO所傳遞過來的學號
	$id = $_GET['acc'];	
	
	$Q1 = "SELECT year, term from this_semester";

	if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) )
	{
		die("資料庫查詢錯誤!!");
	}

	if(mysql_num_rows($result1) != 0)
	{
		$row1 = mysql_fetch_assoc($result1);
		$year = $row1['year'];
		$term = $row1['term'];
	}	
}


if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
{
	die("資料庫連結錯誤!!");
}

$Q1 = "SELECT a_id from user where id LIKE '$id'";

if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) )
{
	die("資料庫查詢錯誤!!");
}

if(mysql_num_rows($result1) != 0)
{
	$row1 = mysql_fetch_assoc($result1);
	$user_id = $row1['a_id'];
}
else
{
	die("您在e-course平台上沒有資料");
}

$sql = "SELECT course_id from take_course where student_id = '$user_id' and year = '$year' and term = '$term'";
//echo $user_id . $year . $term;

if ( !($res = mysql_db_query( $DB, $sql ) ) )
{
	die("資料庫查詢錯誤!!");
}
	
$course_counter = mysql_num_rows($res);

if($course_counter != 0)
{	
	while ( $row = mysql_fetch_assoc($res) )
	{		
		$course_id = $row['course_id'];
				
		//課程
		$Q1 = "SELECT name from course where a_id = '$course_id'";

		if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) )
		{
			die("資料庫查詢錯誤!!");
		}
		$row1 = mysql_fetch_assoc($result1);
		$course_name = iconv("big5","UTF-8",$row1['name']);
				
		
		//作業
		$Q2 = "SELECT a_id, name, due FROM homework WHERE due >= '$today' AND (public='1' OR public='3') ORDER BY chap_num, a_id";
		//echo $Q2;
		
				
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) )
		{
			die("資料庫查詢錯誤!!");
		}
		
		if(mysql_num_rows($result2) != 0)
		{
			while($row2 = mysql_fetch_assoc($result2))
			{
				$homework_id = $row2['a_id'];
				$assignment_name = iconv("big5","UTF-8",$row2['name']);
				$due = $row2['due'];
									
				$Q3 = "SELECT upload FROM handin_homework WHERE homework_id = '$homework_id' AND student_id = '$user_id'";
				//echo $Q3;
				if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) )
				{
					die("資料庫查詢錯誤!!");
				}
				
				if(mysql_num_rows($result3) != 0)
				{
					$row3 = mysql_fetch_assoc($result3);
					
					if($row3['upload'] == 0)
					{
						$assignment_contents[] = "<tr><td>$course_name</td><td>$assignment_name</td><td>$due</td></tr>";						
					}								
				}				
			}		
		}
		if(count($assignment_contents) == 0)
		{
			$has_assignment = 'n';			
		}
		else
		{
			$has_assignment = 'y';			
		}
		
		
		//測驗
		$Q3 = "SELECT a_id, name, end_time FROM exam WHERE beg_time <= '$now_time' AND end_time >= '$now_time' AND (public='1' OR public='3') ORDER BY chap_num, a_id";
		//echo $Q3;
				
		if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) )
		{
			die("資料庫查詢錯誤!!");
		}
		
		if(mysql_num_rows($result3) != 0)
		{
			while($row3 = mysql_fetch_assoc($result3))
			{
				$exam_id = $row3['a_id'];
				$exam_name = iconv("big5","UTF-8",$row3['name']);
				$exam_end_time = $row3['end_time'];
				
					
				$Q4 = "SELECT grade FROM take_exam WHERE exam_id = '$exam_id' AND student_id = '$user_id'";
				
				if ( !($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) )
				{
					die("資料庫查詢錯誤!!");
				}
				
				if(mysql_num_rows($result3) != 0)
				{
					$row4 = mysql_fetch_assoc($result4);
					
					if($row4['grade'] == -1)
					{
						$exam_year = substr($row3['end_time'], 0, 4);
						$exam_month = substr($row3['end_time'], 5, 2);
						$exam_day = substr($row3['end_time'], 8, 2);
						$exam_hour = substr($row3['end_time'], 11, 2);
						$exam_minute = substr($row3['end_time'], 14, 2);
						$exam_second = substr($row3['end_time'], 17, 2);
						$exam_end_time = $exam_year . '-' . 
											$exam_month . '-' . 
											$exam_day . ' ' . 
											$exam_hour . ':' . 
											$exam_minute . ':' . 
											$exam_second;
						$exam_contents[] = "<tr><td>$course_name</td><td>$exam_name</td><td>$exam_end_time</td></tr>";						
					}								
				}				
			}		
		}
		if(count($exam_contents) == 0)
		{
			$has_exam = 'n';			
		}
		else
		{
			$has_exam = 'y';			
		}
		
		
		//問卷
		$Q5 = "SELECT a_id, name, end_time FROM questionary WHERE beg_time <= '$now_time' AND end_time >= '$now_time' AND (public='1' OR public='3') ORDER BY a_id";
		//echo $Q5;
				
		if ( !($result5 = mysql_db_query( $DB.$course_id, $Q5 ) ) )
		{
			die("資料庫查詢錯誤!!");
		}
		
		if(mysql_num_rows($result5) != 0)
		{
			while($row5 = mysql_fetch_assoc($result5))
			{
				$questionary_id = $row5['a_id'];
				$questionary_name = iconv("big5","UTF-8",$row5['name']);
				$questionary_end_time = $row5['end_time'];
				
					
				$Q6 = "SELECT count FROM take_questionary WHERE q_id = '$questionary_id' AND student_id = '$user_id'";
				//echo $Q6;
				if ( !($result6 = mysql_db_query( $DB.$course_id, $Q6 ) ) )
				{
					die("資料庫查詢錯誤!!");
				}
				
				$row6 = mysql_fetch_assoc($result6);
				if($row6['count'] == 0)
				{
					$questionary_year = substr($row5['end_time'], 0, 4);
						$questionary_month = substr($row5['end_time'], 5, 2);
						$questionary_day = substr($row5['end_time'], 8, 2);
						$questionary_hour = substr($row5['end_time'], 11, 2);
						$questionary_minute = substr($row5['end_time'], 14, 2);
						$questionary_second = substr($row5['end_time'], 17, 2);
						$questionary_end_time = $questionary_year . '-' . 
											$questionary_month . '-' . 
											$questionary_day . ' ' . 
											$questionary_hour . ':' . 
											$questionary_minute . ':' . 
											$questionary_second;
					$questionary_contents[] = "<tr><td>$course_name</td><td>$questionary_name</td><td>$questionary_end_time</td></tr>";						
											
				}
			}		
		}
		if(count($questionary_contents) == 0)
		{
			$has_questionary = 'n';			
		}
		else
		{
			$has_questionary = 'y';			
		}
		
		
		//公告
		//$Q7 = "SELECT n.a_id, n.subject, n.content FROM news n, log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '0' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."' order by n.begin_day DESC";
		$twoweeksago = date('Y-m-d', strtotime("-2 weeks"));
		$Q7 = "SELECT n.a_id, n.begin_day, n.subject, n.content FROM news n, log l where l.tag1 = n.a_id and l.event_id = '8' and n.system = '0' and n.begin_day <= '".date("Y-m-d")."' and n.end_day >= '".date("Y-m-d")."' and n.begin_day >= '".$twoweeksago."' order by n.begin_day DESC";
		
		//echo $Q7;
			
		if ( !($result7 = mysql_db_query( $DB.$course_id, $Q7 ) ) )
		{
			die("資料庫查詢錯誤!!");
		}
		
		if(mysql_num_rows($result7) != 0)
		{
			while($row7 = mysql_fetch_assoc($result7))
			{
				$news_id = $row7['a_id'];
				$news_begin_day = $row7['begin_day'];
				$news_subject = iconv("big5","UTF-8",$row7['subject']);
				$news_content = iconv("big5","UTF-8",$row7['content']);
				
				//$news_contents[] = "<tr><td>$course_name</td><td colspan='2'><span class='dropt'>$news_subject<span style='width:500px;'>$news_content</span></span></td></tr>";						
				$news_contents[] = "<tr><td>$course_name</td><td>$news_begin_day</td><td><span class='dropt'>$news_subject<span style='width:500px;'>$news_content</span></span></td></tr>";						
											
		
			}		
		}
		
		if(count($news_contents) == 0)
		{
			$has_news = 'n';			
		}
		else
		{
			$has_news = 'y';			
		}
		
		
		//教材
		$dir_name = "../../$course_id/textbook";
		$url = "http://cih.elearning.ccu.edu.tw/$course_id/textbook";
		$textbook_files = sort_file_list($dir_name);
		foreach((array)$textbook_files as $textbook_file)
		{
			$textbook_modified_time = date ("Y-m-d  H:i:s",filemtime("$dir_name/$textbook_file"));
			$textbook_file_url = rawurlencode($textbook_file);
			$textbook_file = iconv('big5','utf-8',$textbook_file);
			$textbook_contents[] = "<tr><td>$course_name</td><td><a href='javascript:void(0)' onclick=\"window.open('$url/$textbook_file_url')\">$textbook_file</td><td>$textbook_modified_time</td></tr>";						
		}
		
		$Q8 = "SELECT chap_num, sect_num FROM chap_title ORDER BY chap_num, sect_num";
		//echo $Q8;
			
		if ( !($result8 = mysql_db_query( $DB.$course_id, $Q8 ) ) )
		{
			die("資料庫查詢錯誤!!");
		}
		
		
		
		if(mysql_num_rows($result8) != 0)
		{
			while($row8 = mysql_fetch_assoc($result8))
			{
				$chap_num = $row8['chap_num'];
				$sect_num = $row8['sect_num'];
				if($sect_num == '0')
				{
					$dir_name = "../../$course_id/textbook/$chap_num";
					$url = "http://cih.elearning.ccu.edu.tw/$course_id/textbook/$chap_num";
				}
				else
				{
					$dir_name = "../../$course_id/textbook/$chap_num/$sect_num";
					$url = "http://cih.elearning.ccu.edu.tw/$course_id/textbook/$chap_num/$sect_num";
				}
										
				$textbook_files = sort_file_list($dir_name);
				foreach((array)$textbook_files as $textbook_file)
				{
					$textbook_modified_time = date ("Y-m-d  H:i:s",filemtime("$dir_name/$textbook_file"));
					$textbook_file_url = rawurlencode($textbook_file);
					$textbook_file = iconv('big5','utf-8',$textbook_file);
					$textbook_contents[] = "<tr><td>$course_name</td><td><a href='$url/$textbook_file_url'>$textbook_file</td><td>$textbook_modified_time</td></tr>";						
				}			
			}		
		}
		
		if(count($textbook_contents) == 0)
		{
			$has_textbook = 'n';			
		}
		else
		{
			$has_textbook = 'y';			
		}
		
		
		
		//成績
		$Q9 = "select e.name, e.a_id, e.percentage, te.grade FROM exam e, take_exam te WHERE te.student_id = '".$user_id."' and te.exam_id = e.a_id and e.is_online = '0'";
		$Q10 = "select e.name, e.a_id, e.percentage, te.grade FROM exam e, take_exam te WHERE te.student_id = '".$user_id."' and te.exam_id = e.a_id and e.is_online = '1' and ( e.public = '1' or (e.end_time != '00000000000000' && e.beg_time <= ".date("YmdHis")." ) )";
		$Q11 = "select h.name, h.a_id, h.percentage, hh.grade FROM homework h, handin_homework hh WHERE hh.student_id = '".$user_id."' and hh.homework_id = h.a_id and (h.public = '1' or h.public = '3')";
		
		$sum = 0.0;
		
		if ( !($result9 = mysql_db_query( $DB.$course_id, $Q9 ) ) )
		{
			die("資料庫查詢錯誤!!");
		}
		
		if(mysql_num_rows($result9) != 0)
		{
			while($row9 = mysql_fetch_assoc($result9))
			{
				$score_name = iconv("big5","UTF-8",$row9['name']);
				$score_percentage = $row9['percentage'];
				$score_grade = $row9['grade'] != "-1" ? $row9['grade'] : "";
				
				$score_contents[] = "<tr><td>$course_name</td><td>$score_name</td><td>$score_percentage" . "%" . "</td><td>$score_grade</td></tr>";						
											
				$sum = $sum + $score_grade*$score_percentage / 100;
			}		
		}
		
		
		if ( !($result10 = mysql_db_query( $DB.$course_id, $Q10 ) ) )
		{
			die("資料庫查詢錯誤!!");
		}
		
		if(mysql_num_rows($result10) != 0)
		{
			while($row10 = mysql_fetch_assoc($result10))
			{
				$score_name = iconv("big5","UTF-8",$row10['name']);
				$score_percentage = $row10['percentage'];
				$score_grade = $row10['grade'] != "-1" ? $row10['grade'] : "";
				
				$score_contents[] = "<tr><td>$course_name</td><td>$score_name</td><td>$score_percentage" . "%" . "</td><td>$score_grade</td></tr>";						
											
				$sum = $sum + $score_grade*$score_percentage / 100;
			}		
		}


		if ( !($result11 = mysql_db_query( $DB.$course_id, $Q11 ) ) )
		{
			die("資料庫查詢錯誤!!");
		}
		
		if(mysql_num_rows($result11) != 0)
		{
			while($row11 = mysql_fetch_assoc($result11))
			{
				$score_name = iconv("big5","UTF-8",$row11['name']);
				$score_percentage = $row11['percentage'];
				$score_grade = $row11['grade'] != "-1" ? $row11['grade'] : "";
				
				$score_contents[] = "<tr><td>$course_name</td><td>$score_name</td><td>$score_percentage" . "%" . "</td><td>$score_grade</td></tr>";						
											
				$sum = $sum + $score_grade*$score_percentage / 100;
			}		
		}		
		

		if(count($score_contents) == 0)
		{
			$has_score = 'n';			
		}
		else
		{
			$has_score = 'y';			
		}
		
		
	}
}
else
{
	echo  "<b><font size='3'><center>您這學期沒有修課</center></font></b>";
}

$per = 5;
$assignment_page = ceil(count($assignment_contents) / $per);
$exam_page = ceil(count($exam_contents) / $per);
$questionary_page = ceil(count($questionary_contents) / $per);
$news_page = ceil(count($news_contents) / $per);
$textbook_page = ceil(count($textbook_contents) / $per);
$score_page = ceil(count($score_contents) / $per);


if($has_assignment == 'n')
{
	echo "<div id='hw_1_1'>目前尚無未繳交作業</div>";
}
else
{
	//取出所有作業
	for($i = 1; $i <= $assignment_page; $i++)
	{
		if($i == 1)
			echo "<div id='hw_1_$i'>";
		else
			echo "<div id='hw_1_$i' style='display:none;'>";
		//產生頁面標題
		echo "<table align='center' border='0' width='" . WIDTH . "'>";
		echo  "<tr><td align='center'><font size='3'><b>作業列表</b></font></td></tr>";
		echo  "</table>";

		echo  "<table align='center' border='1' width='" . WIDTH . "' style='table-layout:fixed;border-collapse:collapse;margin-top:5px;'>";
		echo  "<tr style=background-color:#D6D6D6;border: 1px solid black;><th>課程</th><th>作業</th><th>繳交期限</th></tr>";
		for($j = ($i - 1) * $per; $j < $i * $per; $j++)
		{	
			if($assignment_contents[$j] !== NULL)
			{
				echo $assignment_contents[$j];
			}
		}
		
		echo "<tr><td colspan='3'>";
		
		for($k = 1; $k <= $assignment_page; $k++)
		{		
			echo "<a href=\"#\" onclick=\"javascript:ShowContent('hw_1_" . $k . "')\")' onfocus='onFocus(this);' onblur='onBlur(this);'>$k</a>&nbsp;";
			
		}
		echo "</td></tr>";
		echo  "</table>";
		echo "</div>";		
	}
}
	

if($has_exam == 'n')
{
	echo "<div id='hw_2_1' style='display:none;'>目前尚無未做測驗</div>";
}
else
{	
	//取出所有測驗
	for($i = 1; $i <= $exam_page; $i++)
	{
		if($i == 1)
			echo "<div id='hw_2_$i' style='display:none;'>";
		else
			echo "<div id='hw_2_$i' style='display:none;'>";
		//產生頁面標題
		echo "<table align='center' border='0' width='" . WIDTH . "'>";
		echo  "<tr><td align='center'><font size='3'><b>測驗列表</b></font></td></tr>";
		echo  "</table>";

		echo  "<table align='center' border='1' width='" . WIDTH . "' style='table-layout:fixed;border-collapse:collapse;margin-top:5px;'>";
		echo  "<tr style=background-color:#D6D6D6;border: 1px solid black;><th>課程</th><th>測驗</th><th>結束時間</th></tr>";
		for($j = ($i - 1) * $per; $j < $i * $per; $j++)
		{	
			if($exam_contents[$j] !== NULL)
			{
				echo $exam_contents[$j];
			}
		}
		
		echo "<tr><td colspan='3'>";
		
		for($k = 1; $k <= $exam_page; $k++)
		{		
			echo "<a href=\"#\" onclick=\"javascript:ShowContent('hw_2_" . $k . "')\" onfocus='onFocus(this);' onblur='onBlur(this);'>$k</a>&nbsp;";
		}
		echo "</td></tr>";
		echo  "</table>";
		echo "</div>";		
	}
}



if($has_questionary == 'n')
{
	echo "<div id='hw_3_1' style='display:none;'>目前尚無未做問卷</div>";
}
else
{	
	//取出所有問卷
	for($i = 1; $i <= $questionary_page; $i++)
	{
		if($i == 1)
			echo "<div id='hw_3_$i' style='display:none;'>";
		else
			echo "<div id='hw_3_$i' style='display:none;'>";
		//產生頁面標題
		echo "<table align='center' border='0' width='" . WIDTH . "'>";
		echo  "<tr><td align='center'><font size='3'><b>問卷列表</b></font></td></tr>";
		echo  "</table>";

		echo  "<table align='center' border='1' width='" . WIDTH . "' style='table-layout:fixed;border-collapse:collapse;margin-top:5px;'>";
		echo  "<tr style=background-color:#D6D6D6;border: 1px solid black;><th>課程</th><th>問卷</th><th>結束時間</th></tr>";
		for($j = ($i - 1) * $per; $j < $i * $per; $j++)
		{	
			if($questionary_contents[$j] !== NULL)
			{
				echo $questionary_contents[$j];
			}
		}
		
		echo "<tr><td colspan='3'>";
		
		for($k = 1; $k <= $questionary_page; $k++)
		{		
			echo "<a href=\"#\" onclick=\"javascript:ShowContent('hw_3_" . $k . "')\" onfocus='onFocus(this);' onblur='onBlur(this);'>$k</a>&nbsp;";
		}
		echo "</td></tr>";
		echo  "</table>";
		echo "</div>";		
	}
}


if($has_news == 'n')
{
	echo "<div id='hw_4_1' style='display:none;'>目前尚無最新消息</div>";
}
else
{	
	//取出所有公告
	for($i = 1; $i <= $news_page; $i++)
	{
		if($i == 1)
			echo "<div id='hw_4_$i' style='display:none;'>";
		else
			echo "<div id='hw_4_$i' style='display:none;'>";
		//產生頁面標題
		echo "<table align='center' border='0' width='" . WIDTH . "'>";
		//echo  "<tr><td align='center'><font size='3'><b>公告列表</b></font></td></tr>";
		echo  "<tr><td align='center'><font size='3'><b>最近兩週公告列表</b></font></td></tr>";
		echo  "</table>";

		
		echo  "<table align='center' border='1' width='" . WIDTH . "' style='table-layout:fixed;border-collapse:collapse;margin-top:5px;'>";
		//echo  "<tr style=background-color:#D6D6D6;border: 1px solid black;><th>課程</th><th colspan='2'>標題</th></tr>";
		echo  "<tr style=background-color:#D6D6D6;border: 1px solid black;><th>課程</th><th>日期</th><th>標題</th></tr>";
		for($j = ($i - 1) * $per; $j < $i * $per; $j++)
		{	
			if($news_contents[$j] !== NULL)
			{
				echo $news_contents[$j];
			}
		}
		
		echo "<tr><td colspan='3'>";
		
		for($k = 1; $k <= $news_page; $k++)
		{		
			echo "<a href=\"#\" onclick=\"javascript:ShowContent('hw_4_" . $k . "')\" onfocus='onFocus(this);' onblur='onBlur(this);'>$k</a>&nbsp;";
		}
		echo "</td></tr>";
		echo  "</table>";
		echo "</div>";		
	}
}


if($has_textbook == 'n')
{
	echo "<div id='hw_5_1' style='display:none;'>目前尚無教材</div>";
}
else
{	
	//取出所有教材
	for($i = 1; $i <= $textbook_page; $i++)
	{
		if($i == 1)
			echo "<div id='hw_5_$i' style='display:none;'>";
		else
			echo "<div id='hw_5_$i' style='display:none;'>";
		//產生頁面標題
		echo "<table align='center' border='0' width='" . WIDTH . "'>";
		echo  "<tr><td align='center'><font size='3'><b>教材列表</b></font></td></tr>";
		echo  "</table>";

		
		echo  "<table align='center' border='1' width='" . WIDTH . "' style='table-layout:fixed;border-collapse:collapse;margin-top:5px;'>";
		echo  "<tr style=background-color:#D6D6D6;border: 1px solid black;><th>課程</th><th>檔名</th><th>修改時間</th></tr>";
		for($j = ($i - 1) * $per; $j < $i * $per; $j++)
		{	
			if($textbook_contents[$j] !== NULL)
			{
				echo $textbook_contents[$j];
			}
		}
		
		echo "<tr><td colspan='3'>";
		
		for($k = 1; $k <= $textbook_page; $k++)
		{		
			echo "<a href=\"#\" onclick=\"javascript:ShowContent('hw_5_" . $k . "')\" onfocus='onFocus(this);' onblur='onBlur(this);'>$k</a>&nbsp;";
		}
		echo "</td></tr>";
		echo  "</table>";
		echo "</div>";		
	}
}



if($has_score == 'n')
{
	echo "<div id='hw_6_1' style='display:none;'>目前尚無成績</div>";
}
else
{	
	//取出所有成績
	for($i = 1; $i <= $score_page; $i++)
	{
		if($i == 1)
			echo "<div id='hw_6_$i' style='display:none;'>";
		else
			echo "<div id='hw_6_$i' style='display:none;'>";
		//產生頁面標題
		echo "<table align='center' border='0' width='" . WIDTH . "'>";
		echo  "<tr><td align='center'><font size='3'><b>成績列表</b></font></td></tr>";
		echo  "</table>";

		
		echo  "<table align='center' border='1' width='" . WIDTH . "' style='table-layout:fixed;border-collapse:collapse;margin-top:5px;'>";
		echo  "<tr style=background-color:#D6D6D6;border: 1px solid black;><th>課程</th><th>名稱</th><th>比例</th><th>分數</th></tr>";
		for($j = ($i - 1) * $per; $j < $i * $per; $j++)
		{	
			if($score_contents[$j] !== NULL)
			{
				echo $score_contents[$j];
			}
		}
		
		echo "<tr><td colspan='4'>";
		
		for($k = 1; $k <= $score_page; $k++)
		{		
			echo "<a href=\"#\" onclick=\"javascript:ShowContent('hw_6_" . $k . "')\" onfocus='onFocus(this);' onblur='onBlur(this);'>$k</a>&nbsp;";
		}
		echo "</td></tr>";
		echo  "</table>";
		echo "</div>";		
	}
}


	
	 
?>
<head>
<style type='text/css'>
/*
.menu:blur {background-color:red;
           }
.menu:focus {background-color:yellow;
           }

*/
</style>

<script type="text/javascript" language="JavaScript">
	function HideContent(d) {
	document.getElementById(d).style.display = "none";
	}
	function ShowContent(d) {
		
		document.getElementById('assignment').style.backgroundColor="white";
		document.getElementById('exam').style.backgroundColor="white";
		document.getElementById('questionary').style.backgroundColor="white";
		document.getElementById('news').style.backgroundColor="white";
		document.getElementById('textbook').style.backgroundColor="white";
		document.getElementById('score').style.backgroundColor="white";
		
		
		if(d == 'hw_1_1') {	document.getElementById('assignment').style.backgroundColor="yellow"; }
		if(d == 'hw_2_1') {	document.getElementById('exam').style.backgroundColor="yellow"; }
		if(d == 'hw_3_1') {	document.getElementById('questionary').style.backgroundColor="yellow"; }
		if(d == 'hw_4_1') {	document.getElementById('news').style.backgroundColor="yellow"; }
		if(d == 'hw_5_1') {	document.getElementById('textbook').style.backgroundColor="yellow"; }
		if(d == 'hw_6_1') {	document.getElementById('score').style.backgroundColor="yellow"; }
		
		
		<?php $assignment_page = $assignment_page != 0 ? $assignment_page : 1 ; ?>
		for(var a=1 ;a<=<?php echo $assignment_page; ?>;a++){

			eval('hw_1_'+a).style.display = "none";	
			
			//alert(a);

		}
		
		<?php $exam_page = $exam_page != 0 ? $exam_page : 1 ; ?>
		for(var a=1 ;a<=<?php echo $exam_page; ?>;a++){

			eval('hw_2_'+a).style.display = "none";	
			
			//alert(a);

		}
		
		<?php $questionary_page = $questionary_page != 0 ? $questionary_page : 1 ; ?>
		for(var a=1 ;a<=<?php echo $questionary_page; ?>;a++){

			eval('hw_3_'+a).style.display = "none";	
			//alert(a);

		}
		
		<?php $news_page = $news_page != 0 ? $news_page : 1 ; ?>
		for(var a=1 ;a<=<?php echo $news_page; ?>;a++){

			eval('hw_4_'+a).style.display = "none";	
			//alert(a);

		}
		
		<?php $textbook_page = $textbook_page != 0 ? $textbook_page : 1 ; ?>
		for(var a=1 ;a<=<?php echo $textbook_page; ?>;a++){

			eval('hw_5_'+a).style.display = "none";	
			//alert(a);

		}
		
		
		<?php $score_page = $score_page != 0 ? $score_page : 1 ; ?>
		for(var a=1 ;a<=<?php echo $score_page; ?>;a++){

			eval('hw_6_'+a).style.display = "none";	
			//alert(a);

		}
		
		
		
			
			
		document.getElementById(d).style.display = "block";	
		//document.getElementById(d).style.backgroundColor="yellow";	
	}
	
	
	
	
	function ReverseDisplay(d) {
		if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
		else { document.getElementById(d).style.display = "none"; }
	}
	
	
	
	
 </script> 
 <style>
 span.dropt {border-bottom: thin dotted; background: #ffeedd;}
span.dropt:hover {text-decoration: none; background: #ffffff; z-index: 6; }
span.dropt span {position: absolute; left: -9999px;
  margin: 20px 0 0 0px; padding: 3px 3px 3px 3px;
  border-style:solid; border-color:black; border-width:1px; z-index: 6;}
span.dropt:hover span {left: 2%; background: #ffffff;} 
span.dropt span {position: absolute; left: -9999px;
  margin: 4px 0 0 0px; padding: 3px 3px 3px 3px; 
  border-style:solid; border-color:black; border-width:1px;}
span.dropt:hover span {margin: 20px 0 0 170px; background: #ffffff; z-index:6;} 
</style>
 </head> 
 
 
<?php
function sort_file_list($dir_name)
{
	//若有index.htm或index.html則為網頁教材，就不顯示該目錄的檔案清單
	if(is_file("$dir_name/index.htm") || is_file("$dir_name/index.html"))
	{
		return;
	}
	
	if(!is_dir($dir_name))
	{
		return;
	}
	
	$handle = dir($dir_name);
    $i=false;
	$files = array();
	
	while (false !== ( $file = $handle->read() ) ) 
	{
		if( ( strcmp($file,".")!=0 ) && ( strcmp($file,"..")!=0 ) && !is_dir($dir_name."/".$file) ) 
		{
			$files[filemtime($dir_name."/".$file)] = $file;
		}
	}
	ksort($files);	//依日期排序
	$handle->close();
	return $files;    
}

?>
 
 
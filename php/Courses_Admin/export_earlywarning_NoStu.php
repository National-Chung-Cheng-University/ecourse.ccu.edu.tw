<?php
/**
 * 匯出沒有預警名單之課程 1001026
 * 文字檔格式：科目編碼[\t]班別[\t]學號[\n]
 */
	require 'fadmin.php';
?>
<HTML>
<HEAD>
<TITLE>匯出[無預警學生]課程清單(文字檔)</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<meta http-equiv="Pragma" content="no-cache">
</HEAD>
<BODY>
<?	
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		echo "<body background = \"/images/img/bg.gif\"><center>";
		if( ($error = export_earlywarning()) == -1 )
			echo "<a href=\"./early_NOwarning.txt\">下載預警學生清單(文字檔)</a><br>";
			
		else{
			echo "$error<br>";
		}

		echo "<br><a href=../check_admin.php>回系統管理介面</a></center></body>";
	}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
	
	// 
	function export_earlywarning() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;


		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql資料庫連結錯誤!!";
			echo "$error";
		}
		$Q1="select * from this_semester";
		$result1 = mysql_db_query($DB,$Q1);
		$row1 = mysql_fetch_array($result1);
		$year = $row1['year'];
		$term = $row1['term'];
		
		$Q2="select course_id, count(course_id) as cnt1, student_id from early_warning where year=$year and term=$term group by course_id having cnt1 = 1 order by course_id";
		//echo $Q2;
		$content="";
		if($result2 = mysql_db_query($DB,$Q2)){
			$fp = fopen("./early_NOwarning.txt", "w");
			$count=0;
			while( $row2 = mysql_fetch_array($result2))
			{				
				$course_id = $row2['course_id'];
				$Q3="SELECT c.course_no, c.name AS course_name, g.deptcd, g.name AS dept_name FROM course c, course_group g WHERE c.group_id = g.a_id AND c.a_id = $course_id ";
				//echo $Q3;
				if($result3 = mysql_db_query($DB,$Q3)){
					$row3 = mysql_fetch_array($result3);
					$content.=$row3['course_no']."\t".$row3['course_name']."\t".$row3['deptcd']."\t".$row3['dept_name']."\n";
					$count ++;
					
				}	
			}
			
			fwrite($fp,$content);
			echo "此學期共".$count."筆<BR><BR>";			
			
		}

		fclose($fp);		
		return -1;
	}
	
	
	
	function Error_Handler( $msg, $cnx ) {  
		echo "$msg \n";
		sybase_close( $cnx); exit();  
	}	
?>
</BODY>
</HTML>
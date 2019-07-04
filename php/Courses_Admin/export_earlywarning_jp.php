<?php
/**
 * 匯出預警名單為文字檔9605
 * 文字檔格式：科目編碼[\t]班別[\t]學號[\t]預警理由[\n]
 */
	require 'fadmin.php';
?>
<HTML>
<HEAD>
<TITLE>匯出預警學生清單(文字檔)</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<meta http-equiv="Pragma" content="no-cache">
</HEAD>
<BODY>
<?	
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		echo "<body background = \"/images/img/bg.gif\"><center>";
		if( ($error = export_earlywarning()) == -1 )
			echo "<a href=\"./early_warning.txt\">下載預警學生清單(文字檔)</a><br>";
			
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
		//100.5.9 教學組要求加入ew.reason預警理由 by Jim
		$Q1="select c.course_no, u.id, ew.mdate, ew.reason  from early_warning ew, user u, course c, this_semester ts where ew.course_id=c.a_id and ew.student_id=u.a_id and ew.year=ts.year and ew.term=ts.term order by c.course_no";
		$content="";
		$reason_t="";
		if ($result1 = mysql_db_query($DB,$Q1)){			
			$fp = fopen("./early_warning.txt", "w");
			$count=0;
			while( $row = mysql_fetch_array($result1))
			{				
				//100.5.9 49~64行, 教學組要求加入ew.reason預警理由 by Jim
				if ( $row['reason'] == '1' )
				{
					$reason_t = '成績不佳';
				}
				elseif ( $row['reason'] == '2' )
				{
					$reason_t = '缺課';
				}
				elseif ( $row['reason'] == '3' )
				{
					$reason_t = '成績不佳且缺課';
				}
				else
				{
					$reason_t = 'N/A';
				}
				list($cour_cd, $grp)=split("_",$row["course_no"]);
				$content.=$cour_cd."\t".$grp."\t ".$row["id"]."\t ".$row["mdate"]."\t ".$reason_t."\n";
				$count ++;
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
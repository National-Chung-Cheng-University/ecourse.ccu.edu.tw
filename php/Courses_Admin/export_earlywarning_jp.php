<?php
/**
 * �ץX�wĵ�W�欰��r��9605
 * ��r�ɮ榡�G��ؽs�X[\t]�Z�O[\t]�Ǹ�[\t]�wĵ�z��[\n]
 */
	require 'fadmin.php';
?>
<HTML>
<HEAD>
<TITLE>�ץX�wĵ�ǥͲM��(��r��)</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<meta http-equiv="Pragma" content="no-cache">
</HEAD>
<BODY>
<?	
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		echo "<body background = \"/images/img/bg.gif\"><center>";
		if( ($error = export_earlywarning()) == -1 )
			echo "<a href=\"./early_warning.txt\">�U���wĵ�ǥͲM��(��r��)</a><br>";
			
		else{
			echo "$error<br>";
		}

		echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a></center></body>";
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
	
	// 
	function export_earlywarning() {
		global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $id, $pass;


		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "mysql��Ʈw�s�����~!!";
			echo "$error";
		}
		//100.5.9 �оǲխn�D�[�Jew.reason�wĵ�z�� by Jim
		$Q1="select c.course_no, u.id, ew.mdate, ew.reason  from early_warning ew, user u, course c, this_semester ts where ew.course_id=c.a_id and ew.student_id=u.a_id and ew.year=ts.year and ew.term=ts.term order by c.course_no";
		$content="";
		$reason_t="";
		if ($result1 = mysql_db_query($DB,$Q1)){			
			$fp = fopen("./early_warning.txt", "w");
			$count=0;
			while( $row = mysql_fetch_array($result1))
			{				
				//100.5.9 49~64��, �оǲխn�D�[�Jew.reason�wĵ�z�� by Jim
				if ( $row['reason'] == '1' )
				{
					$reason_t = '���Z����';
				}
				elseif ( $row['reason'] == '2' )
				{
					$reason_t = '�ʽ�';
				}
				elseif ( $row['reason'] == '3' )
				{
					$reason_t = '���Z���ΥB�ʽ�';
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
			echo "���Ǵ��@".$count."��<BR><BR>";
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
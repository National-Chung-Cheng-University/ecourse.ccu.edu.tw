<?php
	require 'fadmin.php';
	/**********************************
	devon 2006-02-15
	update_temptakcih.php
	��s�Ȯɩʪ���ҦW��
	**********************************/
?>
<html>
<head>
<title>Update Temp Course</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>��s�Ȯɩʿ�Ҹ��!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	�@
</div>
<?php
/*	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) )
	{*/
		if(($error = take_course()) == -1){
			echo "�Ȯɩʪ��׽Ҹ�Ƨ�s����!!<br>";
		}
		else{
			echo "$error<br>";
		}
		echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a>";
	/*}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
	*/
	// ��s��Ҹ��
	function take_course()
	{
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		// �s��mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
		{
			$error = "��Ʈw�s�����~!!";
			return $error;
		}
		$Q0 = "select student_id, course_no from temp_takcih order by course_no";
		$result0 = mysql_db_query($DB, $Q0);
		if(!$result0)
		{
			echo "��ƮwŪ�����~!! $Q0<br>";
		}
		
		$count = 0;
		$temp = -1;
		$total = mysql_num_rows($result0);
		echo "�`�@ $total ����<br>";
		ob_end_flush();
		ob_implicit_flush(1);
		while($array=mysql_fetch_array($result0))
		{
			$count++;
			$p = number_format((100*$count)/$total, 2);
			//$p = (int)((100*$count)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"�׽Ҹ�Ƨ�s���A�еy�J $p%\" ; </script>";
			}
			$temp = $p;
			
			$cno = $array['course_no'];
			$Qs1 = "select course_id from course_no where course_no='$cno'";
			if ($result1 = mysql_db_query($DB,$Qs1))
			{
				if(($row1 = mysql_fetch_array($result1))==0)
				{
					$error = "���ҵ{���s�b!!";
					echo "$error $array[course_no]<br>";
					continue;
				}
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Qs1<br>";
			}
			$Qs2 = "select a_id from user where id='$array[student_id]'";
			if ($result2 = mysql_db_query($DB,$Qs2)){
				if(($row2 = mysql_fetch_array($result2))==0)
				{
					$error = "���ǥͤ��s�b!!";
					echo "$error $array[student_id]<br>";
					continue;
				}
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Qs2<br>";
			}
			
			$Qs3 = "select group_id from course where a_id='$row1[course_id]'";
			if ($result3 = mysql_db_query($DB,$Qs3))
			{
				$row3 = mysql_fetch_array($result3);
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Qs3<br>";
			}
			
			//----devon---�p�G�ǥͪ�credit�O0�B�b��Ҩt�θ̦���Ҹ�ơA���Nupdate credit=1-------------------------------------------
			$Q4 = "select * from take_course where student_id='".$row2[a_id]."' and course_id='".$row1[course_id]."' and credit=0";
			if($result4 = mysql_db_query($DB, $Q4))
			{
				if(mysql_num_rows($result4) == 1)
				{
					echo "$Q4<br>";
					mysql_db_query($DB, "update take_course set credit='1' where student_id='".$row2[a_id]."' and course_id='".$row1[course_id]."'");
				}
			}
			
			$Qins = "insert into take_course (course_id, student_id, group_id, validated, credit) values ('$row1[course_id]', '$row2[a_id]', '$row3[group_id]', '1', '1')";
			//echo "$Qins <br>";
			
			if(!($resulti = mysql_db_query($DB,$Qins)))
			{
				$error = "mysql��Ʈw�g�J���~!!";
				//return "$error $Qins<br>";
				continue;
			}
		}
		//sybase_close( $cnx);
		return -1;
	}
?>
</div>
</center>
</body>
</html>
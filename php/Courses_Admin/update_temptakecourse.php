<?php
	require 'fadmin.php';
	/**********************************
	devon 2006-02-15
	update_temptakcih.php
	更新暫時性的選課名單
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
          <font color=#000000>更新暫時性選課資料!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	　
</div>
<?php
/*	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) )
	{*/
		if(($error = take_course()) == -1){
			echo "暫時性的修課資料更新完畢!!<br>";
		}
		else{
			echo "$error<br>";
		}
		echo "<br><a href=../check_admin.php>回系統管理介面</a>";
	/*}
	else
		show_page( "index_ad.tpl", "你的權限錯誤，請重新登入!!");
	*/
	// 更新選課資料
	function take_course()
	{
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		// 連結mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
		{
			$error = "資料庫連結錯誤!!";
			return $error;
		}
		$Q0 = "select student_id, course_no from temp_takcih order by course_no";
		$result0 = mysql_db_query($DB, $Q0);
		if(!$result0)
		{
			echo "資料庫讀取錯誤!! $Q0<br>";
		}
		
		$count = 0;
		$temp = -1;
		$total = mysql_num_rows($result0);
		echo "總共 $total 門課<br>";
		ob_end_flush();
		ob_implicit_flush(1);
		while($array=mysql_fetch_array($result0))
		{
			$count++;
			$p = number_format((100*$count)/$total, 2);
			//$p = (int)((100*$count)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"修課資料更新中，請稍侯 $p%\" ; </script>";
			}
			$temp = $p;
			
			$cno = $array['course_no'];
			$Qs1 = "select course_id from course_no where course_no='$cno'";
			if ($result1 = mysql_db_query($DB,$Qs1))
			{
				if(($row1 = mysql_fetch_array($result1))==0)
				{
					$error = "此課程不存在!!";
					echo "$error $array[course_no]<br>";
					continue;
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Qs1<br>";
			}
			$Qs2 = "select a_id from user where id='$array[student_id]'";
			if ($result2 = mysql_db_query($DB,$Qs2)){
				if(($row2 = mysql_fetch_array($result2))==0)
				{
					$error = "此學生不存在!!";
					echo "$error $array[student_id]<br>";
					continue;
				}
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Qs2<br>";
			}
			
			$Qs3 = "select group_id from course where a_id='$row1[course_id]'";
			if ($result3 = mysql_db_query($DB,$Qs3))
			{
				$row3 = mysql_fetch_array($result3);
			}
			else{
				$error = "mysql資料庫讀取錯誤!!";
				return "$error $Qs3<br>";
			}
			
			//----devon---如果學生的credit是0且在選課系統裡有選課資料，那就update credit=1-------------------------------------------
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
				$error = "mysql資料庫寫入錯誤!!";
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
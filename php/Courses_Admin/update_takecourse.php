<?PHP
	require 'fadmin.php';
?>
<html>
<head>
<title>Update Course</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>��s��Ҹ��!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	�@
</div>
<?PHP
	if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
		if(($error = take_course()) == -1){
			echo "�@��ͭ׽Ҹ�Ƨ�s����!!<br>";
		}
		else{
			echo "$error<br>";
		}
		if(($error = take_course_gra()) == -1){
			echo "�M�Z�׽Ҹ�Ƨ�s����!!<br>";
		}
		else{
			echo "$error<br>";
		}
		
		echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a>";
	}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
	
	// ��s��Ҹ��
	function take_course(){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		// �s��sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
		}
		$csd = @sybase_select_db("academic", $cnx);
		
		$cur = sybase_query("select std_no, cour_cd, grp from a31v_sel_class_tea", $cnx);
		if(!$cur) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}
		
		// �s��mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}

		$count = 0;
		$temp = -1;
		$total = sybase_num_rows($cur);
		ob_end_flush();
		ob_implicit_flush(1);
		while($array=sybase_fetch_array($cur)){
			$count++;
			$p = number_format((100*$count)/$total, 2);
			//$p = (int)((100*$count)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"�@��ͭ׽Ҹ�Ƨ�s���A�еy�J $p%\" ; </script>";
			}
			$temp = $p;
			
			$cno = $array[cour_cd]."_".$array[grp];
			$Qs1 = "select course_id from course_no where course_no='$cno'";
			if ($result1 = mysql_db_query($DB,$Qs1)){
				if(($row1 = mysql_fetch_array($result1))==0){
					$error = "���ҵ{���s�b!!";
					//echo "$error $array[cour_cd]";
					continue;
				}
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Qs1<br>";
			}
			
			$Qs2 = "select a_id from user where id='$array[std_no]'";
			if ($result2 = mysql_db_query($DB,$Qs2)){
				if(($row2 = mysql_fetch_array($result2))==0){
					$error = "���ǥͤ��s�b!!";
					echo "$error $array[std_no]<br>";
					continue;
				}
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Qs2<br>";
			}
			
			$Qs3 = "select group_id from course where a_id='$row1[course_id]'";
			if ($result3 = mysql_db_query($DB,$Qs3)){
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
			
			if(!($resulti = mysql_db_query($DB,$Qins))){
				$error = "mysql��Ʈw�g�J���~!!";
				//return "$error $Qins<br>";
				continue;
			}
		}
		sybase_close( $cnx);
		return -1;
	}

	// ��s�M�Z��Ҹ��
	function take_course_gra(){
		global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
		// �s��sybase
		if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
			Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
		}
		$csd = @sybase_select_db("academic_gra", $cnx);
		
		$cur = sybase_query("select std_no, cour_cd, grp from a31v_sel_class_tea", $cnx);
		if(!$cur) {  
			Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
		}
		
		// �s��mysql
		if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
		}

		$count = 0;
		$temp = -1;
		$total = sybase_num_rows($cur);
		ob_end_flush();
		ob_implicit_flush(1);
		while($array=sybase_fetch_array($cur)){
			$count++;
			$p = number_format((100*$count)/$total, 2);
			//$p = (int)((100*$count)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"�M�Z�׽Ҹ�Ƨ�s���A�еy�J $p%\" ; </script>";
			}
			$temp = $p;
			
			$cno = $array[cour_cd]."_".$array[grp];
			$Qs1 = "select course_id from course_no where course_no='$cno'";
			if ($result1 = mysql_db_query($DB,$Qs1)){
				if(($row1 = mysql_fetch_array($result1))==0){
					$error = "���M�Z�ҵ{���s�b!!";
					//echo "$error $array[cour_cd]";
					continue;
				}
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Qs1<br>";
			}
			
			$Qs2 = "select a_id from user where id='$array[std_no]'";
			if ($result2 = mysql_db_query($DB,$Qs2)){
				if(($row2 = mysql_fetch_array($result2))==0){
					$error = "���M�Z�ǥͤ��s�b!!";
					echo "$error $array[std_no]<br>";
					continue;
				}
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Qs2<br>";
			}
			
			$Qs3 = "select group_id from course where a_id='$row1[course_id]'";
			if ($result3 = mysql_db_query($DB,$Qs3)){
				$row3 = mysql_fetch_array($result3);
			}
			else{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Qs3<br>";
			}
			
			//----devon---�p�G�ǥͪ�credit�O0�B�b��Ҩt�θ̦���Ҹ�ơA���Nupdate credit=1-------------------------------------------
			$Q4 = "select * from take_course where student_id='".$row2[a_id]."' and course_id='".$row1[course_id]."' and credit=0";
			$result4 = mysql_db_query($DB, $Q4);
			if(mysql_num_rows($result4) == 1)
			{
				echo "$Q4<br>";
				mysql_db_query($DB, "update take_course set credit='1' where student_id='".$row2[a_id]."' and course_id='".$row1[course_id]."'");
			}

			$Qins = "insert into take_course (course_id, student_id, group_id, validated, credit) values ('$row1[course_id]', '$row2[a_id]', '$row3[group_id]', '1', '1')";
			//echo "$Qins <br>";
			if(!($resulti = mysql_db_query($DB,$Qins))){
				$error = "mysql��Ʈw�g�J���~!!";
				//return "$error $Qins<br>";
				continue;
			}

		}
		sybase_close( $cnx);
		return -1;		
	}

	function Error_Handler( $msg, $cnx ) {  
		echo "$msg \n";
		sybase_close( $cnx); exit();  
	}
	
?>
</div>
</center>
</body>
</html>

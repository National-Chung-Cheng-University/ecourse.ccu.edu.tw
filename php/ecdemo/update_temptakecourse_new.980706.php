<?php
	require 'fadmin.php';
	include 'logger.php';
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
		echo "�}�l�R���e�@�~�׽Ҹ��<BR>";
		updateLog("�}�l�R���e�@�~�׽Ҹ��",3);
		if(($error = del_old_takcih()) == -1){
			echo "�e�@�~�׽Ҹ�ƧR������!!<br>";
			updateLog("�e�@�~�׽Ҹ�ƧR������!!",3);
		}
		else{
			echo "$error<BR>";
		}
		
		echo "�}�l��s�Ȯɩʭ׽Ҹ��<BR>";
		updateLog("�}�l��s�Ȯɩʭ׽Ҹ��",3);
		if(($error = update_temptakcih()) == -1){
			echo "�Ȯɩʪ��׽Ҹ�Ƨ�s����!!<br>";
			updateLog("�Ȯɩʪ��׽Ҹ�Ƨ�s����!!",3);
		}
		else{
			echo "$error<br>";
		}
		
		echo "�}�l�M���Ȯɩʭ׽Ҹ��<BR>";
		updateLog("�}�l�M���Ȯɩʭ׽Ҹ��",3);
		if(($error = del_now_termtakcih()) == -1)
		{
			echo "�Ȯɩʿ�ҲM������!!<br>";
			updateLog("�Ȯɩʿ�ҲM������!!",3);
		}
		else{
			echo "$error<br>";
		}
		
		echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a>";
	/*}
	else
		show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
	*/
	
//�R���@�~�e����Ҹ��
function del_old_takcih()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	// �s��sybase �qsybase���o�s�Ǵ�
	if( !($cnx = sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
	}
	$csd = sybase_select_db("academic", $cnx);
	
	$cur = sybase_query("select DISTINCT year, term from a31v_sel_class_tea", $cnx);
	if(!$cur) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	if($array=sybase_fetch_array($cur))
	{
		$year = $array['year'] - 1;
		$term = $array['term'];
	}
	else{
		$error = "�Ǵ���Ƥ��s�b!!<BR>";
		updateLog("�Ǵ���Ƥ��s�b!!",3);
		return $error;
	}
	
/*	// �s��mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$error = "��Ʈw�s�����~!!";
		return $error;
	}
	
	//��X���Ǵ����Ǧ~�Ǵ����
	$qs1 = "SELECT * FROM this_semester";
	if ($result1 = mysql_db_query($DB, $qs1)){
		if(($row1 = mysql_fetch_array($result1))==0){
			$error = "���Ǵ���Ƥ��s�b!!<BR>";
			return $error;
		}
	}
	else{
		$error = "mysql��ƮwŪ�����~1!!<br>";
		return $error;
	}
	//�n�R�������e�@�Ǧ~����Ҹ�� (�e�@�Ǧ~ = �W�W�Ǵ�)
	$year = $row1['year'] - 1;
	$term = $row1['term'];
*/
	
	$qs2 = "DELETE FROM take_course WHERE year='$year' AND term='$term'";
	$result2 = mysql_db_query($DB, $qs2);
	if (!$result2 ){
		$error = "�R���e�@�Ǧ~��Ҹ�ƥ���!!<BR>";
		updateLog("�R���e�@�Ǧ~��Ҹ�ƥ���!!",3);
		return $error;
	}
/*
	//�R���ǥ͸��	
	$qs2 = "SELECT * FROM take_course WHERE year='$year' AND term='$term'";
	$result2 = mysql_db_query($DB, $qs2);
	if ($result2 ){
		//��ܶi��start
		$count = 0;
		$temp = -1;
		$total = mysql_num_rows($result2);
		ob_end_flush();
		ob_implicit_flush(1);
		//��ܶi��end
		while($array2=mysql_fetch_array($result2))
		{
			//��ܶi��start
			$count++;
			$p = number_format((100*$count)/$total);
			if($p>$temp){
				echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
						document.all.progress.innerHTML = \"$class �W�Ǵ���Ҿǥ͸�ƲM�����A�еy�J $p%\" ; </script>";
			}
			$temp = $p;
			//��ܶi��end
			delete_stu($array2[student_id], $array2[course_id]);
		}		
	}
	else{
		$error = "mysql��ƮwŪ�����~2!!<BR>";
		return "$error $qs2<BR>";
	}
	//-------
*/	
	sybase_close( $cnx);
	return -1;
}

// ��s��Ҹ��
function update_temptakcih()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	// �s��mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "��Ʈw�s�����~!!";
		return $error;
	}
	
	// �s��sybase �qsybase���o�s�Ǵ�
	if( !($cnx = sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
	}
	$csd = sybase_select_db("academic", $cnx);
	
	$cur = sybase_query("select DISTINCT year, term from a31v_sel_class_tea", $cnx);
	if(!$cur) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	if($array=sybase_fetch_array($cur))
	{
		$year = $array['year'];
		$term = $array['term'];
	}
	else{
		$error = "�Ǵ���Ƥ��s�b!!<BR>";
		updateLog("�Ǵ���Ƥ��s�b!!",3);
		return $error;
	}
/*	
	//���o���Ǵ����
	$qs_sem = "SELECT * FROM this_semester";
	if ($result_sem = mysql_db_query($DB, $qs_sem )){
		if(($row_sem = mysql_fetch_array($result_sem))==0){
			$error = "���Ǵ���Ƥ��s�b!!<BR>";
			return $error;
		}
	}
	else{
		$error = "mysql��ƮwŪ�����~1!!<br>";
		return $error;
	
	}
	$year = $row_sem['year'];
	$term = $row_sem['term'];	
	//*/
	
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
	updateLog("�`�@ $total ����",3);
	ob_end_flush();
	ob_implicit_flush(1);
	while($array=mysql_fetch_array($result0))
	{		
		$count++;
		$p = number_format((100*$count)/$total, 1);
		//$p = (int)((100*$count)/$total);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"�׽Ҹ�Ƨ�s���A�еy�J $p%\" ; </script>";
		}
		$temp = $p;
		
		//�P�_���F�Ҹ��~�٭n�[�Wteach_course����T
		$cno = $array['course_no'];
		$Qs1 = "select c.a_id, c.group_id from course c, teach_course tec where c.course_no='$cno' AND c.a_id=tec.course_id AND tec.year='$year' AND tec.term='$term' ";
		if ($result1 = mysql_db_query($DB,$Qs1))
		{
			if(($row1 = mysql_fetch_array($result1))==0)
			{
				$error = "���ҵ{���s�b!!";
				echo "$error $array[course_no]<br>";
				updateLog("$error $array[course_no]",3);
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
				updateLog("$error $array[student_id]",3);
				continue;
			}
		}
		else{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Qs2<br>";
		}
/*		
		$Qs3 = "select group_id from course where a_id='$row1[a_id]'";
		if ($result3 = mysql_db_query($DB,$Qs3))
		{
			$row3 = mysql_fetch_array($result3);
		}
		else{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Qs3<br>";
		}
*/		
		//----devon---�p�G�ǥͪ�credit�O0�B�b��Ҩt�θ̦���Ҹ�ơA���Nupdate credit=1-------------------------------------------
		$Q4 = "select * from take_course where student_id='".$row2[a_id]."' and course_id='".$row1[a_id]."' and credit=0 AND year='$year' AND term ='$term'";
		if($result4 = mysql_db_query($DB, $Q4))
		{
			if(mysql_num_rows($result4) == 1)
			{
				mysql_db_query($DB, "update take_course set credit='1' where student_id='".$row2[a_id]."' and course_id='".$row1[a_id]."' AND year='$year' AND term ='$term'");
			}
		}
		
		$Qins = "insert into take_course (course_id, student_id, group_id, validated, credit, year ,term) values ('$row1[a_id]', '$row2[a_id]', '$row1[group_id]', '1', '1', '$year', '$term')";
		//echo "$Qins <br>";
		if(!($resulti = mysql_db_query($DB,$Qins)))
		{
			$error = "mysql��Ʈw�g�J���~!!$Qins";
			//echo $error." <BR>"; exit;
			//return "$error $Qins<br>";
			continue;
		}		
	}
	sybase_close( $cnx);
	return -1;
}

function del_now_termtakcih()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	$delete = 0;
	// �s��mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "��Ʈw�s�����~!!";
		return $error;
	}
	
	// �s��sybase �qsybase���o�s�Ǵ�
	if( !($cnx = sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) ){  
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
	}
	$csd = sybase_select_db("academic", $cnx);
	
	$cur = sybase_query("select DISTINCT year, term from a31v_sel_class_tea", $cnx);
	if(!$cur) {  
		Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
	}
	if($array=sybase_fetch_array($cur))
	{
		$year = $array['year'];
		$term = $array['term'];
	}
	else{
		$error = "�Ǵ���Ƥ��s�b!!<BR>";
		updateLog("�Ǵ���Ƥ��s�b!!",3);
		return $error;
	}
	//
/*	//���o���Ǵ����
	$qs_sem = "SELECT * FROM this_semester";
	if ($result_sem = mysql_db_query($DB, $qs_sem )){
		if(($row_sem = mysql_fetch_array($result_sem))==0){
			$error = "���Ǵ���Ƥ��s�b!!<BR>";
			return $error;
		}
	}
	else{
		$error = "mysql��ƮwŪ�����~1!!<br>";
		return $error;
	
	}
	$year = $row_sem['year'];
	$term = $row_sem['term'];	
	//*/
	
	$Q0 = "select student_id, course_id, combine_course_id from take_course where credit='1' AND year='$year' AND term='$term' order by student_id";
	$result0 = mysql_db_query($DB, $Q0);
	
	$count = 0;
	$temp = -1;
	$total = mysql_num_rows($result0);
	echo "�`�@ $total ����Ҹ��<br>";
	ob_end_flush();
	ob_implicit_flush(1);
	while ($row0 = mysql_fetch_array($result0))
	{
		$count++;
		$p = number_format((100*$count)/$total, 1);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \" �Ȯɩʿ�Ҹ�ƲM�����A�еy�J $p%\" ; </script>";
		}
		$temp = $p;
		
		$Q1 = "select course_no from course where a_id='".$row0[course_id]."'";
		if($result1 = mysql_db_query($DB,$Q1))
		{
			if(($row1 = mysql_fetch_array($result1))==0)
			{
				$error = "���ҵ{���s�b!!";
				echo "$error $row0[course_id]<br>";
				updateLog("$error $row0[course_id]",3);
				continue;
			}
		}
		else
		{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Q1<br>";
		}
		
		//Ū�X�X�֪��ҵ{�s��
		//�p�G�O-1��ܬO�w�]��
		if($row0['combine_course_id'] == -1){
			$row1_1['course_no'] = 0;
		}
		else{
			$Q1_1 = "select course_no from course where a_id='".$row0['combine_course_id']."'";
			if($result1_1 = mysql_db_query($DB,$Q1_1))
			{
				if(($row1_1 = mysql_fetch_array($result1_1))==0)
				{
					$error = "���ҵ{���s�b!!";
					echo "$error $row0[combine_course_id]<br>";
					updateLog("$error $row0[combine_course_id]",3);
					continue;
				}
			}
			else
			{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Q1_1<br>";
			}
		}
		//
		
		$Q2 = "select id from user where a_id='".$row0[student_id]."'";
		if($result2 = mysql_db_query($DB, $Q2))
		{
			if(($row2 = mysql_fetch_array($result2)) == 0)
			{
				$error = "���ǥͤ��s�b!!";
				echo "$error $row0[student_id]<br>";
				updateLog("$error $row0[student_id]",3);
				//delete_stu($row0[student_id], $row0[course_id]);
				continue;
			}
		}
		else
		{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Q2<br>";
		}
		
		$Q3 = "select * from temp_takcih where student_id='".$row2[id]."' and course_no='".$row1[course_no]."'";
		$Q4 = "select * from temp_takcih where student_id='".$row2[id]."' and course_no='".$row1_1[course_no]."'"; //�X�֪��Ҹ�
		if($result3 = mysql_db_query($DB, $Q3))
		{
			if($row3 = mysql_fetch_array($result3) == 0)
			{
				//�]���O�X�֪��ǥͤ~�R��
				if($result4 = mysql_db_query($DB, $Q4))
				{
					if($row4 = mysql_fetch_array($result4) == 0)
					{					
						echo "�R���ǥ� $row2[id] �ҭת� $row1[course_no] ����<br>";
						updateLog("�R���ǥ� $row2[id] �ҭת� $row1[course_no] ����",3);
						$delete++;
						delete_stu($row0[student_id], $row0[course_id], $year, $term);
					}
				}
				else
				{
					$error = "mysql��ƮwŪ�����~!!";
					return "$error $Q4<br>";
				}
				//
			}
		}
		else
		{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Q3<br>";
		}
		
	}
	echo "�`�@�R�F $delete �ӾǥͻP�ҵ{�����Y<br>";
	sybase_close( $cnx);
	return -1;
}
// �R����Ҹ�Ƥά�������
function delete_stu ($key,$course_id, $year, $term) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "��Ʈw�s�����~!!";
		show_page_d ( $message );
		return;
	}
	
/*	//���o���Ǵ����
	$qs_sem = "SELECT * FROM this_semester";
	if ($result_sem = mysql_db_query($DB, $qs_sem )){
		if(($row_sem = mysql_fetch_array($result_sem))==0){
			$error = "���Ǵ���Ƥ��s�b!!<BR>";
			return $error;
		}
	}
	else{
		$error = "mysql��ƮwŪ�����~1!!<br>";
		return $error;
	
	}
	$year = $row_sem['year'];
	$term = $row_sem['term'];	
	//
*/
	$Q1 = "Select student_id From take_course Where student_id='$key' AND year='$year' AND term='$term'";
	if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "��ƮwŪ�����~!!";
		show_page_d ( $message );
		return;
	}
	$Q2 = "Select id From user Where a_id='$key'";
	if ( !($resultOBJid = mysql_db_query( $DB, $Q2 ) ) ) {
		$message = "��ƮwŪ�����~!!";
		show_page_d ( $message );
		return;
	}
	$row_id = mysql_fetch_array( $resultOBJid );
	mysql_db_query( $DB.$course_id, "Delete From handin_homework Where student_id='$key'" );
	$resultOBJ2 = mysql_db_query( $DB.$course_id, "Select a_id From homework");
	if($resultOBJ2){
		while($row = mysql_fetch_array ( $resultOBJ2 )) {
			$target = "../../$course_id/homework/".$row['a_id']."/".$row_id['id'];
			if ( is_dir($target) )
				deldir ( $target );
		}
	}
	mysql_db_query( $DB.$course_id, "Delete From take_exam Where student_id='$key'");
	//----------�R���ݨ�------------
	mysql_db_query( $DB.$course_id, "Delete From take_questionary Where student_id='$key'");
	//-----------------------
	mysql_db_query( $DB.$course_id, "Delete From log Where user_id='$key'");
	mysql_db_query( $DB, "Delete From take_course Where student_id='$key' and course_id = '$course_id' AND year='$year' AND term='$term'");
	/*
	//coop
	$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
	while($row_coop = mysql_fetch_array ( $resultcoop )) {
		mysql_db_query( $DBC.$course_id, "Delete From coop_".$row_coop['a_id']."_group Where student_id='".$row_id['id']."'");
		mysql_db_query( $DBC.$course_id, "Delete From discuss_".$row_coop['a_id']."_subscribe Where user_id='".$row_id['id']."'");
		mysql_db_query( $DBC.$course_id, "Delete From grade_".$row_coop['a_id']." Where give_id='$key' or gived_id ='$key'");
		mysql_db_query( $DBC.$course_id, "Delete From guestbook_".$row_coop['a_id']." Where user_id='$key'");
		mysql_db_query( $DBC.$course_id, "Delete From log_".$row_coop['a_id']." Where user_id='$key'");
		mysql_db_query( $DBC.$course_id, "Delete From note_".$row_coop['a_id']." Where student_id='$key'");
		mysql_db_query( $DBC.$course_id, "Delete From share_".$row_coop['a_id']." Where student_id='$key'");
		mysql_db_query( $DBC.$course_id, "Delete take_coop Where student_id='$key'");
	}
	*/
	if( mysql_num_rows ( $resultOBJ ) == 1 )
	{
		mysql_db_query( $DB, "Delete From log Where user_id='$key'");
		//mysql_db_query( $DB, "Delete From user Where a_id='$key'");
		mysql_db_query( $DB, "delete from gbfriend where my_id = '$key' or friend_id='$key'" );	
	}
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

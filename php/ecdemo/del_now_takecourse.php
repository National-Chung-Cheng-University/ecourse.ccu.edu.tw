<?php
/*****�R���w�h�諸�ǥͦW��*********************
	�N�쥻����W���̫�Ӥ��b��Ҫ��ǥͦW�椤����Ҹ�ơA�çR���׽Ҭ���
**********************************************/
require 'fadmin.php';
?>
<html>
<head>
<title>Delete Dropped Courses</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>�M���̫᥼��W���ǥͦW��!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	�@
</div>
<?php
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) ) {
	if(($error = del_now_takcih("academic")) == -1){
		echo "�@��ͥ���W�ҵ{�M������!!<br>";
	}
	else{
		echo "$error<br>";
	}

	if(($error = del_now_takcih("academic_gra")) == -1){
		echo "�M�Z����W�ҵ{�M������!!<br>";
	}
	else{
		echo "$error<br>";
	}

	echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a>";
}
else
	show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");

function del_now_takcih($SDB)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	// �s��mysql
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "��Ʈw�s�����~!!";
		return $error;
	}
	
	// �s����Ҩt�θ�Ʈw
	if( !($cnx = @sybase_connect("140.123.30.7:4100", "acauser", "!!acauser13")) )
	{  
		Error_handler( "�b sybase_connect �����~�o��" , $cnx );  
	}
	$csd = @sybase_select_db($SDB, $cnx);
	
	// ���X�ҵ{�N�X
	if($SDB == "academic_gra")
		$Qs1 = "select c.course_no, c.a_id from course c, teach_course t, this_semester ts where t.year = ts.year and t.term = ts.term and c.a_id = t.course_id and (c.course_no like '___A%' or c.course_no like '___B%' or c.course_no like '___C%' or c.course_no like '___D%')";
	else if($SDB == "academic"){
		$Qs1 = "select c.course_no, c.a_id from course c, teach_course t, this_semester ts where t.year = ts.year and t.term = ts.term and c.a_id = t.course_id and !(c.course_no like '___A%' or c.course_no like '___B%' or c.course_no like '___C%' or c.course_no like '___D%')";
	}
	if(!($result1 = mysql_db_query($DB,$Qs1))){
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Qs1<br>";
	}

	$count = 0;
	$temp = -1;
	$total = mysql_num_rows($result1);
	ob_end_flush();
	ob_implicit_flush(1);
	while ($row1 = mysql_fetch_array($result1))
	{
		$count++;
		$p = number_format((100*$count)/$total, 2);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"$class �w�h��ҵ{�M�����A�еy�J $p%\" ; </script>";
		}
		$temp = $p;
		
		$course_no = explode("_",$row1['course_no']);
		$cour_cd = $course_no[0];
		$grp = $course_no[1];
		$course_id = $row1['a_id'];

		// �q�оǨt�Ψ��X��Ҹ��
		$Qs5 = "select student_id, id from user u,take_course tc, this_semester ts where course_id = '$course_id' and u.a_id=tc.student_id and tc.credit=1 AND tc.year=ts.year AND tc.term=ts.term";
		if ($result5 = mysql_db_query($DB,$Qs5)){
			while($row5 = mysql_fetch_array($result5)){
				$std_id = $row5['student_id'];
				$std_no = $row5['id'];
				// �q��Ҩt�θ�Ʈw�����X�ӦW�ǥͦb�����Ҫ���Ҹ��
				$Qs6 = "select std_no from a31v_sel_class_tea where cour_cd = '$cour_cd' and grp = '$grp' and std_no = '$std_no'";
				$cur = sybase_query($Qs6, $cnx);
				if(!$cur) {  
					Error_handler( "�b sybase_exec �����~�o��( �S�����жǦ^ ) " , $cnx );  
				}
				// �Y�L��ƫh�R����Ҹ�Ƥά�������
				if(($array=sybase_fetch_array($cur))==0){
					//echo $SDB." - ".$std_no." - ".$cour_cd.$grp."<br>";
					delete_stu($std_id, $course_id);
					echo "delete: $std_no<br>";
				}
			}
		}
		else{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Qs5<br>";
		}
		
		if($SDB == "academic")
			$class = "�@���";
		else
			$class = "�M�Z";
			

	}

	sybase_close( $cnx);
	return -1;		
}

// �R����Ҹ�Ƥά�������
function delete_stu ($key,$course_id) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "��Ʈw�s�����~!!";
		show_page_d ( $message );
		return;
	}

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
	//

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
	/*$resultOBJ2 = mysql_db_query( $DB.$course_id, "Select a_id From homework");
	while($row = mysql_fetch_array ( $resultOBJ2 )) {
		$target = "../../$course_id/homework/".$row['a_id']."/".$row_id['id'];
		if ( is_dir($target) )
			deldir ( $target );
	}*/
	mysql_db_query( $DB.$course_id, "Delete From take_exam Where student_id='$key'");
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

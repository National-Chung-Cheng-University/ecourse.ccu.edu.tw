<?php
/************************************************
	�M���Ȯɩʿ�ҦW��
	�M���q�q�⤤�߮��쪺�w��W�椤�A�w�h�諸�W��
************************************************/
require 'fadmin.php';
?>
<html>
<head>
<title>Delete Temp Courses</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>�M���Ȯɩʪ��ǥͦW��!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	�@
</div>
<?php
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) )
{
	if(($error = del_now_termtakcih()) == -1)
	{
		echo "�Ȯɩʿ�ҲM������!!<br>";
	}
	else{
		echo "$error<br>";
	}

	echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a>";
}
else
	show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");

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
	
	$Q0 = "select student_id, course_id from take_course where credit=1 AND year='$year' AND term='$term' order by student_id";
	
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
		$p = number_format((100*$count)/$total, 2);
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
				continue;
			}
		}
		else
		{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Q1<br>";
		}
		
		$Q2 = "select id from user where a_id='".$row0[student_id]."'";
		if($result2 = mysql_db_query($DB, $Q2))
		{
			if(($row2 = mysql_fetch_array($result2)) == 0)
			{
				$error = "���ǥͤ��s�b!!";
				echo "$error $row0[student_id]<br>";
				delete_stu($row0[student_id], $row0[course_id]);
				continue;
			}
		}
		else
		{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Q2<br>";
		}
		
		$Q3 = "select * from temp_takcih where student_id='".$row2[id]."' and course_no='".$row1[course_no]."'";
		if($result3 = mysql_db_query($DB, $Q3))
		{
			if($row3 = mysql_fetch_array($result3) == 0)
			{
				echo "�R���ǥ� $row2[id] �ҭת� $row1[course_no] ����<br>";
				$delete++;
				delete_stu($row0[student_id], $row0[course_id]);
			}
		}
		else
		{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Q3<br>";
		}
		
	}
	echo "�`�@�R�F $delete �ӾǥͻP�ҵ{�����Y<br>";
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
?>
</div>
</center>
</body>
</html>

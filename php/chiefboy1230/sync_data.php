<?php
	require 'fadmin.php';
	/**********************************
	20090929
	sync_data.php
	�T�{�Χ�s�׽Ҿǥͧ@�~����ݨ�
	**********************************/
?>
<html>
<head>
<title>�P�B���</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>�T�{�Χ�s�׽Ҿǥͧ@�~,����,�ݨ�!!</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress">	�@
</div>
<?php
/*	
if ( isset($PHPSESSID) && session_check_admin($PHPSESSID) )
{
	*/
		if(($error = sync_data()) == -1){
			echo "<br>�T�{�Χ�s����!!<br>";
		}
		else{
			echo "$error<br>";
		}
		echo "<br><a href=../check_admin.php>�^�t�κ޲z����</a>";
	/*
}
else
	show_page( "index_ad.tpl", "�A���v�����~�A�Э��s�n�J!!");
	*/
//�P�B�@�~,����,�ݨ�
function sync_data()
{
	global $DB_SERVER, $DB_LOGIN, $DB,$DB_PASSWORD;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
			$error = "��Ʈw�s�����~!!";
			return $error;
	}

	//���o�Ҧ��ҵ{a_id
	//$Q0 = "SELECT * FROM course";
	
	
	//���o��Ǵ��Ҧ��ҵ{
	$Q0 = "SELECT DISTINCT c.* FROM course as c, teach_course as tc, this_semester as ts 
	       WHERE tc.course_id = c.a_id 
	       AND tc.year=ts.year AND tc.term=ts.term";

	//���w��@�ҵ{a_id
	$Q0 = "SELECT * FROM course where a_id='35191'";

	if(!($rs0 = mysql_db_query($DB,$Q0)))
	{
		$error = "mysql��ƮwŪ�����~!!";
		return "$error $Q0<br>";
	}
	$realcount=0;
	$count = 0;
	$temp = -1;
	$total = mysql_num_rows($rs0);
	echo "�`�@ $total ����<br>";
	ob_end_flush();
	ob_implicit_flush(1);
	
	while($rows0 = mysql_fetch_array($rs0))
	{
		$count++;	
		$p = number_format((100*$count)/$total, 2);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"�T�{�Χ�s���A�еy�J $p%\" ; </script>";
		}
		$temp = $p;
		
		echo "<br>�ҵ{ID: ".$rows0['a_id']." �W��: ".$rows0['name']."<br>";
		//���o��Ǵ��A�C�ӽҵ{���ǥͭ׽Ҹ��, �[�J���ץ�(�@�~�B����)�A�ݨ��|�ݽT�{
		$Q1 = "SELECT tc.student_id FROM take_course as tc, this_semester as ts WHERE tc.course_id = '".$rows0['a_id']."' and tc.year = ts.year and tc.term = ts.term and tc.credit=1";
		if(!($rs1 = mysql_db_query($DB,$Q1)))
		{
			$error = "mysql��ƮwŪ�����~!!";
			return "$error $Q1<br>";
		}
		
		$is_add_homework=0;
		$is_add_exam=0;
		$is_add_questionary=0;
		while($rows1 = mysql_fetch_array($rs1)){
	
			//���o�ҵ{���@�~		
			$Q2 = "select * from homework";
			if(!($rs2 = mysql_db_query($DB.$rows0['a_id'],$Q2)))
			{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Q2<br>";
			}
			while($rows2 = mysql_fetch_array($rs2)){
				if(!isUserInHandin_Homework($rows0['a_id'], $rows2['a_id'], $rows1['student_id']) ){						
						$Q21 = "insert into handin_homework ( homework_id, student_id ) values ('".$rows2['a_id']."', '".$rows1['student_id']."')";
						if ( !($result21 = mysql_db_query( $DB.$rows0['a_id'], $Q21 ) ) ) 
						{									
							echo "�s�W���� $Q21<br>";					
						}
						$is_add_homework++;
				}				
			}
			
			//���o�ҵ{������
			$Q2 = "select * from exam";
			if(!($rs2 = mysql_db_query($DB.$rows0['a_id'],$Q2)))
			{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Q2<br>";
			}
			while($rows2 = mysql_fetch_array($rs2)){
				if(!isUserInTakeExam($rows0['a_id'], $rows2['a_id'], $rows1['student_id']) ){						
						$Q21 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$rows2['a_id']."', '".$rows1['student_id']."','-1')";
						if ( !($result21 = mysql_db_query( $DB.$rows0['a_id'], $Q21 ) ) ) 
						{		
							echo "�s�W���� $Q21<br>";						
						}
						$is_add_exam++;
				}				
			}
			
			//���o�ҵ{���ݨ�
			$Q2 = "select * from questionary";
			if(!($rs2 = mysql_db_query($DB.$rows0['a_id'],$Q2)))
			{
				$error = "mysql��ƮwŪ�����~!!";
				return "$error $Q2<br>";
			}
			while($rows2 = mysql_fetch_array($rs2)){
				if(!isUserInTakeQuestionary($rows0['a_id'], $rows2['a_id'], $rows1['student_id']) ){						
						$Q21 = "insert into take_questionary (q_id,student_id) values ('".$rows2['a_id']."', '".$rows1['student_id']."')";
						if ( !($result21 = mysql_db_query( $DB.$rows0['a_id'], $Q21 ) ) ) 
						{		
							echo "�s�W���� $Q21<br>";						
						}
						$is_add_questionary++;
				}				
			}
			
		}
		if($is_add_homework!=0 || $is_add_exam!=0 || $is_add_questionary!=0){
			$realcount++;
			echo "�׽ҤH��: ".mysql_num_rows($rs1).",  ��s���p = �@�~: $is_add_homework  ����: $is_add_exam  �ݨ�: $is_add_questionary <br>";
		}
					
	}
	echo "<BR>��ڧ�s $realcount ���<BR>";
	return -1;
}


function isUserInCourse($course_id , $student_id){
  global $DB_SERVER, $DB_LOGIN, $DB,$DB_PASSWORD;
  $Q1 = "SELECT homework_id FROM handin_homework WHERE student_id='".$student_id."'";
  $rs = mysql_db_query($DB.$course_id, $Q1);
   if(mysql_num_rows($rs) == 0 ){
        return false;
   }
   else{
        return true;
   }
}
		
function isUserInHandin_Homework($course_id , $homework_id , $student_id){
  global $DB_SERVER, $DB_LOGIN, $DB,$DB_PASSWORD;
  $Q1 = "SELECT homework_id FROM handin_homework WHERE homework_id='".$homework_id."' AND student_id='".$student_id."'";
  $rs = mysql_db_query($DB.$course_id, $Q1);
   if(mysql_num_rows($rs) == 0 ){
        return false;
   }
   else{
        return true;
   }
}

function isUserInTakeExam($course_id , $exam_id , $student_id){
  global $DB_SERVER, $DB_LOGIN, $DB,$DB_PASSWORD;
  $Q1 = "SELECT exam_id FROM take_exam WHERE exam_id='".$exam_id."' AND student_id='".$student_id."'";
  $rs = mysql_db_query($DB.$course_id, $Q1);
   if(mysql_num_rows($rs) == 0 ){
        return false;
   }
   else{
        return true;
   }
}  

function isUserInTakeQuestionary($course_id , $q_id , $student_id){
  global $DB_SERVER, $DB_LOGIN, $DB,$DB_PASSWORD;
  $Q1 = "SELECT q_id FROM take_questionary WHERE q_id='".$q_id."' AND student_id='".$student_id."'";
  $rs = mysql_db_query($DB.$course_id, $Q1);
   if(mysql_num_rows($rs) == 0 ){
        return false;
   }
   else{
        return true;
   }
}

?>

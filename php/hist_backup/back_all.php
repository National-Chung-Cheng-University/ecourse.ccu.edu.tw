<?php
require 'fadmin.php';
include("excelwriter.inc.php");
?>
<html>
<head>
<title>�Ƥ���~�׸��--�j���B�Ч��B���Z�B�����ݨ�</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<body background = "/images/img/bg.gif"><center>
�ƥ����� -- �j���B�Ч��B���Z�B�����ݨ�
<div><table>
    <tr> 
      <td> 
        <div>
          <font color=#000000>�Ƥ���~�׸��</font>
        </div>
      </td>
    </tr>
</table>
<div id="progress1">
</div>
<BR>
<div id="progress">
</div>

<?php

//******************************�D�n�y�{�}�l*************************************
if (!(isset($PHPSESSID) && session_check_admin($PHPSESSID)) ) {
		show_page( "not_access.tpl" ,"�v�����~");
}	
if( ($error = backup_all()) == 1 ){
	echo "�Ƥ���~�׸�Ƨ���<BR><BR>";
}
else{
	echo "$error<br>";
}

echo "<a href=../check_admin.php>�^�t�κ޲z����</a>";


//******************************backup_all()�}�l****************************************
function backup_all()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) )
	{
		$error = "��Ʈw�s�����~!!";
		return $error;
	}
	
	//����{�b�Ǵ�
	$Q0 = "SELECT * FROM this_semester";
	$result0 = mysql_db_query($DB, $Q0);
	
	if(!$result0){
		$error = "��ƮwŪ�����~!! $Q0";
		return $error;
	}
	
	$array=mysql_fetch_array($result0);
	
	if(!$array){
		$error = "�S���Ǵ����";
		return $error;
	}
	
	$year = $array['year'];
	$term = $array['term'];
	
	//�ƥ��Y���w���~��
	$year = 102;
	$term = 1;
	echo $year."�~��".$term."�Ǵ�<BR>";
	//
	
	//���v�Ϯڥؿ�
	if(!is_dir("../../echistory/")){
		mkdir("../../echistory/", 0700);
	}
	//�Ǧ~��Ƨ�
	if(!is_dir("../../echistory/".$year)){
		mkdir("../../echistory/".$year, 0700);
	}
	//�Ǵ���Ƨ� 1,2
	if(!is_dir("../../echistory/".$year."/".$term)){
		mkdir("../../echistory/".$year."/".$term, 0700);				
	}	
	//�����ݨ���Ƨ� mid_question 
	if(!is_dir("../../echistory/".$year."/".$term."/mid_question")){
		mkdir("../../echistory/".$year."/".$term."/mid_question", 0700);
	}
	
	
	//�N�o�Ǵ��Ҷ}�ҵ{���Ч��Ƥ�
	//$Q1 = "SELECT distinct course_id FROM teach_course WHERE year=".$year." AND term=".$term;
	$Q1 = "SELECT distinct course_id FROM teach_course WHERE year=".$year." AND term=".$term." AND course_id != 28797 ";
	$result1 = mysql_db_query($DB, $Q1);
	if(!$result1){
		$error = "��ƮwŪ�����~!! $Q1";
		return $error;
	}
	
	$count = 0;
	$temp = -1;
	$total = mysql_num_rows($result1);
	echo "�`�@ $total ����<br><BR>";
	ob_end_flush();
	ob_implicit_flush(1);
	
	//log �}��
	$log_fp = fopen("/home/study/logs/course_backup_".$year."_".$term.".log", "a");
	//
	while($row1=mysql_fetch_array($result1))
	{
		$count++;
		$p = number_format((100*$count)/$total, 2);
		if($p>$temp){
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"�ƥ��Ч���Ƥ��A�еy�J $p%\" ; </script>";
		}
		$temp = $p;
		
		$course_id = $row1['course_id'];
		//�ҵ{��Ƨ� course_id
		if(!is_dir("../../echistory/".$year."/".$term."/".$course_id)){
			mkdir("../../echistory/".$year."/".$term."/".$course_id, 0700);
		}		
		//�j����Ƨ� intro
		if(!is_dir("../../echistory/".$year."/".$term."/".$course_id."/intro")){
			mkdir("../../echistory/".$year."/".$term."/".$course_id."/intro", 0700);
		}
		//�Ч���Ƨ� textbook
		if(!is_dir("../../echistory/".$year."/".$term."/".$course_id."/textbook")){
			mkdir("../../echistory/".$year."/".$term."/".$course_id."/textbook", 0700);
		}
		//���Z��Ƨ� grade
		if(!is_dir("../../echistory/".$year."/".$term."/".$course_id."/grade")){
			mkdir("../../echistory/".$year."/".$term."/".$course_id."/grade", 0700);
		}
		
		echo "�}�l�ƥ��ҵ{:$course_id<BR>";
		//�ƥ��l�禡
		//log�_��
		$log_content = "�}�l�ƥ��ҵ{:$course_id\n";
		fwrite($log_fp, $log_content);
		//
		
		//�ҵ{��T
		if( ($error = backup_coursetb($year, $term, $course_id, $log_fp)) == 1 ){
			echo "�ƥ��ҵ{��T����<br>";
		}
		else if($error == -1){
			continue ;
		}
		else{
			return "$error";
		}
		//�j��
		if( ($error = backup_intro($year, $term, $course_id, $log_fp)) == 1 ){
			echo "�ƥ��j������<br>";
		}
		else{
			return "$error";
		}
		//�Ч�
		if( ($error = backup_textbook($year, $term, $course_id, $log_fp)) == 1 ){
			echo "�ƥ��Ч�����<br>";
		}
		else{
			return "$error";
		}
		//���Z
		if( ($error = backup_grade($year, $term, $course_id, $log_fp)) == 1 ){
			echo "�ƥ����Z����<br>";
		}
		else{
			return "$error";
		}
		
		echo "<br>";
	}

	//�����ݨ�
	echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"�ƥ��Ч���Ƨ���$p%�A�}�l�ƥ������ݨ�\" ; </script>";
	echo "�}�l�ƥ������ݨ�<br>";
	if( ($error = backup_mid($year, $term, $log_fp)) == 1 ){
		echo "�ƥ��ݨ�����<br>";
	}
	else{
		return "$error";
	}
	echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
					document.all.progress.innerHTML = \"�ƥ��Ч���Ƨ���$p%�A�ƥ������ݨ�����\" ; </script>";
	//��LOG��
	fclose($log_fp);
	return 1;
}

//******************************backup_coursetb()�}�l****************************************
function backup_coursetb($year, $term, $course_id, $log_fp)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	
	$Q1 = "SELECT c.group_id AS group_id, c.name AS name, c.course_no AS course_no  FROM course c WHERE c.a_id = '".$course_id."'";
	$result1 = mysql_db_query($DB, $Q1);
	
	if(!$result1){
		$error = "��ƮwŪ�����~!! $Q1";
		return $error;
	}

	if($row1 = mysql_fetch_array($result1)){
		$Q2 = "SELECT * FROM hist_course WHERE year = '".$year."' AND term = '".$term."' AND a_id = '".$course_id."'";
		$result2 = mysql_db_query($DB, $Q2);
		if(!$result2){
			$error = "��ƮwŪ�����~!! $Q2";
			return $error;
		}
		//�w�����
		if(	$row2 = mysql_fetch_array($result2)){
			$Q3 = "UPDATE hist_course SET group_id = '".$row1['group_id']."', course_no = '".$row1['course_no']."', name = '".$row1['name']."' WHERE year = '".$year."' AND term = '".$term."' AND a_id = '".$course_id."'";
		}
		//�|�L���
		else{
			$Q3 = "INSERT INTO hist_course (year, term, a_id, group_id, course_no, name) values ( '".$year."', '".$term."', '".$course_id."', '".$row1['group_id']."', '".$row1['course_no']."', '".$row1['name']."') ";
		}
		
		if(!($result3= mysql_db_query($DB, $Q3))){
			$error = "��ƮwŪ�����~!!!$Q3";
			return $error;
		}
			
		//log�_��
		$log_content = "�ƥ��ҵ{:$course_id��hist_course���=$Q3\n";
		fwrite($log_fp,$log_content);
		//
	}
	else{
		echo "���ҵ{���s�b<BR><BR>";
		return -1;
	}
	
	return 1;
}

//******************************backup_intro()�}�l****************************************
function backup_intro($year, $term, $course_id, $log_fp)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	//�N�o�Ǵ����ҵ{�j���ƥ���../../echistory/$year/$term/$courseid/intro/		
	//�P�_�O�_���ҵ{�j��
	$Q1 = "select introduction, name from course where a_id ='".$course_id."'";
	if ( $result1 = mysql_db_query( $DB, $Q1 ) ) {
		$row1 = mysql_fetch_array( $result1 );			
		//�p�Gintroduction���O�Ū��A�ӥB��Ƨ��U���ɮ�
		if( $row1['introduction']!= "" || is_file("../../".$course_id."/intro/index.html") || is_file("../../".$course_id."/intro/index.htm") || is_file("../../".$course_id."/intro/index.doc") || is_file("../../".$course_id."/intro/index.pdf") || is_file("../../".$course_id."/intro/index.ppt") ){	
			//�ƥ���../../echistory/$year/$term/$courseid/intro/
			//�ƥ���Ʈw�̪��ɮסA�ݭn�t�~�B�z
			//echo "$row1[introduction] <br>";
			if($row1['introduction']!= ""){
				 $fp = fopen ("../../echistory/".$year."/".$term."/".$course_id."/intro/index.html", "w");
				 if(!fwrite ($fp,$row1['introduction'])){
					echo("write error!!!");
				 }
				 fclose ($fp);
				 $log_output = shell_exec("cp -rv ../../".$course_id."/intro/*  ../../echistory/".$year."/".$term."/".$course_id."/intro/");			
			}
			//�ƥ���L���u�n�����ƻs�L�h�Y�i
			else{
				$log_output = shell_exec("cp -rv ../../".$course_id."/intro/*  ../../echistory/".$year."/".$term."/".$course_id."/intro/");		
			}
			//log�_��
			$log_content = "$log_output\n";
			fwrite($log_fp,$log_content);
			//
		}
		
	}
	else{
		return $message = "$message - ��ƮwŪ�����~!!";
	}			
	return 1;
}

//******************************backup_textbook()�}�l****************************************
function backup_textbook($year, $term, $course_id, $log_fp)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	$Q1 = "SELECT * FROM chap_title";
	$result1 = mysql_db_query($DB.$course_id, $Q1);
	
	if(!$result1){
		$error = "��ƮwŪ�����~!! $Q1";
		return $error;
	}
	
	while($row1 = mysql_fetch_array($result1)){
		$Q2 = "SELECT * FROM hist_chaptitle WHERE year = '".$year."' AND term = '".$term."' AND course_id = '".$course_id."' AND chap_aid = '".$row1['a_id']."'";
		$result2 = mysql_db_query($DB, $Q2);
		
		if(!$result2){
			$error = "��ƮwŪ�����~!! $Q2";
			return $error;
		}
		
		//�w�����
		if(	$row2 = mysql_fetch_array($result2)){
			$Q3 = "UPDATE hist_chaptitle SET chap_num = '".$row1['chap_num']."', chap_title = '".addslashes($row1['chap_title'])."', sec_num = '".$row1['sect_num']."', sec_title = '".addslashes($row1['sect_title'])."' WHERE year = '".$year."' AND term = '".$term."' AND course_id = '".$course_id."' AND chap_aid ='".$row1['a_id']."'";
		}
		//�|�L���
		else{
			$Q3 = "INSERT INTO hist_chaptitle (year, term, course_id, chap_aid, chap_num, chap_title, sec_num, sec_title) values ( '".$year."', '".$term."', '".$course_id."', '".$row1['a_id']."', '".$row1['chap_num']."', '".addslashes($row1['chap_title'])."', '".$row1['sect_num']."', '".addslashes($row1['sect_title'])."') ";
		}
		
		if(!($result3= mysql_db_query($DB, $Q3))){
			$error = "��ƮwŪ�����~!!!$Q3";
			return $error;
		}
		//log�_��
		$log_content = "�ƥ��ҵ{:$course_id��hist_chaptitle���=$Q3\n";
		fwrite($log_fp,$log_content);
		//
	}

	//�ƻs�Ҧ��Ч��ɮ�
	$cmd = "cp -rv ../../".$course_id."/textbook/* ../../echistory/".$year."/".$term."/".$course_id."/textbook/";
	$output = shell_exec($cmd);
	//log�_��
	$log_content  = "$output\n";  
	fwrite($log_fp,$log_content);
	//
	return 1;
}


//******************************backup_grade()�}�l****************************************
function backup_grade($year, $term, $course_id, $log_fp)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;

	//���X�Ӫ��Ҫ��ǥ�
	$Q1 = "SELECT u.a_id, u.id, u.name FROM user u, take_course tc WHERE u.a_id=tc.student_id AND tc.course_id='$course_id' and tc.year='$year' and tc.term='$term' and tc.credit = '1' ORDER BY u.id";
	if ( !($result1 = mysql_db_query( $DB, $Q1 ) ) ) {
		$error = "��ƮwŪ�����~!!!$Q1";
		return $error;
	}
	//�P�_�O�_���ǥ͡A�p�G�S���N���@
	$num1 = mysql_num_rows($result1);
	if ( $num1 != 0 ) {
		$countdata[0][0] = $num1;
		//��Xexam�@���W�١@�ʤ���@a_id is_online �p�G��public �B�ɶ�����0
		$Q2 = "SELECT name,percentage,a_id , is_online FROM exam where ( public = '1' or (end_time != '00000000000000' && beg_time <= ".date("YmdHis")." ) ) ORDER BY a_id";
		if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
			$error = "��ƮwŪ�����~!!!$Q2";
			return $error;
		}
		//��X�@homework���W�١@�ʤ���@a_id 
		$Q3 = "SELECT name,percentage,a_id FROM homework where public = '1' or public = '3' ORDER BY a_id";
		if ( !($result3 = mysql_db_query( $DB.$course_id, $Q3 ) ) ) {
			$error = "��ƮwŪ�����~!!!$Q3";
			return $error;
		}
/*		$Q31 = "SELECT name,percentage,a_id FROM coop where ( public = '1' or (end_time != '00000000000000' && beg_time <= ".date("YmdHis")." ) ) ORDER BY a_id";
		if ( !($result31 = mysql_db_query( $DBC.$course_id, $Q31 ) ) ) {
			$error = "��ƮwŪ�����~!!!$Q31";
			return $error;
		}
*/
		$num_t = mysql_num_rows($result2);
		$num_h = mysql_num_rows($result3);
//		$num_c = mysql_num_rows($result31);
        //��ӥu�n���@�Ӥ��� 0 , �N�i�h��
		if(($num_t != 0)||($num_h != 0)) {//||($num_c != 0)) {
			//�U��
			$total_score = 0;
			$i = 0;
			while ( $rows = mysql_fetch_array($result2) ) {
				$max = 0;
				$min = 100;
				//�� take_exam ��Xexam_id=$rows[2]�����Z
				$Q4 = "SELECT grade FROM take_exam WHERE exam_id = '$rows[2]'";
				if ( !($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) ) {
					$error = "��ƮwŪ�����~!!!$Q4";
					return $error;
				}
				$stu_num = mysql_num_rows($result4);
				$total = 0;
				//���X�̰����̧C��, �٦��� exam�@���`��
				while ( $row = mysql_fetch_array($result4) ) {
					if ( $row[0] != "" && $row[0] != "-1" ) { //�����Z
						$total = $total + $row[0];
						if ( (int)$row[0] < $min )
							$min = (int)$row[0];
						if ( (int)$row[0] > $max )
							$max = (int)$row[0];
					}
					else
						$stu_num --;
				}
				if( $stu_num != 0 ) {
				//0 ���� 3 ���Ĥ��Ƥ�� 4 �зǮt 5 is_online 6 a_id 7 exam or homework
					$countdata[0][ $i + 2 ] = $total/$stu_num;
					$countdata[3][ $i + 2 ] = $stu_num;
					$total_squire = 0;
					$Q4 = "SELECT grade FROM take_exam WHERE exam_id ='$rows[2]'";
					if ( !($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) ) {
						$error = "��ƮwŪ�����~!!!$Q4";
						return $error;
					}
					while ( $row = mysql_fetch_array($result4) ) {
						if ( $row[0] != "" && $row[0] != "-1" ) {
							$total_squire += pow( $row[0]-$countdata[0][ $i + 2 ], 2 );
						}
					}
					
					$std = sqrt($total_squire/$stu_num);
					$countdata[4][ $i + 2 ] = $std;
				}
				else {
					$countdata[0][ $i + 2 ] = "�@";
					$countdata[3][ $i + 2 ] = 0;
					$countdata[4][ $i + 2 ] = 0;
				}
				$countdata[5][ $i + 2 ] = $rows[3];
				$countdata[6][ $i + 2 ] = $rows[2];
				$countdata[7][ $i + 2 ] = 0;
				
				if ( $total == 0 ){
					$min = 0;
				}
				$countdata[1][ $i + 2 ] = $max;
				$countdata[2][ $i + 2 ] = $min;
				$i++;
			}
			while ( $rows = mysql_fetch_array($result3) ) {
				$max = 0;
				$min = 100;
				//��handin_homework����X homework_id = $rows[2] �����Z
				$Q4 = "SELECT grade FROM handin_homework WHERE homework_id ='$rows[2]'";
				if ( !($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) ) {
					$error = "��ƮwŪ�����~!!!$Q4";
					return $error;
				}
				$stu_num = mysql_num_rows($result4); //homework���Z���ƶq
				$total = 0;
				//�o��@�~���Z���̰����P�̧C��
				while ( $row = mysql_fetch_array($result4) ) {
					if ( $row[0] != "" && $row[0] != "-1" ) {
						$total = $total + $row[0];
						if ( (int)$row[0] < $min )
							$min = (int)$row[0];
						if ( (int)$row[0] > $max )
							$max = (int)$row[0];
					}
					else
						$stu_num --;
				}
				if( $stu_num != 0 ) { //�ǥͤH�Ƥ��� 0
					$countdata[0][ $i + 2 ] = $total/$stu_num; //��@�~����
					$countdata[3][ $i + 2 ] = $stu_num; //�ǥͤH��
					$total_squire = 0;
					//��handin_homework����X homework_id = $rows[2] �����Z
					$Q4 = "SELECT grade FROM handin_homework WHERE homework_id ='$rows[2]'";
					if ( !($result4 = mysql_db_query( $DB.$course_id, $Q4 ) ) ) {
						$error = "��ƮwŪ�����~!!!$Q4";
						return $error;
					}
					while ( $row = mysql_fetch_array($result4) ) {
						if ( $row[0] != "" && $row[0] != "-1" ) {
							$total_squire += pow( $row[0]-$countdata[0][ $i + 2 ], 2 );
						}
					}
					$std = sqrt($total_squire/$stu_num);
					$countdata[4][ $i + 2 ] = $std;
				}
				else {
					$countdata[0][ $i + 2 ] = "�@";
					$countdata[3][ $i + 2 ] = 0;
					$countdata[4][ $i + 2 ] = 0;
				}
				$countdata[5][ $i + 2 ] = 0;
				$countdata[6][ $i + 2 ] = $rows[2];
				$countdata[7][ $i + 2 ] = 1;
				
				if ( $total == 0 )
					$min = 0;
				$countdata[1][ $i + 2 ] = $max;
				$countdata[2][ $i + 2 ] = $min;
				$i++;
			}
			/*
			while ( $rows = mysql_fetch_array($result31) ) {
				$max = 0;
				$min = 100;
				$Q4 = "SELECT grade FROM take_coop WHERE case_id = '$rows[2]'";
				if ( !($result4 = mysql_db_query( $DBC.$course_id, $Q4 ) ) ) {
					$error = "��ƮwŪ�����~!!!$Q4";
					return $error;
				}
				$stu_num = mysql_num_rows($result4);
				$total = 0;

				while ( $row = mysql_fetch_array($result4) ) {
					if ( $row[0] != "" && $row[0] != "-1" ) {
						$total = $total + $row[0];
						if ( (int)$row[0] < $min )
							$min = (int)$row[0];
						if ( (int)$row[0] > $max )
							$max = (int)$row[0];
					}
					else
						$stu_num --;
				}
				if( $stu_num != 0 ) {
					//0 ���� 3 ���Ĥ��Ƥ�� 4 �зǮt 5 is_online 6 a_id 7 exam or homework
					$countdata[0][ $i + 2 ] = $total/$stu_num;
					$countdata[3][ $i + 2 ] = $stu_num;
					$total_squire = 0;
					$Q4 = "SELECT grade FROM take_coop WHERE case_id ='$rows[2]'";
					if ( !($result4 = mysql_db_query( $DBC.$course_id, $Q4 ) ) ) {
						$error = "��ƮwŪ�����~!!!$Q4";
						return $error;
					}
					while ( $row = mysql_fetch_array($result4) ) {
						if ( $row[0] != "" && $row[0] != "-1" ) {
							$total_squire += pow( $row[0]-$countdata[0][ $i + 2 ], 2 );
						}
					}
					
					$std = sqrt($total_squire/$stu_num);
					$countdata[4][ $i + 2 ] = $std;
				}
				else {
					$countdata[0][ $i + 2 ] = "�@";
					$countdata[3][ $i + 2 ] = 0;
					$countdata[4][ $i + 2 ] = 0;
				}
				$countdata[5][ $i + 2 ] = $rows[3];
				$countdata[6][ $i + 2 ] = $rows[2];
				$countdata[7][ $i + 2 ] = 2;
				
				if ( $total == 0 )
					$min = 0;
				$countdata[1][ $i + 2 ] = $max;
				$countdata[2][ $i + 2 ] = $min;
				$i++;
			}*/
			
			//�`��
			$countdata[0][1] = $i;
			$max = 0;
			$min = 100;
			$l = $num1;
			$j = 0;
			$scordate[0][0] = 0;
			while ( $row1 = mysql_fetch_array($result1) ) {
				$real_grade = 0;
				$mark = 0;
				$scordata[$j][0] = $row1[1];//�Ǹ�
				$scordata[$j][1] = $row1[2];//�m�W
				$k = 2;
				$Q5 = "SELECT te.grade,e.percentage FROM exam e,take_exam te WHERE e.a_id = te.exam_id AND te.student_id = '$row1[0]' and ( e.public = '1' or (e.end_time != '00000000000000' && e.beg_time <= ".date("YmdHis")." ) ) order by e.a_id";
				if ( !($result5 = mysql_db_query( $DB.$course_id, $Q5 ) ) ) {
					$error = "��ƮwŪ�����~!!!$Q5";
					return $error;
				}				
				while ( $row2 = mysql_fetch_array($result5) ) {
					//if ( $row2[0] != "" && $row2[0] != "-1" && $row2[0] != null && $row2[1] != 0 ) {//20060609jp    0%���|���
					if ( $row2[0] != "" && $row2[0] != "-1" && $row2[0] != null ) {//20060609jp�@0%�|���
						$real_grade = $real_grade + $row2[0]*$row2[1]/100;
						$scordata[$j][$k] = $row2[0];//�C�ӤH���Ҧ�����
						$mark = 1;
					}
					else {
						$scordata[$j][$k] = " ";
					}
					$k ++;
				}

				$Q6 = "SELECT hh.grade,h.percentage FROM homework h, handin_homework hh WHERE h.a_id = hh.homework_id AND hh.student_id='$row1[0]' and (h.public = '1' or h.public = '3') order by h.a_id";
				if ( !($result6 = mysql_db_query( $DB.$course_id, $Q6 ) ) ) {
					$error = "��ƮwŪ�����~!!!$Q6";
					return $error;
				}
				while ( $row3 = mysql_fetch_array($result6) ) {
					if ( $row3[0] != "" && $row3[0] != "-1" && $row3[0] != null && $row3[1] != 0 ) {//20060609jp
						$real_grade = $real_grade + $row3[0]*$row3[1]/100;
						$scordata[$j][$k] = $row3[0];
						$mark = 1;
					}
					else {
						$scordata[$j][$k] = " ";
					}
					$k ++;
				}
				/*
				$Q61 = "SELECT tc.grade,c.percentage FROM coop c, take_coop tc WHERE c.a_id = tc.case_id AND tc.student_id='$row1[0]' and c.public = '1' order by c.a_id";
				if ( !($result61 = mysql_db_query( $DBC.$course_id, $Q61 ) ) ) {
					$error = "��ƮwŪ�����~!!!$Q61";
					return $error;
				}
				while ( $row31 = mysql_fetch_array($result61) ) {
					if ( $row31[0] != "" && $row31[0] != "-1" ) {
						$real_grade = $real_grade + $row31[0]*$row31[1]/100;
						$scordata[$j][$k] = $row31[0];
						$mark = 1;
					}
					else {
						$scordata[$j][$k] = " ";
					}
					$k ++;
				}
				*/
				if ( $mark != 0 )
					$scordata[$j][$k] = $real_grade;
				else
					$scordata[$j][$k] = " ";
				
				if ( $mark != 0 )
				{
					$total_score = $total_score + $real_grade;
					if ( $real_grade < $min )
						$min = $real_grade;
					if ( $real_grade > $max )
						$max = $real_grade;
				}
				else
					$l --;
				$j ++;
			}
			if ( $l != 0 ) {
				$countdata[0][ $i + 2 ] = $total_score/$l;
				$countdata[3][ $i + 2 ] = $l;
				$total_squire = 0;
				for ( $m = 0 ; $m < $j ; $m ++ ) {
					if ( $scordata[$m][$k] != " " ) {
						$total_squire += pow( $scordata[$m][$k]-$countdata[0][ $i + 2 ], 2 );
					}
				}
				$std = sqrt($total_squire/$l);
				$countdata[4][ $i + 2 ] = $std;
			}
			else {
				$countdata[0][ $i + 2 ] = "�@";
				$countdata[3][ $i + 2 ] = 0;
			}
			if ( $total_score == 0 )
				$min = 0;
			$countdata[1][ $i + 2 ] = $max;
			$countdata[2][ $i + 2 ] = $min;

			if( ($error = write_to_file($year, $term, $course_id, $scordata, $countdata, $log_fp)) != 1 ){
				return $error;
			}
		}
	}
		
	return 1;
}
//******************************backup_grade()����****************************************

//******************************backup_mid()�}�l****************************************
//�s��--�u�d��zip�ɦs���s�b �s�b���ܴN�ƻs�@���L�h���v�� �_�h���L
function backup_mid($year, $term, $log_fp)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	
	$location1 = "../mid_questionary/".$year."_0".$term.".tar";
	$location2 = "../../echistory/".$year."/".$term."/mid_question/".$year."_0".$term.".tar";
	
	if(is_file($location1)){
		$log_output = shell_exec("cp -v ".$location1." ".$location2);
	}
	else{
		$log_output =  "�����ݨ��ɮ� ".$year."_0".$term.".tar ���s�b";
		echo $log_output."<BR>";
	}
	
	//log�_��
	$log_content = "$log_output\n";
	fwrite($log_fp,$log_content);
	//
	return 1;
	
}
/*
function backup_mid($year, $term, $log_fp)
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD;
	 
	//
	//�N�Ҧ��t�Ҷ}���ҵ{���g�b�P�@��Excel�ɡA�M��� �ǰ|->�t�� �إ߸�Ƨ�
	//�̫����Y�_�ӡA���аȳB���H�o�H�U���C
	//

	$Q0 = "select * from this_semester";
	$result0 = mysql_db_query($DB, $Q0);
	$rows0 = mysql_fetch_array($result0);

	$Q1 = "select a_id from mid_subject where year='".$year."' and term='".$term."'";
	$result1 = mysql_db_query($DB, $Q1);
	$rows1 = mysql_fetch_array($result1);	
		
	if(!is_dir("../../echistory/".$year."/".$term."/mid_question/".$year."_0".$term."�����ݨ�")){
		mkdir("../../echistory/".$year."/".$term."/mid_question/".$year."_0".$term."�����ݨ�");
	}
	
	//��ǰ|
	$Q6 = "select a_id, name from course_group where parent_id=1 and a_id!=98"; //98�����եΨt��
	$result6 = mysql_db_query($DB, $Q6);
	while($rows6 = mysql_fetch_array($result6))
	{
		if(!is_dir("../../echistory/".$year."/".$term."/mid_question/".$year."_0".$term."�����ݨ�/".$rows6[name])){
			mkdir("../../echistory/".$year."/".$term."/mid_question/".$year."_0".$term."�����ݨ�/".$rows6[name]);
		}
		//��t��
		$Q7 = "select a_id, name from course_group where parent_id='".$rows6[a_id]."' and a_id!=92 order by name";
		$result7 = mysql_db_query($DB, $Q7);
		while($rows7 = mysql_fetch_array($result7))
		{
			if(is_file("../../echistory/".$year."/".$term."/mid_question/".$year."_0".$term."�����ݨ�/".$rows6[name]."/".$year."_0".$term."_".$rows7[name].".xls")){
				unlink("../../echistory/".$year."/".$term."/mid_question/".$year."_0".$term."�����ݨ�/".$rows6[name]."/".$year."_0".$term."_".$rows7[name].".xls");//�Y�w���ɮ׫h�R��
			}
			
			$excel=new ExcelWriter("../../echistory/".$year."/".$term."/mid_question/".$year."_0".$term."�����ݨ�/".$rows6[name]."/".$year."_0".$term."_".$rows7[name].".xls");
			if($excel==false){
				return $excel->error;
			}
			$data=array("�t��","�ҵ{�s��","�ҵ{�W��","�½ұЮv","�׽ҤH��","��g�H��","��g�v","���D�@","���D�G");
			$excel->writeLine($data);
			
			$Q8 = "select distinct c.a_id, c.name, c.course_no from course c, teach_course tc
						 where c.group_id='$rows7[a_id]'
						 and c.a_id=tc.course_id
						 and tc.year='$year'
						 and tc.term='$term'";
			$result8 = mysql_db_query($DB, $Q8);
			while($rows8 = mysql_fetch_array($result8)) //��ҵ{ while($row8)
			{			
				//--�½ұЮv(1~�h��)
				$name="";
				$Q9 = "select distinct u.name FROM user u , teach_course tc where tc.course_id = '".$rows8['a_id']."' and tc.teacher_id = u.a_id and u.authorization='1' and tc.year='".$year."' and tc.term='".$term."'";
				if ( !($result9 = mysql_db_query( $DB, $Q9 ) ) ) {
					return $message = "$message - ��ƮwŪ�����~9!!";
				}
				while ($rows9 = mysql_fetch_array($result9))
				{
					if ( $rows9['name'] != NULL )
					{
						$name = $name.$rows9['name']." ";
					}
				}
				
				//�׽ҤH��
				$stu_no=0;
				$Q_tmp="select count(tc.student_id) as stu_no from take_course tc, user u where tc.year='$year' and tc.term='$term' and tc.student_id=u.a_id and u.disable='0' and tc.course_id=$rows8[a_id]";				
				if ( !($rs_temp = mysql_db_query( $DB, $Q_tmp ) ) ) {
					return $message = "$message - ��ƮwŪ�����~-�׽ҤH��!!";
				}
				if($rw_tmp = mysql_fetch_array($rs_temp))
				{
					$stu_no = $rw_tmp['stu_no'];				
				}
				
				//��g�H��
				$join_no=0;
				$Q_tmp="select count(student_id) as join_no FROM mid_ans where year='$year' and term='$term'";				
				if ( !($rs_temp = mysql_db_query( $DB.$rows8[a_id], $Q_tmp ) ) ) {
					return $message = "$message - ��ƮwŪ�����~-��g�H��!!";
				}
				if($rw_tmp = mysql_fetch_array($rs_temp))
				{
					$join_no = $rw_tmp['join_no'];				
				}
				//��g�v
				$ratio1=0;
				if ($stu_no!=0)
					$ratio1=number_format((($join_no/$stu_no)*100),2); 
				
				//Ū���ӽҵ{���ݨ����G�A�v���g�Jexcel�ɮ�
				$Q10 = "SELECT q1,q2 FROM mid_ans where year='$year' and term='$term'";
				
				if ( !($result10 = mysql_db_query( $DB.$rows8[a_id], $Q10 ) ) ) {
					return "��ƮwŪ�����~10!!";
				}
				//----��ӽҵ{�L�H��g�ݨ�
				if(!mysql_num_rows($result10)) 
				{
					$excel->writeRow();
					$excel->writeCol($rows7[name]);			//�t��
					$excel->writeCol($rows8[course_no]);	//�ҵ{�s��
					$excel->writeCol($rows8[name]);			//�ҵ{�W��
					$excel->writeCol($name);					//�½ұЮv
					$excel->writeCol($stu_no);					//�׽ҤH��
					$excel->writeCol($join_no);				//��g�H��
					$excel->writeCol($ratio1);					//��g�v
					$excel->writeCol("");						//���D�@
					$excel->writeCol("");						//���D�G
				}	
				//----��ӽҵ{���ݨ���g���
				else {
					while($rows10 = mysql_fetch_array( $result10 ) )
					{
						$excel->writeRow();
						$excel->writeCol($rows7[name]);			//�t��
						$excel->writeCol($rows8[course_no]);	//�ҵ{�s��
						$excel->writeCol($rows8[name]);			//�ҵ{�W��
						$excel->writeCol($name);					//�½ұЮv
						$excel->writeCol($stu_no);					//�׽ҤH��
						$excel->writeCol($join_no);				//��g�H��
						$excel->writeCol($ratio1);					//��g�v
						$excel->writeCol($rows10[q1]);			//���D�@
						$excel->writeCol($rows10[q2]);			//���D�G				
					}
				}		
			}	//end of ��ҵ{ while($row8)
			$excel->close();
			//log�_��
			$log_content = "�s�@�����ݨ����v�ɮר�../../echistory/".$year."/".$term."/mid_question/".$year."_0".$term."�����ݨ�/".$rows6[name]."/".$year."_0".$term."_".$rows7[name].".xls\n";
			fwrite($log_fp,$log_content);
			//
		} //end of ��t��
	} //end of ��ǰ|
	//
	/���Y����U�Ǵ������ݨ���tar��
	//
	$location1 = $year."_0".$term."�����ݨ�";
	$location2 = $year."_0".$term;
	$log_output = exec("cd ../../echistory/$year/$term/mid_question/ ;tar -cvf $location2.tar $location1/*");
	//log�_��
	$log_content = "$log_output\n";
	fwrite($log_fp,$log_content);
	//
	return 1;
}
*/
//******************************backup_mid()����****************************************






//***********************************write to file******************************************

function write_to_file ($year, $term, $course_id, $scordata, $countdata, $log_fp) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $version;
	
	$Q1 = "SELECT name,percentage,a_id FROM exam where  ( public = '1' or (end_time != '00000000000000' && beg_time <= ".date("YmdHis")." ) ) ORDER BY a_id";
	if ( !($result1 = mysql_db_query( $DB.$course_id, $Q1 ) ) ) {
		$error = "��ƮwŪ�����~!!!$Q1";
		return $error;
	}
	$Q2 = "SELECT name,percentage,a_id FROM homework where public = '1' or public = '3' ORDER BY a_id";
	if ( !($result2 = mysql_db_query( $DB.$course_id, $Q2 ) ) ) {
		$error = "��ƮwŪ�����~!!!$Q2";
		return $error;
	}
	/*
	$Q21 = "SELECT name,percentage,a_id FROM coop where ( public = '1' or (end_time != '00000000000000' && beg_time <= ".date("YmdHis")." ) ) ORDER BY a_id";
	if ( !($result21 = mysql_db_query( $DBC.$course_id, $Q21 ) ) ) {
		$error = "��ƮwŪ�����~!!!$Q21";
		return $error;
	}
	*/

	$file_name="../../echistory/".$year."/".$term."/".$course_id."/grade/score_".$course_id.".xls";
	
	if(file_exists($file_name)){
		unlink($file_name);
	}
	
	$file=fopen("$file_name","w");
	if($version == "C"){
		fwrite($file,"�Ǹ�\t�m�W");
	}
	else{
		fwrite($file,"Student ID\tName");
	}
	$i = 0;
	while ( $row = mysql_fetch_array($result1) ) {
		fwrite($file,"\t$row[0]");
		$i ++;
	}
	while ( $row = mysql_fetch_array($result2) ) {
		fwrite($file,"\t$row[0]");
		$i ++;
	}
	/*
	while ( $row = mysql_fetch_array($result21) ) {
		fwrite($file,"\t$row[0]");
		$i ++;
	}
	*/
	if($version == "C"){
		fwrite($file,"\t�`���Z\n");
	}
	else{
		fwrite($file,"\tTotal Score\n");
	}
		
	$i = 0;

	for ( $j = 0 ; $j < $countdata[0][0]; $j ++ )
	{
		for($k = 0; $k <= $countdata[0][1] + 2; $k ++ )
		{
			if($k == 0 || $k == 1 ) {
				if ( $k == 0 ) {
					//�ƦW
					if ( $j == 0 ) {
						$grade = $scordata[$j][0];
						$i ++;
					}
					else if ( $grade == $scordata[$j][0] ) {
						$m ++;
					}
					else {
						$i = $i + $m + 1;
						$m = 0;
						$grade = $scordata[$j][0];
					}
					if ( $scordata[$j][0] == " " ) {
						$i = 0;
					}
					if ( $i == 0 ) {
						$i = " ";
					}					
					fwrite($file,$scordata[$j][$k]);
				}
				else {					
					fwrite($file,"\t".$scordata[$j][$k]);
				}
			}
			else {
				fwrite($file,"\t".$scordata[$j][$k]);
			}
		}
		fwrite($file,"\n");
	}
	if($version == "C") {
		fwrite($file,"�@\t�̰���");		
	}
	else {
		fwrite($file,"�@\tTOP");
	}
	for( $j = 0; $j < $countdata[0][1] + 1; $j++ )
	{
		fwrite($file,"\t".$countdata[1][$j + 2]);
	}
	fwrite($file,"\n");
	if($version == "C") {
		fwrite($file,"�@\t�̧C��");		
	}
	else {
		fwrite($file,"�@\tWorst");
	}
	for( $j = 0; $j < $countdata[0][1] + 1; $j++ )
	{
		fwrite($file,"\t".$countdata[2][$j + 2]);
	}
	fwrite($file,"\n");
	if($version == "C") {
		fwrite($file,"�@\t����");		
	}
	else {
		fwrite($file,"�@\tAverage");
	}

	for( $j = 0; $j < $countdata[0][1] + 1; $j++ )
	{
		$point = strpos( $countdata[0][$j + 2], '.' );
		$point2 = strpos( $countdata[0][$j + 2], '/' );
		fwrite($file,"\t".substr($countdata[0][$j + 2], 0, $point+3));
	}
	fclose($file);
	
	//log�_��
	$log_content = "�ƻs���Z�ɮר�$file_name\n";
	fwrite($log_fp,$log_content);
	//
	
	return 1;
}

?>

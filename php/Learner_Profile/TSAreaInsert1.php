<?php
/**************************/
/*�ɦW:TSAreaInsert1.php*/
/*����:�h���ǥ͸�ƿ�J*/
/*�����ɮ�:*/
/*TSAreaInsert2.php*/
/*************************/
require 'fadmin.php';
if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"�v�����~");
	exit;
}
if($check != 2)
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"�A�S���v���ϥΦ��\��");
		exit;
	}
	else {
		show_page( "not_access.tpl" ,"You have No Permission!!");
		exit;
	}
}
if( isset($stdlist) )
{
	if ( $stdlist == "" ) {
		if($version=="C")
			$message = "�L������!!!";
		else
			$message = "No data!!!";
		show_page_d ( $message );
	}
	else
		handl_data();
}
else
	show_page_d ( );

function handl_data() {
	global $stdlist, $version, $course_year, $course_term;
	$student = explode("\r\n",$stdlist);

	//�ˬd��J�榡�O�_���T
	$testExpression = true;
	$testEmpty = false;

	for( $i = 0 ; $i < count($student) ; $i++ )
	{
		$expression=ereg(",[[:alnum:]]+#$",$student[$i]) || $student[$i]=="";
		$commaCount=substr_count($student[$i],",");
		if($student[$i]=="")
			$commaPos=-1;
		else
			$commaPos=strpos($student[$i],",");
		$wellCount=substr_count($student[$i],"#");
		if(!$expression || $commaCount>1 || $commaPos==0 || $wellCount>1)
			$testExpression=false;
		if(!$student[$i]=="")
			$testEmpty=true;
	}
	if(!$testExpression || !$testEmpty)
	{
		if($version=="C")
			$message = "�榡�i�঳���~,���ˬd!!!";
		else
			$message = "The format may have errors,please check!!!";
		show_page_d ( $message );
	}
	else
	{
		$temp1=implode("",$student);
		$temp2=explode("#",$temp1);
		$recordCount=0;
		for($i=0;$i<count($temp2)-1;$i++)
		{
			$temp3=explode(",",$temp2[$i]);
			$stud_name[]=addslashes($temp3[0]);
			$stud_id[]=$temp3[1];
			$recordCount++;
		}

		//�ˬd�ϥΪ̿�J���Ǹ����O�_������
		$repeat_flag = false;
		$kk = "";
		$repeat=array_count_values($stud_id);
		for(reset($repeat);$key=key($repeat);next($repeat))
		{
			if($repeat[$key]>1)
			{
				$repeat_flag = true;
				if($version=="C")
					$kk .= "�A�ҿ�J���ǥ;Ǹ�:$key �i�঳����,���ˬd!!!<br>";
				else
					$kk .= "ID:$key that you input overlaps,please check!!!<br>";
			}
		}
		if($repeat_flag)
			show_page_d ( $kk );
		else {			
			global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id;
			if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
				$message = "��Ʈw�s�����~!!";
				show_page_d ( $message );
				return;
			}
			for( $i=0; $i< $recordCount; $i++ )
			{
				$Q1 = "Select * From user u,take_course tc Where u.a_id=tc.student_id And tc.course_id='$course_id' And tc.year = '$course_year' And tc.term = '$course_term' And u.id='$stud_id[$i]' and tc.credit='1'";
				if ( !($resultOBJ = mysql_db_query( $DB, $Q1 ) ) ) {
					$message = "��ƮwŪ�����~!!";
					show_page_d ( $message );
					return;
				}
				if(mysql_num_rows($resultOBJ) != 0)
				{
					$existed = true;
					if($version=="C")
						$kk .= "�Ǹ�:$stud_id[$i] ����, �ӵ���Ƥw�s�b!!!<br>";
					else
						$kk .= "ID:$stud_id[$i] overlaps, the record exists!!!<br>";
				}
				else
				{
					add_one_stu($stud_id[$i], $stud_name[$i]);
					$kk .= "�Ǹ�:$stud_id[$i] �s�W����!!!<br>";
					
				}
			}
			show_page_d ( $kk );
			/*
			if($existed)
				show_page_d ( $kk );
			else
			{	
				//�w�ѤW����add_one_stu�s�W�����A�Gadd_stu�Τ���
				//add_stu( $recordCount, $stud_id, $stud_name );
			}
			*/
		}
			
	}
}

//�s�W�浧�ǥ�function
function add_one_stu ($id, $name) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $version, $stdlist, $course_year, $course_term;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "��Ʈw�s�����~!!";
		show_page_d ( $message );
		return;
	}

	
	$Q1 = "Select id,name,authorization From user Where id='".$id."'";
	if ( !$resultOBJ = mysql_db_query( $DB, $Q1) )
	{
		$message = "��ƮwŪ�����~!!";
		show_page_d ( $message );
		return;
	}
	
	if(mysql_num_rows($resultOBJ) == 0 )
	{
    	$Q2 = "Insert Into user (name,id,authorization, forbear) values ('$name','$id','3', '1800')";
		if ( !$resultOBJ = mysql_db_query( $DB, $Q2 ) )
		{
			$message = "��Ʈw�g�J���~1!!";
			show_page_d ( $message );
			return;
		}
		$a_id = mysql_insert_id();
		$Q3 = "Select group_id From course Where a_id='$course_id'";
		if ( !$resultOBJ = mysql_db_query( $DB, $Q3) )
		{
			$message = "��ƮwŪ�����~1!!";
			show_page_d ( $message );
			return;
		}
		$row2 = mysql_fetch_array($resultOBJ);
		$Q4 = "Insert Into take_course (group_id,course_id,student_id,validated, credit, year, term) values ('".$row2['group_id']."','$course_id','".$a_id."','1','1','$course_year','$course_term')";
		if ( !$resultOBJ = mysql_db_query( $DB, $Q4) )
		{
			$message = "��Ʈw�g�J���~2!!";
			show_page_d ( $message );
			return;
		}
		$Q5 = "Select a_id From homework";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q5 ) )
		{
			$message = "��ƮwŪ�����~2!!";
			show_page_d ( $message );
			return;
		}
		while($row3 = mysql_fetch_array($resultOBJ))
		{
			$Q6 = "Insert Into handin_homework (homework_id,student_id) values ('".$row3['a_id']."','".$a_id."')";
			if ( !mysql_db_query( $DB.$course_id, $Q6 ) )
			{
				$message = "��Ʈw�g�J���~3!!";
				show_page_d ( $message );
				return;
			}
		}
		$Q7 = "Select a_id From exam";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) )
		{
			$message = "��ƮwŪ�����~3!!";
			show_page_d ( $message );
			return;
		}
		while($row4 = mysql_fetch_array($resultOBJ))
		{
			$Q8 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$a_id."','-1')";
			if ( !mysql_db_query( $DB.$course_id, $Q8) )
			{
				$message = "��Ʈw�g�J���~4!!";
				show_page_d ( $message );
				return;
			}
		}
/*		
		//coop
		$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
		while($row_coop = mysql_fetch_array ( $resultcoop )) {
			mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$id[$i]."','0')");
			mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$a_id."','-1')");
		}
*/		
	}
	else
	{
		$row = mysql_fetch_array($resultOBJ);
		if ( $row['authorization'] != '3' ) {
			continue;
		}
		if($row['id'] == $id && $row['name'] != $name )
		{
			$Q2 = "Update user Set name='".$name."' Where id='".$id."'";
			if ( !mysql_db_query( $DB, $Q2 ) )
			{
				$message = "��Ʈw��s���~1!!";
				show_page_d ( $message );
				return;
			}
		}
		
		$Q3 = "Select t.student_id, t.credit From take_course t,user u Where t.course_id='$course_id' and t.year = '$course_year' and t.term = '$course_term' and u.id = '".$id."' and t.student_id = u.a_id";
		if ( !$repeatOBJ = mysql_db_query( $DB, $Q3 ) )
		{
			$message = "��ƮwŪ�����~1!!";
			show_page_d ( $message );
			return;
		}
		
		$Q4 = "Select a_id From user Where id='".$id."'";
		if ( !$resultOBJ = mysql_db_query( $DB, $Q4 ) )
		{
			$message = "��ƮwŪ�����~2!!";
			show_page_d ( $message );
			return;
		}
		$row1 = mysql_fetch_array($resultOBJ);
		
		if(mysql_num_rows ( $repeatOBJ ) != 0) {
			if ( $rowrepeat = mysql_fetch_array( $repeatOBJ ) ) {
				if ( $rowrepeat['credit'] == "1" ) {
					continue;
				}
				else {
					$Q3 = "update take_course set credit = '1' where course_id='$course_id' and student_id = '".$rowrepeat['student_id']."' and year = '$course_year' and term = '$course_term'";
					if ( !$repeatOBJ2 = mysql_db_query( $DB, $Q3 ) )
					{
						$message = "��Ʈw��s���~!!";
						show_page_d ( $message );
						return;
					}
				}
			}
			else {
				continue;
			}
		}
		else {
			$Q5 = "Select group_id From course Where a_id='$course_id'";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q5 ) )
			{
				$message = "��ƮwŪ�����~3!!";
				show_page_d ( $message );
				return;
			}
			$row2 = mysql_fetch_array($resultOBJ);
			
			$Q6 = "Insert Into take_course (group_id,course_id,student_id,validated, credit, year, term) values('".$row2['group_id']."','$course_id','".$row1['a_id']."','1','1','$course_year','$course_term')";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q6 ) )
			{
				$message = "��Ʈw�g�J���~1!!";
				show_page_d ( $message );
				return;
			}
		}
		
		$Q7 = "Select a_id From homework";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) )
		{
			$message = "��ƮwŪ�����~4!!";
			show_page_d ( $message );
			return;
		}
		while($row3 = mysql_fetch_array($resultOBJ))
		{
			$Q8 = "Insert Into handin_homework (homework_id,student_id) values('".$row3['a_id']."','".$row1['a_id']."')";
			if ( !mysql_db_query( $DB.$course_id, $Q8 ) )
			{
				$message = "��Ʈw�g�J���~2!!";
				show_page_d ( $message );
				return;
			}
		}
		$Q9 = "Select a_id From exam";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q9 ) )
		{
			$message = "��ƮwŪ�����~5!!";
			show_page_d ( $message );
			return;
		}
		while($row4 = mysql_fetch_array($resultOBJ))
		{
			$Q10 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$row1['a_id']."','-1')";
			if ( !mysql_db_query( $DB.$course_id, $Q10 ) )
			{
				$message = "��Ʈw�g�J���~3!!";
				show_page_d ( $message );
				return;
			}
		}
/*		
		//coop
		$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
		while($row_coop = mysql_fetch_array ( $resultcoop )) {
			mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$id[$i]."','0')");
			mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$row1['a_id']."','-1')");
		}*/
	}
	
	include("Generate_studinfo.php");
	if ( $version =="C" )
		$message = "�ǥͤw�[�J����";
	else
		$message = "Students Add Complete!!!";
	include("Generate_studinfo.php");
	$stdlist = "";
	show_page_d ( $message );
}

function add_stu ( $recordCount, $id, $name ) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $course_id, $version, $stdlist, $course_year, $course_term;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "��Ʈw�s�����~!!";
		show_page_d ( $message );
		return;
	}

	for($i=0 ; $i < $recordCount ; $i++ )
	{
		$Q1 = "Select id,name,authorization From user Where id='".$id[$i]."'";
		if ( !$resultOBJ = mysql_db_query( $DB, $Q1) )
		{
			$message = "��ƮwŪ�����~!!";
			show_page_d ( $message );
			return;
		}
		
		if(mysql_num_rows($resultOBJ) == 0 )
		{
      			$Q2 = "Insert Into user (name,id,authorization, forbear) values ('$name[$i]','$id[$i]','3', '1800')";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q2 ) )
			{
				$message = "��Ʈw�g�J���~1!!";
				show_page_d ( $message );
				return;
			}
			$a_id = mysql_insert_id();
			$Q3 = "Select group_id From course Where a_id='$course_id'";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q3) )
			{
				$message = "��ƮwŪ�����~1!!";
				show_page_d ( $message );
				return;
			}
			$row2 = mysql_fetch_array($resultOBJ);
			$Q4 = "Insert Into take_course (group_id,course_id,student_id,validated, credit, year, term) values ('".$row2['group_id']."','$course_id','".$a_id."','1','1','$course_year','$course_term')";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q4) )
			{
				$message = "��Ʈw�g�J���~2!!";
				show_page_d ( $message );
				return;
			}
			$Q5 = "Select a_id From homework";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q5 ) )
			{
				$message = "��ƮwŪ�����~2!!";
				show_page_d ( $message );
				return;
			}
			while($row3 = mysql_fetch_array($resultOBJ))
			{
				$Q6 = "Insert Into handin_homework (homework_id,student_id) values ('".$row3['a_id']."','".$a_id."')";
				if ( !mysql_db_query( $DB.$course_id, $Q6 ) )
				{
					$message = "��Ʈw�g�J���~3!!";
					show_page_d ( $message );
					return;
				}
			}
			$Q7 = "Select a_id From exam";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) )
			{
				$message = "��ƮwŪ�����~3!!";
				show_page_d ( $message );
				return;
			}
			while($row4 = mysql_fetch_array($resultOBJ))
			{
				$Q8 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$a_id."','-1')";
				if ( !mysql_db_query( $DB.$course_id, $Q8) )
				{
					$message = "��Ʈw�g�J���~4!!";
					show_page_d ( $message );
					return;
				}
			}
/*			
			//coop
			$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
			while($row_coop = mysql_fetch_array ( $resultcoop )) {
				mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$id[$i]."','0')");
				mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$a_id."','-1')");
			}
*/			
		}
		else
		{
			$row = mysql_fetch_array($resultOBJ);
			if ( $row['authorization'] != '3' ) {
				continue;
			}
			if($row['id'] == $id[$i] && $row['name'] != $name[$i] )
			{
				$Q2 = "Update user Set name='".$name[$i]."' Where id='".$id[$i]."'";
				if ( !mysql_db_query( $DB, $Q2 ) )
				{
					$message = "��Ʈw��s���~1!!";
					show_page_d ( $message );
					return;
				}
			}
			
			$Q3 = "Select t.student_id, t.credit From take_course t,user u Where t.course_id='$course_id' and t.year = '$course_year' and t.term = '$course_term' and u.id = '".$id[$i]."' and t.student_id = u.a_id";
			if ( !$repeatOBJ = mysql_db_query( $DB, $Q3 ) )
			{
				$message = "��ƮwŪ�����~1!!";
				show_page_d ( $message );
				return;
			}
			
			$Q4 = "Select a_id From user Where id='".$id[$i]."'";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q4 ) )
			{
				$message = "��ƮwŪ�����~2!!";
				show_page_d ( $message );
				return;
			}
			$row1 = mysql_fetch_array($resultOBJ);
			
			if(mysql_num_rows ( $repeatOBJ ) != 0) {
				if ( $rowrepeat = mysql_fetch_array( $repeatOBJ ) ) {
					if ( $rowrepeat['credit'] == "1" ) {
						continue;
					}
					else {
						$Q3 = "update take_course set credit = '1' where course_id='$course_id' and student_id = '".$rowrepeat['student_id']."' and year = '$course_year' and term = '$course_term'";
						if ( !$repeatOBJ2 = mysql_db_query( $DB, $Q3 ) )
						{
							$message = "��Ʈw��s���~!!";
							show_page_d ( $message );
							return;
						}
					}
				}
				else {
					continue;
				}
			}
			else {
				$Q5 = "Select group_id From course Where a_id='$course_id'";
				if ( !$resultOBJ = mysql_db_query( $DB, $Q5 ) )
				{
					$message = "��ƮwŪ�����~3!!";
					show_page_d ( $message );
					return;
				}
				$row2 = mysql_fetch_array($resultOBJ);
				
				$Q6 = "Insert Into take_course (group_id,course_id,student_id,validated, credit, year, term) values('".$row2['group_id']."','$course_id','".$row1['a_id']."','1','1','$course_year','$course_term')";
				if ( !$resultOBJ = mysql_db_query( $DB, $Q6 ) )
				{
					$message = "��Ʈw�g�J���~1!!";
					show_page_d ( $message );
					return;
				}
			}
			
			$Q7 = "Select a_id From homework";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) )
			{
				$message = "��ƮwŪ�����~4!!";
				show_page_d ( $message );
				return;
			}
			while($row3 = mysql_fetch_array($resultOBJ))
			{
				$Q8 = "Insert Into handin_homework (homework_id,student_id) values('".$row3['a_id']."','".$row1['a_id']."')";
				if ( !mysql_db_query( $DB.$course_id, $Q8 ) )
				{
					$message = "��Ʈw�g�J���~2!!";
					show_page_d ( $message );
					return;
				}
			}
			$Q9 = "Select a_id From exam";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q9 ) )
			{
				$message = "��ƮwŪ�����~5!!";
				show_page_d ( $message );
				return;
			}
			while($row4 = mysql_fetch_array($resultOBJ))
			{
				$Q10 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$row1['a_id']."','-1')";
				if ( !mysql_db_query( $DB.$course_id, $Q10 ) )
				{
					$message = "��Ʈw�g�J���~3!!";
					show_page_d ( $message );
					return;
				}
			}
/*			
			//coop
			$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
			while($row_coop = mysql_fetch_array ( $resultcoop )) {
				mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$id[$i]."','0')");
				mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$row1['a_id']."','-1')");
			}*/
		}
	}
	include("Generate_studinfo.php");
	if ( $version =="C" )
		$message = "�ǥͤw�[�J����";
	else
		$message = "Students Add Complete!!!";
	include("Generate_studinfo.php");
	$stdlist = "";
	show_page_d ( $message );
}

function show_page_d ( $message = "" ) {
	global $version, $stdlist, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	
	if($version=="C")
		$tpl->define(array(main => "TSAreaInsert1_Ch.tpl"));
	else
		$tpl->define(array(main => "TSAreaInsert1_En.tpl"));
	$tpl->assign( SKINNUM , $skinnum );
	$tpl->assign(MESSAGE, $message);
	$tpl->assign(VALUE, $stdlist);
	$tpl->parse(BODY, "main");
	$tpl->FastPrint("BODY");
}

?>

<?php
/**************************/
/*�ɦW:TSInsertFrame1.php*/
/*����:�浧�ǥ͸�ƿ�J*/
/*�����ɮ�:*/
/*TSInsertFrame2.php*/
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
if( isset($stud_name) && isset($stud_id) )//�P�_��ƬO�_��J����
{
	if ( $stud_name == "" || $stud_id == "" ) {
		if($version=="C")
			$message = "�m�W�ξǸ��|����J!!!";
		else
			$message = "Name or ID field is empty!!!";
		show_page_d ( $message );
	}
	else{
		add_stu();
	}
}
else
	show_page_d ( $message );
	
function add_stu() {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $stud_name, $stud_id, $version, $course_id, $action, $Submit, $course_year, $course_term;
	
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		$message = "��Ʈw�s�����~!!";
		show_page_d ( $message );
	}

	//2007/07/24 : �q�����^�Ƿs�W�ǥͬO���ץͩάO��ť�� by intree
	$credit_id = $_POST["nocredit"];

	$Q1 = "Select * From user Where id='$stud_id'";  
	if ( !($resultOBJ1 = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "��ƮwŪ�����~!!";
		show_page_d ( $message );
		return;
	}
  
	if(mysql_num_rows($resultOBJ1) == 0 )
	{
		$Q2 = "Insert Into user (name,id,authorization, forbear) values ('$stud_name','$stud_id','3','1800')";
		if ( !($result = mysql_db_query( $DB, $Q2 ) ) ) {
			$message = "��Ʈw�g�J���~1!!";
			show_page_d ( $message );
			return;
		}
		$a_id = mysql_insert_id();
		$Q4 = "Select group_id From course Where a_id='$course_id'";
		if ( !($resultOBJ = mysql_db_query( $DB, $Q4 ) ) ) {
			$message = "��ƮwŪ�����~1!!";
			show_page_d ( $message );
			return;
		}
		$row2 = mysql_fetch_array($resultOBJ);    

		$Q5 = "Insert Into take_course (group_id,course_id,student_id,validated,credit,year,term) values ('$row2[0]','$course_id','".$a_id."','1','$credit_id','$course_year','$course_term')";
		if ( !($resultOBJ = mysql_db_query( $DB, $Q5 ) ) ) {
			$message = "��Ʈw�g�J���~2!!";
			show_page_d ( $message );
			return;
		}
		$Q6 = "Select a_id From homework";
		if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q6 ) ) ) {
			$message = "��ƮwŪ�����~2!!";
			show_page_d ( $message );
			return;
		}
		while($row3 = mysql_fetch_array($resultOBJ))
		{
			$sql = "SELECT * FROM handin_homework where homework_id='$row3[a_id]' AND student_id='$a_id'";
			$res = mysql_query($sql);
			if(mysql_num_rows($res) == 0)
			{
				$Q7 = "Insert Into handin_homework (homework_id,student_id) values ('".$row3['a_id']."','".$a_id."')";
				if ( !( mysql_db_query( $DB.$course_id, $Q7 ) ) ) {
					$message = "��Ʈw�g�J���~3!!";
					show_page_d ( $message );
					return;
				}
			}
		}
		$Q8 = "Select a_id From exam";
		if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q8 ) ) ) {
			$message = "��ƮwŪ�����~3!!";
			show_page_d ( $message );
			return;
		}
		while($row4 = mysql_fetch_array($resultOBJ))
		{
			$sql = "SELECT * FROM take_exam where exam_id='$row4[a_id]' AND student_id='$a_id'";
			$res = mysql_query($sql);
			if(mysql_num_rows($res) == 0)
			{
				$Q9 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$a_id."', '-1')";
				if ( !( mysql_db_query( $DB.$course_id, $Q9 ) ) ) {
					$message = "��Ʈw�g�J���~4!!";
					show_page_d ( $message );
					return;
				}
			}
		}
		
/*		//coop
		$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
		while($row_coop = mysql_fetch_array ( $resultcoop )) {
			mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$stud_id."','0')");
			mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$a_id."','-1')");
		}
*/		
		if ( $version =="C" )
			$message = "�ǥͤw�[�J����";
		else
			$message = "Students Add Complete!!!";
		include("Generate_studinfo.php");
		$stud_name = "";
		$stud_id = "";
	}
	else
	{
		$row1 = mysql_fetch_array($resultOBJ1);
		if ( $row1['authorization'] != '3' ) {
			if ( $version == "C" ) {
				$message = "�ϥΪ̤w�s�b";
			}
			else {
				$message = "ID had been exist";
			}
			show_page_d ( $message );
			return;
		}
		if( $action == "update" && ($Submit == "�[�J�B��s" || $Submit == "ADD & Update") )
		{
			$Q2 = "Update user Set name='".$stud_name."' Where id='".$stud_id."'";
			if ( !mysql_db_query( $DB, $Q2 ) )
			{
				$message = "��Ʈw��s���~!!";
				show_page_d ( $message );
				return;
			}
		}
		if ( strcmp($stud_name, $row1['name']) && $action != "update" )
		{
			if($version=="C")
				$message = "�m�W�P��Ʈw ".$row1['name']." ���P �n��s��??";
			else
				$message = "Do you want to UPDATE Name:".$row1['name']." with $stud_name";
			$action = "update";
		}
		else {
			$Q2 = "Select student_id, credit From take_course Where course_id='$course_id' And student_id='".$row1['a_id']."' and year='$course_year' and term = '$course_term'";
			if ( !($resultOBJ = mysql_db_query( $DB, $Q2 ) ) ) {
				$message = "��ƮwŪ�����~1!!";
				show_page_d ( $message );
				return;
			}
			if( mysql_num_rows($resultOBJ) == 0 )
			{			
				$Q3 = "Select group_id From course Where a_id='$course_id'";
				if ( !($resultOBJ = mysql_db_query( $DB, $Q3 ) ) ) {
					$message = "��ƮwŪ�����~2!!";
					show_page_d ( $message );
					return;
				}
				$row2 = mysql_fetch_array($resultOBJ);
				$Q4 = "Insert Into take_course (group_id,course_id,student_id,validated,credit,year,term) values ('".$row2['group_id']."','$course_id','".$row1['a_id']."','1','$credit_id','$course_year','$course_term')";
	
				if ( !($resultOBJ = mysql_db_query( $DB, $Q4 ) ) ) {
					$message = "��Ʈw�g�J���~1!!";
					show_page_d ( $message );
					return;
				}
				$Q5 = "Select a_id From homework";
				if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q5 ) ) ) {
					$message = "��ƮwŪ�����~3!!";
					show_page_d ( $message );
					return;
				}
				while($row3 = mysql_fetch_array($resultOBJ))
				{
					$sql = "SELECT * FROM handin_homework where homework_id='$row3[a_id]' AND student_id='$row1[a_id]'";
					$res = mysql_query($sql);
					if(mysql_num_rows($res) == 0)
					{
						$Q6 = "Insert Into handin_homework (homework_id,student_id) values ('".$row3['a_id']."','".$row1['a_id']."')";
						if ( !( mysql_db_query( $DB.$course_id, $Q6 ) ) ) {
							$message = "��Ʈw�g�J���~2!!";
							show_page_d ( $message );
							return;
						}
					}
				}
				$Q7 = "Select a_id From exam";
				if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) ) ) {
					$message = "��ƮwŪ�����~4!!";
					show_page_d ( $message );
					return;
				}
				while($row4 = mysql_fetch_array($resultOBJ))
				{
					$sql = "SELECT * FROM take_exam where exam_id='$row4[a_id]' AND student_id='$row1[a_id]'";
					$res = mysql_query($sql);
					if(mysql_num_rows($res) == 0)
					{
						$Q8 = "Insert Into take_exam (exam_id,student_id, grade) values ('".$row4['a_id']."','".$row1['a_id']."','-1')";
						if ( !( mysql_db_query( $DB.$course_id, $Q8 ) ) ) {
							//$message = "��Ʈw�g�J���~3!!";
							show_page_d ( $message );
							return;
						}
					}
				}
/*				
				//coop
				$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
				while($row_coop = mysql_fetch_array ( $resultcoop )) {
					mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$stud_id."','0')");
					mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$row1['a_id']."','-1')");
				}
*/	
				if ( $version =="C" )
					$message = "�ǥͤw�[�J����";
				else
					$message = "Students Add Complete!!!";
				include("Generate_studinfo.php");
				$stud_name = "";
				$stud_id = "";
				$action = "";
			}
			else {
				$row5 = mysql_fetch_array ( $resultOBJ );
				//credit : 0����ť��, 1�����ץ�, �쥻�@�߷s�W�����ץͧ令�ѿ��(nocredit)�M�w by intree
				if ( $row5['credit'] == "0" ) {
					$Q3 = "update take_course set credit = '$credit_id' where course_id='$course_id' and student_id = '".$row1['a_id']."' and year='$course_year' and term = '$course_term'";
					if ( !$repeatOBJ2 = mysql_db_query( $DB, $Q3 ) )
					{
						$message = "��Ʈw��s���~!!";
						show_page_d ( $message );
						return;
					}
					$Q5 = "Select a_id From homework";
					if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q5 ) ) ) {
						$message = "��ƮwŪ�����~3!!";
						show_page_d ( $message );
						return;
					}
					while($row3 = mysql_fetch_array($resultOBJ))
					{
						$sql = "SELECT * FROM handin_homework where homework_id='$row3[a_id]' AND student_id='$row1[a_id]'";
						$res = mysql_query($sql);
						if(mysql_num_rows($res) == 0)
						{
							$Q6 = "Insert Into handin_homework (homework_id,student_id) values ('".$row3['a_id']."','".$row1['a_id']."')";
							if ( !( mysql_db_query( $DB.$course_id, $Q6 ) ) ) {
								$message = "��Ʈw�g�J���~2!!";
								show_page_d ( $message );
								return;
							}
						}
					}
					$Q7 = "Select a_id From exam";
					if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) ) ) {
						$message = "��ƮwŪ�����~4!!";
						show_page_d ( $message );
						return;
					}
					while($row4 = mysql_fetch_array($resultOBJ))
					{
						$sql = "SELECT * FROM take_exam where exam_id='$row4[a_id]' AND student_id='$row1[a_id]'";
						$res = mysql_query($sql);
						if(mysql_num_rows($res) == 0)
						{
							$Q8 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$row1['a_id']."','-1')";
							if ( !( mysql_db_query( $DB.$course_id, $Q8 ) ) ) {
								$message = "��Ʈw�g�J���~3!!";
								show_page_d ( $message );
								return;
							}
						}
					}
/*					
					//coop
					$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
					while($row_coop = mysql_fetch_array ( $resultcoop )) {
						mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$stud_id."','0')");
						mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$row1['a_id']."','-1')");
					}
*/		
					if ( $version =="C" )
						$message = "�ǥͤw�[�J����";
					else
						$message = "Students Add Complete!!!";
					include("Generate_studinfo.php");
					$stud_name = "";
					$stud_id = "";
					$action = "";
				}
				else {
					if($version=="C")
						$message = "�Ǹ�:$stud_id ����, �ӵ���Ƥw�s�b!!!";
					else
						$message = "ID:$stud_id overlaps, the record exists!!!";
				}
			}
		}
	}
	show_page_d( $message );
}

function show_page_d ( $message ) {
	global $version, $stud_name, $stud_id, $action, $skinnum;
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate("./templates");
	if($version=="C")
		$tpl->define(array(main => "TSInsertFrame1_Ch.tpl"));
	else
		$tpl->define(array(main => "TSInsertFrame1_En.tpl"));
	$tpl->assign(NAME, $stud_name);
	$tpl->assign(ID, $stud_id);
	$tpl->assign(MESSAGE, $message);
	$tpl->assign(ACTION, $action);
	$tpl->assign( SKINNUM , $skinnum );
	if ( $action == "update" ) {
		if ( $version == "C" ) {
			$tpl->assign(SUBMIT, "�[�J�B��s");
			$tpl->assign(SUB2, "<input type=submit name=Submit value=\"�[�J����s\" onclick=MsgWin()>" );
		}
		else {
			$tpl->assign(SUBMIT, "ADD & Update");
			$tpl->assign(SUB2, "<input type=submit name=Submit value=\"ADD But Update\" onclick=MsgWin()>" );
		}
		$tpl->assign(TYPE, hidden);
		$tpl->assign(IV, $stud_id);
		$tpl->assign(NAMV, $stud_name);
	}
	else {
		if ( $version == "C" )
			$tpl->assign(SUBMIT, "�s�W");
		else
			$tpl->assign(SUBMIT, "New");
		$tpl->assign(SUB2, "" );
		$tpl->assign(TYPE, text);
		$tpl->assign(IV, "");
		$tpl->assign(NAMV, "");
	}
	$tpl->parse(BODY, "main");
	$tpl->FastPrint("BODY");
}
?>

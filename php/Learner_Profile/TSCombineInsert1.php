<?php
/**************************/
/*�ɦW:TSCombineInsert1.php*/
/*����:�h���ǥ͸�ƿ�J(�W��)*/
/*�����ɮ�:*/
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
if ( $action == "insert" && isset($aid) && isset($id) && isset($name) ) {
	add_stu();
}
else if($action == "list" && isset($cid) ){
	show_stu_list($cid);
}
else{
	show_other_course();
}

function show_other_course()
{
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $skinnum, $course_id, $user_id, $version, $course_year, $course_term;
	
	include("class.FastTemplate.php3");
	$tpl = new FastTemplate ( "./templates" );
	if ( $version == "C" )
		$tpl->define ( array ( body => "TSCombineCourse.tpl" ) );
	else
		$tpl->define ( array ( body => "TSCombineCourse_E.tpl" ) );
	$tpl->define_dynamic ( "course_list" , "body" );
	$tpl->assign( SKINNUM , $skinnum );
	$color = "#000066";
	$tpl->assign( COLOR , $color );
	
	if ( $version == "C" ) {
		$tpl->assign( CNO , "<font color =#FFFFFF>�ҵ{�s��</font>" );
		$tpl->assign( CNAME , "<font color =#FFFFFF>�ҵ{�W��</font>" );
	}
	else {
		$tpl->assign( CNO , "<font color =#FFFFFF>No.</font>" );
		$tpl->assign( CNAME , "<font color =#FFFFFF>Course Name</font>" );
	}
	
	$tpl->parse ( COURSE_LIST, "course_list" );
	//���X���Юv��~�ת���L�½ҽҵ{	
	$Q1 = "select c.a_id, c.course_no, c.name from user u, teach_course tc, course c where u.id='".$user_id."' and u.a_id = tc.teacher_id and tc.year = '".$course_year."' and tc.term = '".$course_term."' and c.a_id = tc.course_id and c.a_id != '".$course_id."'";
	if ($result1 = mysql_db_query($DB,$Q1)){
		while($row1 = mysql_fetch_array($result1) ){
			if ( $color == "#E6FFFC" )
				$color = "#F0FFEE";
			else
				$color = "#E6FFFC";
			$tpl->assign( COLOR , $color );
			$tpl->assign( CNO , $row1['course_no'] );			
			$tpl->assign( CNAME , "<a href='TSCombineInsert1.php?action=list&cid=".$row1['a_id']."'>".$row1['name']."</a>" );
			$tpl->parse ( COURSE_LIST, ".course_list" );
		}					
	}
	$tpl->parse( BODY, "body" );
	$tpl->FastPrint("BODY");					
}

function show_stu_list($cid) {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $cid, $version, $PHPSESSID, $course_year, $course_term;
	
	$Q1 = "select u.a_id, u.id, u.name from user u, take_course tc where tc.course_id = ".$cid." AND tc.year = ".$course_year." AND tc.term= ".$course_term." AND u.a_id = tc.student_id";
	if ($result1 = mysql_db_query($DB,$Q1)){
	
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if ( $version == "C" )
			$tpl->define ( array ( body => "TSCombineInsert1.tpl" ) );
		else
			$tpl->define ( array ( body => "TSCombineInsert1_E.tpl" ) );
			
		$tpl->define_dynamic ( "student_list" , "body" );			
		$index = 0;
		while($row1 = mysql_fetch_array($result1)){
			if($index%5 == 0){
				$tpl->assign( LINE , "<TR>" );
			}
			else{
				$tpl->assign( LINE , "" );
			}
			$tpl->assign( INDEX , $index );
			$tpl->assign( STUAID , $row1['a_id'] );			
			$tpl->assign( STUID , $row1['id'] );		
			$tpl->assign( STUNAME , $row1['name']);						
			$tpl->parse ( STUDENT_LIST, ".student_list" );
			$index++;
		}
		
		$Q2 = "select * from course where a_id ='".$cid."'";
		$result2 = mysql_db_query($DB,$Q2);
		$row2 = mysql_fetch_array($result2);
		$tpl->assign( CNAME, $row2['name'] );
		$tpl->assign( STUNUM, $index );
		$tpl->assign( PHPSESSIONID, $PHPSESSID );
		$tpl->assign( IMCID, $cid );
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
	else{
		$error = "mysql��ƮwŪ�����~_1!!";
		echo "$error<br>";
		return ;
	}

}

function add_stu () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $aid, $id, $name, $course_id, $importcid, $version, $course_year, $course_term;

	for($i=0 ; $i < count($aid) ; $i++ )
	{
		//�j�M�O�_�w�O�X�ֽҵ{���ǥ� combine_course_id != -1
		$Q1 = "Select t.student_id, t.credit From take_course t,user u Where t.course_id='$course_id' and u.a_id = '".$aid[$i]."' and t.student_id = u.a_id and t.year='$course_year' and t.term = '$course_term' and combine_course_id != '-1'";
		if ( !$repeatOBJ = mysql_db_query( $DB, $Q1 ) )
		{
			$message = "��ƮwŪ�����~1!!";
			show_page ( "not_access.tpl",  $message );
			return;
		}

		if(mysql_num_rows ( $repeatOBJ ) != 0) {
			if ( $rowrepeat = mysql_fetch_array( $repeatOBJ ) ) {
				//�w�O�֯Z�W�ҥB�����ץ͡A��s�֯Z�ҵ{�y����
				if ( $rowrepeat['credit'] == "1" ) {
					$Q3 = "update take_course set combine_course_id = '".$importcid."' where course_id='$course_id' and student_id = '".$rowrepeat['student_id']."'  and year='$course_year' and term = '$course_term'";
					if ( !$repeatOBJ2 = mysql_db_query( $DB, $Q3 ) )
					{
						$message = "��Ʈw��s���~3!!";
						show_page ( "not_access.tpl",  $message );
						return;
					}
				}
				//�w�O�֯Z�W�ҥB����ť�͡A��s�֯Z�ҵ{�y�����B�ର���ץ�
				else {
					$Q3 = "update take_course set credit = '1', combine_course_id = '".$importcid."' where course_id='$course_id' and student_id = '".$aid[$i]."'  and year='$course_year' and term = '$course_term'";
					if ( !$repeatOBJ2 = mysql_db_query( $DB, $Q3 ) )
					{
						$message = "��Ʈw��s���~3!!";
						show_page ( "not_access.tpl",  $message );
						return;
					}
				}
			}
			else {
				continue;
			}
		}
		else {
			$Q4 = "Select group_id From course Where a_id='$course_id'";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q4 ) )
			{
				$message = "��ƮwŪ�����~4!!";
				show_page ( "not_access.tpl",  $message );
				return;
			}
			$row4 = mysql_fetch_array($resultOBJ);
			
			$Q5 = "Insert Into take_course (group_id,course_id,student_id,combine_course_id,validated, credit, year, term) values('".$row4['group_id']."','$course_id','".$aid[$i]."','".$importcid."','1','1', '$course_year','$course_term')";			
			if ( !$resultOBJ = mysql_db_query( $DB, $Q5 ) )
			{
				continue; //���Ѫ�ܭ쥻�w�O���ץ� �ҥH���z��
			}
		}
		
		//�P�B�@�~
		$Q6 = "Select a_id From homework";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q6 ) )
		{
			$message = "��ƮwŪ�����~6!!";
			echo $message."<BR>";
			break;
		}
		while($row6 = mysql_fetch_array($resultOBJ))
		{
			if(!isUserInHandin_Homework($course_id, $row6['a_id'], $aid[$i]) ){	
				$Q7 = "Insert Into handin_homework (homework_id,student_id) values('".$row6['a_id']."','".$aid[$i]."')";
				if ( !mysql_db_query( $DB.$course_id, $Q7 ) )
				{
					echo "�s�W����_7<br>";
					continue ;				
				}
			}
		}
		
		//�P�B����
		$Q8 = "Select a_id From exam";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q8 ) )
		{
			$message = "��ƮwŪ�����~8!!";
			echo $message."<BR>";
			break;
		}
		while($row8 = mysql_fetch_array($resultOBJ))
		{
			if(!isUserInTakeExam($course_id, $row8['a_id'], $aid[$i]) ){	
				$Q9 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row8['a_id']."','".$aid[$i]."','-1')";
				if ( !mysql_db_query( $DB.$course_id, $Q9 ) )
				{
					echo "�s�W����_9<br>";
					continue ;	
				}
			}
		}

		//�P�B�ݨ�
		$Q10 = "Select a_id From questionary";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q10 ) )
		{
			$message = "��ƮwŪ�����~10!!";
			echo $message."<BR>";
			break;
		}
		while($row10 = mysql_fetch_array($resultOBJ))
		{
			if(!isUserInTakeQuestionary($course_id, $row10['a_id'], $aid[$i]) ){	
				$Q11 = "Insert Into take_questionary (q_id,student_id) values ('".$row10['a_id']."','".$aid[$i]."')";
				if ( !mysql_db_query( $DB.$course_id, $Q11 ) )
				{
					echo "�s�W����_11<br>";
					continue ;	
				}
			}
		}
/*			
		//coop
		$resultcoop = mysql_db_query( $DBC.$course_id, "Select a_id From coop");
		while($row_coop = mysql_fetch_array ( $resultcoop )) {
			mysql_db_query( $DBC.$course_id, "Insert Into coop_".$row_coop['a_id']."_group (group_num,student_id,duty) values ('-1','".$id[$i]."','0')");
			mysql_db_query( $DBC.$course_id, "Insert Into take_coop (case_id,student_id,grade) values ('".$row_coop['a_id']."','".$row1['a_id']."','-1')");
		}
*/

	}
	include("Generate_studinfo.php");

	if( $version=="C" )
		show_page( "not_access.tpl" ,"�ǥͤw�פJ����", "", "<a href=\"./TSInsertMS.php\">�^�ǥͷs�W</a>");
	else
		show_page( "not_access.tpl" ,"Students imported Complete", "", "<a href=\"./TSInsertMS.php\">Back to New Management</a>");

	echo "INSERT";
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

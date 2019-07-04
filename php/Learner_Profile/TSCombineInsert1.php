<?php
/**************************/
/*檔名:TSCombineInsert1.php*/
/*說明:多筆學生資料輸入(上傳)*/
/*相關檔案:*/
/*************************/
require 'fadmin.php';
if(!(isset($PHPSESSID) && $check = session_check_teach($PHPSESSID)) )
{
	show_page( "not_access.tpl" ,"權限錯誤");
	exit;
}
if($check != 2)
{
	if( $version=="C" ) {
		show_page( "not_access.tpl" ,"你沒有權限使用此功能");
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
		$tpl->assign( CNO , "<font color =#FFFFFF>課程編號</font>" );
		$tpl->assign( CNAME , "<font color =#FFFFFF>課程名稱</font>" );
	}
	else {
		$tpl->assign( CNO , "<font color =#FFFFFF>No.</font>" );
		$tpl->assign( CNAME , "<font color =#FFFFFF>Course Name</font>" );
	}
	
	$tpl->parse ( COURSE_LIST, "course_list" );
	//取出此教師當年度的其他授課課程	
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
		$error = "mysql資料庫讀取錯誤_1!!";
		echo "$error<br>";
		return ;
	}

}

function add_stu () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $aid, $id, $name, $course_id, $importcid, $version, $course_year, $course_term;

	for($i=0 ; $i < count($aid) ; $i++ )
	{
		//搜尋是否已是合併課程的學生 combine_course_id != -1
		$Q1 = "Select t.student_id, t.credit From take_course t,user u Where t.course_id='$course_id' and u.a_id = '".$aid[$i]."' and t.student_id = u.a_id and t.year='$course_year' and t.term = '$course_term' and combine_course_id != '-1'";
		if ( !$repeatOBJ = mysql_db_query( $DB, $Q1 ) )
		{
			$message = "資料庫讀取錯誤1!!";
			show_page ( "not_access.tpl",  $message );
			return;
		}

		if(mysql_num_rows ( $repeatOBJ ) != 0) {
			if ( $rowrepeat = mysql_fetch_array( $repeatOBJ ) ) {
				//已是併班上課且為正修生，更新併班課程流水號
				if ( $rowrepeat['credit'] == "1" ) {
					$Q3 = "update take_course set combine_course_id = '".$importcid."' where course_id='$course_id' and student_id = '".$rowrepeat['student_id']."'  and year='$course_year' and term = '$course_term'";
					if ( !$repeatOBJ2 = mysql_db_query( $DB, $Q3 ) )
					{
						$message = "資料庫更新錯誤3!!";
						show_page ( "not_access.tpl",  $message );
						return;
					}
				}
				//已是併班上課且為旁聽生，更新併班課程流水號且轉為正修生
				else {
					$Q3 = "update take_course set credit = '1', combine_course_id = '".$importcid."' where course_id='$course_id' and student_id = '".$aid[$i]."'  and year='$course_year' and term = '$course_term'";
					if ( !$repeatOBJ2 = mysql_db_query( $DB, $Q3 ) )
					{
						$message = "資料庫更新錯誤3!!";
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
				$message = "資料庫讀取錯誤4!!";
				show_page ( "not_access.tpl",  $message );
				return;
			}
			$row4 = mysql_fetch_array($resultOBJ);
			
			$Q5 = "Insert Into take_course (group_id,course_id,student_id,combine_course_id,validated, credit, year, term) values('".$row4['group_id']."','$course_id','".$aid[$i]."','".$importcid."','1','1', '$course_year','$course_term')";			
			if ( !$resultOBJ = mysql_db_query( $DB, $Q5 ) )
			{
				continue; //失敗表示原本已是正修生 所以不理它
			}
		}
		
		//同步作業
		$Q6 = "Select a_id From homework";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q6 ) )
		{
			$message = "資料庫讀取錯誤6!!";
			echo $message."<BR>";
			break;
		}
		while($row6 = mysql_fetch_array($resultOBJ))
		{
			if(!isUserInHandin_Homework($course_id, $row6['a_id'], $aid[$i]) ){	
				$Q7 = "Insert Into handin_homework (homework_id,student_id) values('".$row6['a_id']."','".$aid[$i]."')";
				if ( !mysql_db_query( $DB.$course_id, $Q7 ) )
				{
					echo "新增失敗_7<br>";
					continue ;				
				}
			}
		}
		
		//同步測驗
		$Q8 = "Select a_id From exam";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q8 ) )
		{
			$message = "資料庫讀取錯誤8!!";
			echo $message."<BR>";
			break;
		}
		while($row8 = mysql_fetch_array($resultOBJ))
		{
			if(!isUserInTakeExam($course_id, $row8['a_id'], $aid[$i]) ){	
				$Q9 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row8['a_id']."','".$aid[$i]."','-1')";
				if ( !mysql_db_query( $DB.$course_id, $Q9 ) )
				{
					echo "新增失敗_9<br>";
					continue ;	
				}
			}
		}

		//同步問卷
		$Q10 = "Select a_id From questionary";
		if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q10 ) )
		{
			$message = "資料庫讀取錯誤10!!";
			echo $message."<BR>";
			break;
		}
		while($row10 = mysql_fetch_array($resultOBJ))
		{
			if(!isUserInTakeQuestionary($course_id, $row10['a_id'], $aid[$i]) ){	
				$Q11 = "Insert Into take_questionary (q_id,student_id) values ('".$row10['a_id']."','".$aid[$i]."')";
				if ( !mysql_db_query( $DB.$course_id, $Q11 ) )
				{
					echo "新增失敗_11<br>";
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
		show_page( "not_access.tpl" ,"學生已匯入完成", "", "<a href=\"./TSInsertMS.php\">回學生新增</a>");
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

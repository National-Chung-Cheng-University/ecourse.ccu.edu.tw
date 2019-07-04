<?php
/**************************/
/*檔名:TSFileInsert1.php*/
/*說明:多筆學生資料輸入(上傳)*/
/*相關檔案:*/
/*TSFileInsert2.php*/
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
if ( isset($id) && isset($name) ) {
	add_stu();
}
else {
	show_stu_list();
}

function add_stu () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DBC, $DB_PASSWORD, $id, $name, $course_id, $version, $course_year, $course_term;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "資料庫連結錯誤!!" );
		return;
	}

	for($i=0 ; $i < count($id) ; $i++ )
	{
		$Q1 = "Select id,name,authorization From user Where id='".$id[$i]."'";
		if ( !$resultOBJ = mysql_db_query( $DB, $Q1) )
		{
			$message = "資料庫讀取錯誤1!!";
			show_page ( "not_access.tpl",  $message );
			return;
		}
		
		if(mysql_num_rows($resultOBJ) == 0 )
		{
      			$Q2 = "Insert Into user (name,id,authorization, forbear) values ('$name[$i]','$id[$i]','3', '1800')";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q2 ) )
			{
				$message = "資料庫寫入錯誤1!!";
				show_page ( "not_access.tpl",  $message );
				return;
			}
			$a_id = mysql_insert_id();
			$Q3 = "Select group_id From course Where a_id='$course_id'";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q3) )
			{
				$message = "資料庫讀取錯誤2!!";
				show_page ( "not_access.tpl",  $message );
				return;
			}
			$row2 = mysql_fetch_array($resultOBJ);
			$Q4 = "Insert Into take_course (group_id,course_id,student_id,validated,credit,year,term) values ('".$row2['group_id']."','$course_id','".$a_id."','1','1','$course_year','$course_term')";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q4) )
			{
				$message = "資料庫寫入錯誤2!!";
				show_page ( "not_access.tpl",  $message );
				return;
			}
			$Q5 = "Select a_id From homework";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q5 ) )
			{
				$message = "資料庫讀取錯誤3!!";
				show_page ( "not_access.tpl",  $message );
				return;
			}
			while($row3 = mysql_fetch_array($resultOBJ))
			{
				$Q6 = "Insert Into handin_homework (homework_id,student_id) values ('".$row3['a_id']."','".$a_id."')";
				if ( !mysql_db_query( $DB.$course_id, $Q6 ) )
				{
					$message = "資料庫寫入錯誤3!!";
					show_page ( "not_access.tpl",  $message );
					return;
				}
			}
			$Q7 = "Select a_id From exam";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) )
			{
				$message = "資料庫讀取錯誤4!!";
				show_page ( "not_access.tpl",  $message );
				return;
			}
			while($row4 = mysql_fetch_array($resultOBJ))
			{
				$Q8 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$a_id."','-1')";
				if ( !mysql_db_query( $DB.$course_id, $Q8) )
				{
					$message = "資料庫寫入錯誤4!!";
					show_page ( "not_access.tpl",  $message );
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
					$message = "資料庫更新錯誤!!";
					show_page ( "not_access.tpl",  $message );
					return;
				}
			}
			
			$Q3 = "Select t.student_id, t.credit From take_course t,user u Where t.course_id='$course_id' and u.id = '".$id[$i]."' and t.student_id = u.a_id and t.year='$course_year' and t.term = '$course_term'";
			if ( !$repeatOBJ = mysql_db_query( $DB, $Q3 ) )
			{
				$message = "資料庫讀取錯誤1!!";
				show_page ( "not_access.tpl",  $message );
				return;
			}

			$Q4 = "Select a_id From user Where id='".$id[$i]."'";
			if ( !$resultOBJ = mysql_db_query( $DB, $Q4 ) )
			{
				$message = "資料庫讀取錯誤2!!";
				show_page ( "not_access.tpl",  $message );
				return;
			}

			$row1 = mysql_fetch_array($resultOBJ);

			if(mysql_num_rows ( $repeatOBJ ) != 0) {
				if ( $rowrepeat = mysql_fetch_array( $repeatOBJ ) ) {
					if ( $rowrepeat['credit'] == "1" ) {
						continue;
					}
					else {
						$Q3 = "update take_course set credit = '1' where course_id='$course_id' and student_id = '".$rowrepeat['student_id']."'  and year='$course_year' and term = '$course_term'";
						if ( !$repeatOBJ2 = mysql_db_query( $DB, $Q3 ) )
						{
							$message = "資料庫更新錯誤!!";
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
				$Q5 = "Select group_id From course Where a_id='$course_id'";
				if ( !$resultOBJ = mysql_db_query( $DB, $Q5 ) )
				{
					$message = "資料庫讀取錯誤3!!";
					show_page ( "not_access.tpl",  $message );
					return;
				}
				$row2 = mysql_fetch_array($resultOBJ);
				
				$Q6 = "Insert Into take_course (group_id,course_id,student_id,validated, credit, year, term) values('".$row2['group_id']."','$course_id','".$row1['a_id']."','1','1', '$course_year','$course_term')";
				if ( !$resultOBJ = mysql_db_query( $DB, $Q6 ) )
				{
					$message = "資料庫寫入錯誤1!!";
					show_page ( "not_access.tpl",  $message );
					return;
				}
			}

			$Q7 = "Select a_id From homework";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) )
			{
				$message = "資料庫讀取錯誤4!!";
				show_page ( "not_access.tpl",  $message );
				return;
			}
			while($row3 = mysql_fetch_array($resultOBJ))
			{
				$Q8 = "Insert Into handin_homework (homework_id,student_id) values('".$row3['a_id']."','".$row1['a_id']."')";
				if ( !mysql_db_query( $DB.$course_id, $Q8 ) )
				{
					$message = "資料庫寫入錯誤2!!";
					show_page ( "not_access.tpl",  $message );
					return;
				}
			}
			$Q9 = "Select a_id From exam";
			if ( !$resultOBJ = mysql_db_query( $DB.$course_id, $Q9 ) )
			{
				$message = "資料庫讀取錯誤5!!";
				show_page ( "not_access.tpl",  $message );
				return;
			}
			while($row4 = mysql_fetch_array($resultOBJ))
			{
				$Q10 = "Insert Into take_exam (exam_id,student_id,grade) values ('".$row4['a_id']."','".$row1['a_id']."','-1')";
				if ( !mysql_db_query( $DB.$course_id, $Q10 ) )
				{
					$message = "資料庫寫入錯誤3!!";
					show_page ( "not_access.tpl",  $message );
					return;
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
	}
	include("Generate_studinfo.php");

	if( $version=="C" )
		show_page( "not_access.tpl" ,"學生已匯入完成", "", "<a href=\"./TSInsertMS.php\">回學生新增</a>");
	else
		show_page( "not_access.tpl" ,"Students imported Complete", "", "<a href=\"./TSInsertMS.php\">Back to New Management</a>");
}

function show_stu_list () {
	global $DB_SERVER, $DB_LOGIN, $DB, $DB_PASSWORD, $course_id, $SERVER_NAME, $version, $PHPSESSID, $user_period;
	if ( !($link = mysql_pconnect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD)) ) {
		echo ( "資料庫連結錯誤!!" );
		return;
	}
	$Q1 = "Select course_no From course Where a_id='$course_id'";
	$resultOBJ = mysql_db_query( $DB, $Q1);
	if ( mysql_num_rows( $resultOBJ ) == 1 ) {
		$row = mysql_fetch_array($resultOBJ);
		$course_no = explode("_",$row['course_no']);
		$c_id = $course_no[0];
		$g_id = $course_no[1];
		$host = getenv("SERVER_NAME");
		if ($host == "")
			$host = $SERVER_NAME;
		if ( $user_period == 0 ) {
			if ( substr( $c_id, 3, 1 ) == "A" || substr( $c_id, 3, 1 ) == "B" || substr( $c_id, 3, 1 ) == "C" || substr( $c_id, 3, 1 ) == "D" )
				header("Location:http://kiki.ccu.edu.tw/~ccmisp07/cgi-bin/class/Namelist.cgi?course_id=$c_id&group=$g_id&host=$host&version=$version&prog=/php/Learner_Profile/TSImportInsert1.php&PHPSESSID=$PHPSESSID");
			else
				header("Location:http://kiki.ccu.edu.tw/~ccmisp06/cgi-bin/class/Namelist.cgi?course_id=$c_id&group=$g_id&host=$host&version=$version&prog=/php/Learner_Profile/TSImportInsert1.php&PHPSESSID=$PHPSESSID");
		}
		else {
			if ( substr( $c_id, 3, 1 ) == "A" || substr( $c_id, 3, 1 ) == "B" || substr( $c_id, 3, 1 ) == "C" || substr( $c_id, 3, 1 ) == "D" )
				header("Location:http://kiki.ccu.edu.tw/~ccmisp04/cgi-bin/class/Namelist.cgi?course_id=$c_id&group=$g_id&host=$host&version=$version&prog=/php/Learner_Profile/TSImportInsert1.php&PHPSESSID=$PHPSESSID");
			else
				header("Location:http://kiki.ccu.edu.tw/~ccmisp03/cgi-bin/class/Namelist.cgi?course_id=$c_id&group=$g_id&host=$host&version=$version&prog=/php/Learner_Profile/TSImportInsert1.php&PHPSESSID=$PHPSESSID");
		}
	}else {
		include("class.FastTemplate.php3");
		$tpl = new FastTemplate ( "./templates" );
		if ( $version == "C" )
			$tpl->define ( array ( body => "TSImportInsert1.tpl" ) );
		else
			$tpl->define ( array ( body => "TSImportInsert1_E.tpl" ) );
		$tpl->define_dynamic ( "list" , "body" );
		$color = "#4d6eb2";
		$tpl->assign( COLOR , $color );
		while ( $row = mysql_fetch_array ( $resultOBJ ) ) {
			$course_no = explode("_",$row['course_no']);
			$c_id = $course_no[0];
			$g_id = $course_no[1];
			$host = getenv("SERVER_NAME");
			if ($host == "")
				$host = $SERVER_NAME;
			$tpl->assign ( CID , $c_id );
			$tpl->assign ( GID , $g_id );
			$tpl->assign( HOST , $host );
			$tpl->assign( PHPSID , $PHPSESSID );
			if ( $color == "#BFCEBD" )
				$color = "#D0DFE3";
			else
				$color = "#BFCEBD";
			$tpl->assign( COLOR , $color );
			if ( $user_period == 0 ) {
				if ( substr( $c_id, 3, 1 ) == "A" || substr( $c_id, 3, 1 ) == "B" || substr( $c_id, 3, 1 ) == "C" || substr( $c_id, 3, 1 ) == "D" ) {
					$tpl->assign( PATH , "ccmisp07" );
				}
				else {
					$tpl->assign( PATH , "ccmisp06" );
				}
			}
			else {
				if ( substr( $c_id, 3, 1 ) == "A" || substr( $c_id, 3, 1 ) == "B" || substr( $c_id, 3, 1 ) == "C" || substr( $c_id, 3, 1 ) == "D" ) {
					$tpl->assign( PATH , "ccmisp04" );
				}
				else {
					$tpl->assign( PATH , "ccmisp03" );
				}
			}
			$tpl->parse( DLIST, ".list" );
			
		}
		$tpl->parse( BODY, "body" );
		$tpl->FastPrint("BODY");
	}
}
?>

<?php
/**************************/
/*檔名:TSInsertFrame1.php*/
/*說明:單筆學生資料輸入*/
/*相關檔案:*/
/*TSInsertFrame2.php*/
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
if( isset($stud_name) && isset($stud_id) )//判斷資料是否輸入完全
{
	if ( $stud_name == "" || $stud_id == "" ) {
		if($version=="C")
			$message = "姓名或學號尚未輸入!!!";
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
		$message = "資料庫連結錯誤!!";
		show_page_d ( $message );
	}

	//2007/07/24 : 從頁面回傳新增學生是正修生或是旁聽生 by intree
	$credit_id = $_POST["nocredit"];

	$Q1 = "Select * From user Where id='$stud_id'";  
	if ( !($resultOBJ1 = mysql_db_query( $DB, $Q1 ) ) ) {
		$message = "資料庫讀取錯誤!!";
		show_page_d ( $message );
		return;
	}
  
	if(mysql_num_rows($resultOBJ1) == 0 )
	{
		$Q2 = "Insert Into user (name,id,authorization, forbear) values ('$stud_name','$stud_id','3','1800')";
		if ( !($result = mysql_db_query( $DB, $Q2 ) ) ) {
			$message = "資料庫寫入錯誤1!!";
			show_page_d ( $message );
			return;
		}
		$a_id = mysql_insert_id();
		$Q4 = "Select group_id From course Where a_id='$course_id'";
		if ( !($resultOBJ = mysql_db_query( $DB, $Q4 ) ) ) {
			$message = "資料庫讀取錯誤1!!";
			show_page_d ( $message );
			return;
		}
		$row2 = mysql_fetch_array($resultOBJ);    

		$Q5 = "Insert Into take_course (group_id,course_id,student_id,validated,credit,year,term) values ('$row2[0]','$course_id','".$a_id."','1','$credit_id','$course_year','$course_term')";
		if ( !($resultOBJ = mysql_db_query( $DB, $Q5 ) ) ) {
			$message = "資料庫寫入錯誤2!!";
			show_page_d ( $message );
			return;
		}
		$Q6 = "Select a_id From homework";
		if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q6 ) ) ) {
			$message = "資料庫讀取錯誤2!!";
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
					$message = "資料庫寫入錯誤3!!";
					show_page_d ( $message );
					return;
				}
			}
		}
		$Q8 = "Select a_id From exam";
		if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q8 ) ) ) {
			$message = "資料庫讀取錯誤3!!";
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
					$message = "資料庫寫入錯誤4!!";
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
			$message = "學生已加入完成";
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
				$message = "使用者已存在";
			}
			else {
				$message = "ID had been exist";
			}
			show_page_d ( $message );
			return;
		}
		if( $action == "update" && ($Submit == "加入且更新" || $Submit == "ADD & Update") )
		{
			$Q2 = "Update user Set name='".$stud_name."' Where id='".$stud_id."'";
			if ( !mysql_db_query( $DB, $Q2 ) )
			{
				$message = "資料庫更新錯誤!!";
				show_page_d ( $message );
				return;
			}
		}
		if ( strcmp($stud_name, $row1['name']) && $action != "update" )
		{
			if($version=="C")
				$message = "姓名與資料庫 ".$row1['name']." 不同 要更新嗎??";
			else
				$message = "Do you want to UPDATE Name:".$row1['name']." with $stud_name";
			$action = "update";
		}
		else {
			$Q2 = "Select student_id, credit From take_course Where course_id='$course_id' And student_id='".$row1['a_id']."' and year='$course_year' and term = '$course_term'";
			if ( !($resultOBJ = mysql_db_query( $DB, $Q2 ) ) ) {
				$message = "資料庫讀取錯誤1!!";
				show_page_d ( $message );
				return;
			}
			if( mysql_num_rows($resultOBJ) == 0 )
			{			
				$Q3 = "Select group_id From course Where a_id='$course_id'";
				if ( !($resultOBJ = mysql_db_query( $DB, $Q3 ) ) ) {
					$message = "資料庫讀取錯誤2!!";
					show_page_d ( $message );
					return;
				}
				$row2 = mysql_fetch_array($resultOBJ);
				$Q4 = "Insert Into take_course (group_id,course_id,student_id,validated,credit,year,term) values ('".$row2['group_id']."','$course_id','".$row1['a_id']."','1','$credit_id','$course_year','$course_term')";
	
				if ( !($resultOBJ = mysql_db_query( $DB, $Q4 ) ) ) {
					$message = "資料庫寫入錯誤1!!";
					show_page_d ( $message );
					return;
				}
				$Q5 = "Select a_id From homework";
				if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q5 ) ) ) {
					$message = "資料庫讀取錯誤3!!";
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
							$message = "資料庫寫入錯誤2!!";
							show_page_d ( $message );
							return;
						}
					}
				}
				$Q7 = "Select a_id From exam";
				if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) ) ) {
					$message = "資料庫讀取錯誤4!!";
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
							//$message = "資料庫寫入錯誤3!!";
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
					$message = "學生已加入完成";
				else
					$message = "Students Add Complete!!!";
				include("Generate_studinfo.php");
				$stud_name = "";
				$stud_id = "";
				$action = "";
			}
			else {
				$row5 = mysql_fetch_array ( $resultOBJ );
				//credit : 0為旁聽生, 1為正修生, 原本一律新增為正修生改成由選單(nocredit)決定 by intree
				if ( $row5['credit'] == "0" ) {
					$Q3 = "update take_course set credit = '$credit_id' where course_id='$course_id' and student_id = '".$row1['a_id']."' and year='$course_year' and term = '$course_term'";
					if ( !$repeatOBJ2 = mysql_db_query( $DB, $Q3 ) )
					{
						$message = "資料庫更新錯誤!!";
						show_page_d ( $message );
						return;
					}
					$Q5 = "Select a_id From homework";
					if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q5 ) ) ) {
						$message = "資料庫讀取錯誤3!!";
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
								$message = "資料庫寫入錯誤2!!";
								show_page_d ( $message );
								return;
							}
						}
					}
					$Q7 = "Select a_id From exam";
					if ( !($resultOBJ = mysql_db_query( $DB.$course_id, $Q7 ) ) ) {
						$message = "資料庫讀取錯誤4!!";
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
								$message = "資料庫寫入錯誤3!!";
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
						$message = "學生已加入完成";
					else
						$message = "Students Add Complete!!!";
					include("Generate_studinfo.php");
					$stud_name = "";
					$stud_id = "";
					$action = "";
				}
				else {
					if($version=="C")
						$message = "學號:$stud_id 重複, 該筆資料已存在!!!";
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
			$tpl->assign(SUBMIT, "加入且更新");
			$tpl->assign(SUB2, "<input type=submit name=Submit value=\"加入不更新\" onclick=MsgWin()>" );
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
			$tpl->assign(SUBMIT, "新增");
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
